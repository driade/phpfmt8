import importlib.util
from pathlib import Path
import sys
from types import ModuleType
import unittest
from unittest.mock import Mock, patch
import warnings


ROOT = Path(__file__).resolve().parents[2]


class View:
    def __init__(self, text):
        self.text = text
        self.options = {"translate_tabs_to_spaces": True}
        self.settings_mock = Mock()
        self.settings_mock.get.side_effect = self.options.get
        self.settings_mock.set.side_effect = self.options.__setitem__

    def settings(self):
        return self.settings_mock

    def size(self):
        return len(self.text)

    def substr(self, region):
        return self.text[region[0]:region[1]]

    def insert(self, edit, offset, text):
        self.text = self.text[:offset] + text + self.text[offset:]

    def erase(self, edit, region):
        self.text = self.text[:region[0]] + self.text[region[1]:]

    def replace(self, edit, region, text):
        self.text = self.text[:region[0]] + text + self.text[region[1]:]


class PluginTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        sublime = ModuleType("sublime")
        sublime.Region = lambda start, end: (start, end)
        sublime.load_settings = Mock(return_value={})
        sublime.packages_path = Mock(return_value=str(ROOT.parent))
        sublime_plugin = ModuleType("sublime_plugin")
        sublime_plugin.TextCommand = object
        sublime_plugin.EventListener = object
        spec = importlib.util.spec_from_file_location("phpfmt_under_test", ROOT / "phpfmt.py")
        cls.plugin = importlib.util.module_from_spec(spec)
        original_path = sys.path[:]
        try:
            with patch.dict(sys.modules, sublime=sublime, sublime_plugin=sublime_plugin):
                spec.loader.exec_module(cls.plugin)
        finally:
            sys.path[:] = original_path

    def setUp(self):
        self.view = View("<?php echo 1;")
        self.edit = object()

    def assert_tabs_restored(self):
        self.assertIs(self.view.options["translate_tabs_to_spaces"], True)

    def test_sources_compile_without_syntax_warnings(self):
        for relative in ("phpfmt.py", "diff_match_patch/python3/diff_match_patch.py"):
            with self.subTest(path=relative), warnings.catch_warnings():
                warnings.simplefilter("error", SyntaxWarning)
                compile((ROOT / relative).read_bytes(), relative, "exec")

    def test_method_completion_escapes_dollars(self):
        view = Mock()
        view.file_name.return_value = str(ROOT / "Example.php")
        view.scope_name.return_value = "source.php"
        process = Mock()
        process.communicate.return_value = (b'"method($first, $second)",method,Example,method\n', b'')
        with patch.object(self.plugin.sublime, "load_settings", return_value={"autocomplete": True}), \
                patch.object(self.plugin, "print_debug"), \
                patch.object(self.plugin.os.path, "isfile", return_value=True), \
                patch.object(self.plugin.subprocess, "Popen", return_value=process):
            completions = self.plugin.PHPFmtComplete().on_query_completions(view, "method", [0])
        self.assertEqual(completions[0][1], r"method(\$first, \$second)")

    def test_patch_headers_preserve_coordinates_and_content(self):
        dmp = self.plugin.diff_match_patch()
        for header, coordinates in (
                ("@@ -1 +1 @@", (0, 1, 0, 1)),
                ("@@ -0,0 +1,3 @@", (0, 0, 0, 3)),
                ("@@ -12,3 +15,4 @@", (11, 3, 14, 4))):
            with self.subTest(header=header):
                parsed = dmp.patch_fromText(header + "\n+abc\n")
                self.assertEqual(len(parsed), 1)
                item = parsed[0]
                self.assertEqual((item.start1, item.length1, item.start2, item.length2), coordinates)
                self.assertEqual(item.diffs, [(dmp.DIFF_INSERT, "abc")])

    def test_invalid_patch_header_is_rejected(self):
        with self.assertRaises(ValueError):
            self.plugin.diff_match_patch().patch_fromText("invalid\n+abc\n")

    def test_merge_updates_buffer_and_restores_tabs(self):
        result = self.plugin.merge(self.view, -1, "<?php echo 23;", self.edit)
        self.assertEqual(result, (True, ""))
        self.assertEqual(self.view.text, "<?php echo 23;")
        self.assert_tabs_restored()

    def test_unchanged_buffer_is_not_dirty(self):
        result = self.plugin.merge(self.view, self.view.size(), self.view.text, self.edit)
        self.assertEqual(result, (False, ""))
        self.assert_tabs_restored()

    def test_empty_buffer_restores_tabs(self):
        self.view.text = "  "
        with patch.object(self.plugin, "_merge") as apply_changes:
            self.assertEqual(self.plugin.merge(self.view, -1, "new", self.edit), (False, ""))
        apply_changes.assert_not_called()
        self.assert_tabs_restored()

    def test_merge_exception_restores_original_buffer(self):
        original = self.view.text

        def fail_after_edit(*args):
            self.view.text = "partially changed"
            raise self.plugin.MergeException("mismatch", True)

        with patch.object(self.plugin, "_merge", side_effect=fail_after_edit):
            dirty, error = self.plugin.merge(self.view, -1, "new", self.edit)
        self.assertTrue(dirty)
        self.assertEqual(error, "Could not merge changes into the buffer, edit aborted: mismatch")
        self.assertEqual(self.view.text, original)
        self.assert_tabs_restored()

    def test_regular_exception_is_reported(self):
        with patch.object(self.plugin, "_merge", side_effect=RuntimeError("failed")):
            self.assertEqual(self.plugin.merge(self.view, -1, "new", self.edit), (False, "error: failed"))
        self.assert_tabs_restored()

    def test_uncaught_exception_propagates_after_restoring_tabs(self):
        with patch.object(self.plugin, "_merge", side_effect=KeyboardInterrupt):
            with self.assertRaises(KeyboardInterrupt):
                self.plugin.merge(self.view, -1, "new", self.edit)
        self.assert_tabs_restored()

    def test_rollback_failure_propagates_after_restoring_tabs(self):
        with patch.object(self.plugin, "_merge", side_effect=self.plugin.MergeException("mismatch", True)), \
                patch.object(self.view, "replace", side_effect=RuntimeError("rollback failed")):
            with self.assertRaisesRegex(RuntimeError, "rollback failed"):
                self.plugin.merge(self.view, -1, "new", self.edit)
        self.assert_tabs_restored()


if __name__ == "__main__":
    unittest.main()
