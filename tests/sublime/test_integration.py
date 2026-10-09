import os
import sqlite3
import sys
import tempfile

import sublime
from unittesting import DeferrableTestCase

from ... import phpfmt as plugin


class SublimeIntegrationTests(DeferrableTestCase):
    def setUp(self):
        self.directory = tempfile.TemporaryDirectory(prefix="phpfmt-integration-")
        self.path = os.path.join(self.directory.name, "example.php")
        self.source = "<?php\n$a=1;"
        with open(self.path, "w") as source:
            source.write(self.source)
        self.view = sublime.active_window().new_file()
        self.view.retarget(self.path)
        self.view.set_scratch(True)
        self.view.set_syntax_file("Packages/PHP/PHP.sublime-syntax")
        self.view.settings().set("auto_complete", False)
        self.view.settings().set("translate_tabs_to_spaces", True)
        self.view.run_command("insert", {"characters": self.source})

    def tearDown(self):
        self.view.close()
        self.directory.cleanup()

    def text(self):
        return self.view.substr(sublime.Region(0, self.view.size()))

    def test_plugin_loads_in_expected_editor_host(self):
        expected = tuple(int(part) for part in os.environ["PHPFMT_EXPECTED_PYTHON"].split("."))
        self.assertEqual(sys.version_info[:2], expected)
        self.assertEqual(int(sublime.version()), 4200)
        self.assertIs(plugin.sublime, sublime)
        self.assertIsInstance(self.view, sublime.View)

    def test_real_format_command_and_idempotence(self):
        self.view.run_command("fmt_now")
        yield lambda: self.text() != self.source or None
        self.assertEqual(self.text(), "<?php\n$a = 1;")
        self.assertIs(self.view.settings().get("translate_tabs_to_spaces"), True)
        formatted = self.text()
        self.view.run_command("fmt_now")
        yield 50
        self.assertEqual(self.text(), formatted)

    def test_oracle_completion_inserts_literal_dollars(self):
        with sqlite3.connect(os.path.join(self.directory.name, "oracle.sqlite")) as database:
            database.execute("CREATE TABLE classes (filename, class, extends, implements)")
            database.execute("CREATE TABLE methods (filename, class, method_name, method_call, method_signature)")
            database.execute("INSERT INTO methods VALUES (?, ?, ?, ?, ?)",
                             (self.path, "Example", "method", "method($first, $second)", "method"))
        yield lambda: "source.php" in self.view.scope_name(6) or None
        completions = plugin.PHPFmtComplete().on_query_completions(self.view, "method", [6])
        self.assertEqual(len(completions), 1)
        self.assertEqual(completions[0][1], r"method(\$first, \$second)")
        self.view.sel().clear()
        self.view.sel().add(sublime.Region(0, self.view.size()))
        self.view.run_command("insert_snippet", {"contents": completions[0][1]})
        yield lambda: self.text() == "method($first, $second)" or None
        self.assertEqual(self.text(), "method($first, $second)")

    def test_merge_failure_restores_real_buffer(self):
        self.view.run_command("phpfmt_integration_fail_merge")
        yield lambda: self.view.settings().get("integration_error") or None
        self.assertEqual(self.text(), self.source)
        self.assertIs(self.view.settings().get("integration_dirty"), True)
        self.assertEqual(self.view.settings().get("integration_error"),
                         "Could not merge changes into the buffer, edit aborted: mismatch")
        self.assertIs(self.view.settings().get("translate_tabs_to_spaces"), True)
