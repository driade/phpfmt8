"""Prepare an unlicensed, isolated evaluation install on a disposable CI runner."""
import json
import os
from pathlib import Path
import shutil
import subprocess
import tarfile
import urllib.request
import zipfile


ROOT = Path(__file__).resolve().parents[2]
TEMP = Path(os.environ["RUNNER_TEMP"]) / "phpfmt-editor"
TEMP.mkdir()
platform = os.environ["RUNNER_OS"]
suffix = {"Linux": "x64.tar.xz", "macOS": "mac.zip", "Windows": "x64.zip"}[platform]
archive = TEMP / suffix
urllib.request.urlretrieve("https://download.sublimetext.com/sublime_text_build_4200_" + suffix, archive)
if suffix.endswith(".zip"):
    if platform == "macOS":
        subprocess.run(["ditto", "-x", "-k", str(archive), str(TEMP)], check=True)
    else:
        with zipfile.ZipFile(archive) as bundle:
            bundle.extractall(TEMP)
else:
    with tarfile.open(archive) as bundle:
        bundle.extractall(TEMP, filter="data")

if platform == "Windows":
    executable = next(TEMP.rglob("sublime_text.exe"))
    data = executable.parent / "Data"
    launcher = executable.parent
elif platform == "macOS":
    app = TEMP / "Sublime Text.app"
    launcher = app / "Contents/SharedSupport/bin"
    data = Path.home() / "Library/Application Support/Sublime Text"
    subprocess.run(["/System/Library/Frameworks/CoreServices.framework/Frameworks/LaunchServices.framework/Support/lsregister",
                    "-f", str(app)], check=True)
else:
    executable = TEMP / "sublime_text/sublime_text"
    launcher = TEMP / "bin"
    launcher.mkdir()
    (launcher / "subl").symlink_to(executable)
    data = Path.home() / ".config/sublime-text"

packages = data / "Packages"
packages.mkdir(parents=True)
shutil.copytree(ROOT, packages / "phpfmt", ignore=shutil.ignore_patterns(".git", "vendor", ".ci-unittesting", "__pycache__"))
shutil.copytree(ROOT / ".ci-unittesting", packages / "UnitTesting", ignore=shutil.ignore_patterns(".git"))
(packages / "UnitTesting/aaa_ci_log.py").write_text(
    "import os, sys\n"
    "sys.stdout = sys.stderr = open(os.path.join(os.path.dirname(__file__), 'unittesting.log'), 'a', buffering=1)\n"
)
package = packages / "phpfmt"
(package / ".python-version").write_text(os.environ["PHPFMT_EXPECTED_PYTHON"])
shutil.copyfile(ROOT / "tests/sublime/commands.py", package / "integration_commands.py")
(package / "unittesting.json").write_text(json.dumps({"tests_dir": "tests/sublime", "reload_package_on_testing": False}))
user = packages / "User"
user.mkdir()
(user / "Preferences.sublime-settings").write_text(json.dumps({"close_windows_when_empty": False, "update_check": False}))
(user / "phpfmt.sublime-settings").write_text(json.dumps({
    "php_bin": shutil.which("php"), "format_on_save": False, "autocomplete": True,
    "autoimport": False, "readini": False, "psr2": False, "passes": [], "excludes": []
}))
with open(os.environ["GITHUB_PATH"], "a") as output:
    output.write(str(launcher) + "\n")
with open(os.environ["GITHUB_ENV"], "a") as output:
    output.write("SUBLIME_TEXT_PACKAGES=" + str(packages) + "\n")
