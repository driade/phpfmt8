import sublime_plugin
from unittest.mock import patch

from . import phpfmt as plugin


class PhpfmtIntegrationFailMergeCommand(sublime_plugin.TextCommand):
    def run(self, edit):
        def fail_after_edit(view, size, text, edit):
            view.insert(edit, 0, "partial edit")
            raise plugin.MergeException("mismatch", True)

        with patch.object(plugin, "_merge", side_effect=fail_after_edit):
            dirty, error = plugin.merge(self.view, -1, "new text", edit)
        self.view.settings().set("integration_dirty", dirty)
        self.view.settings().set("integration_error", error)
