# phpfmt (PHP formatter)

## Purpose

- **Sublime Text 4 package** that formats PHP code by invoking a PHP-based formatting engine.
- The engine is a pipeline of **passes** (transformations) applied over PHP tokens.
- Includes a fixture-based test harness (`.in`/`.out`) and a PHPUnit test suite.

## Repository layout

- `fmt.stub.php`
  - Main formatter implementation (pass classes, `CodeFormatter`, helpers).
  - Also acts as the PHP CLI script that Sublime Text calls (it prints a usage message when run without the expected args).
  - The fixture test harness loads this file and instantiates `CodeFormatter` directly.
- `phpfmt.py`
  - **Sublime Text plugin entrypoint** (`sublime_plugin`) that:
    - Reads `phpfmt.sublime-settings`.
    - Builds a PHP command line.
    - Executes PHP against `fmt.stub.php` with the configured options.
  - When documenting “how the editor integration works”, this is the key file.
- `php.tools.ini`
  - Default configuration file passed to the PHP formatter from the Sublime plugin.
- `phpfmt.sublime-settings`
  - Sublime package settings (php binary path, passes, excludes, PSR/WP toggles, etc.).
- `tests/`
  - `run_all_tests.php`: fixture test harness.
  - `Original/`: non-PSR fixtures.
  - `PSR/`: PSR-style fixtures (PSR decorator applied by the harness).
  - `Unit/`: PHPUnit unit tests (if present/used).
- `vendor/`
  - Composer dependencies, including PHPUnit.

## Running tests

## PHP version compatibility

- Treat **PHP 5.6** as the minimum supported runtime for any code changes in this repo.
- New formatter logic should avoid syntax/features newer than PHP 5.6 (e.g. scalar type hints, return types, null coalescing `??`, spaceship `<=>`, arrow functions, etc.).
- Some fixtures and passes are intentionally **version-gated** (e.g. PHP 8 attributes, `match`, etc.) and will be skipped when running under older runtimes.

### 1) Fixture tests (`.in`/`.out`)

- Run the full fixture suite:
  - From `tests/`: `php ./run_all_tests.php`

- If you don’t have PHP 5.6 locally, validate compatibility by running with PHP 7.4:
  - `php74 tests/run_all_tests.php`
  - If your system uses `php7.4` instead: `php7.4 tests/run_all_tests.php`
- Run a subset by test number prefix:
  - `php ./run_all_tests.php --testNumber 525`
- Show diffs (verbose):
  - `php ./run_all_tests.php -v`
  - `php ./run_all_tests.php --testNumber 525 -v`

### Harness notes

- The harness supports a few special `//...` comments inside `.in` fixtures (parsed via `token_get_all()` in `tests/run_all_tests.php`).

- `//passes:PassA,PassB,...`
  - Forces a specific list of passes for that test.
  - Example: `//passes:AutoSemicolon`
  - `Default` is a special value: `//passes:Default` enables a default set plus PSR decoration.
  - Pass parameters are supported via `PassName|argument`.

- `//excludes:PassA,PassB,...`
  - Disables specific passes for that test.

- `//version:X.Y.Z`
  - Skips the test when the running PHP version is **lower** than `X.Y.Z`.

- `//versionup:X.Y.Z`
  - Skips the test when the running PHP version is **higher** than `X.Y.Z`.

- `//skipShortTag`
  - If `short_open_tag` is disabled in the current runtime and the fixture contains `//skipShortTag` in inline HTML, the test is skipped.

- If there is **no** `//passes:...` or `//excludes:...`, the harness applies a baseline set and calls `PsrDecorator::decorate($fmt)`.

- Some tests may be skipped depending on PHP version or `short_open_tag` settings (see the logic in `tests/run_all_tests.php`).

### 2) PHPUnit

- Run from the repository root:
  - `./vendor/bin/phpunit`

- Under PHP 7.4 (if available):
  - `php74 ./vendor/bin/phpunit`

Notes:
- The **fixture harness** (`tests/run_all_tests.php`) is the primary “hard gate” for formatter behavior and should stay at `Broken:0`.
- Under older runtimes (e.g. PHP 7.4), PHPUnit may report many **Skipped** tests due to runtime/dependency constraints; this is expected.
- Prefer running PHPUnit with the default `php` (newer runtime) for full coverage, and use `php74 tests/run_all_tests.php` as the compatibility proxy.

## Creating a new fixture test (`.in`/`.out`)

1) Pick a new number

- In `tests/Original/` or `tests/PSR/`, use the next available number to avoid collisions.
  - Example: if the current max is `524`, create `525-some-case.in` and `525-some-case.out`.

2) Write the `.in`

- The `.in` should reproduce the formatting case.
- Optional: specify passes explicitly:
  - `//passes:AutoSemicolon,ResizeSpaces` (comma-separated)

3) Produce the `.out`

- Run the harness in verbose mode to see the diff and adjust the expected output:
  - `php tests/run_all_tests.php --testNumber 525 -v`
- Edit the `.out` until the test passes.

4) Validate

- Run the specific fixture test:
  - `php tests/run_all_tests.php --testNumber 525`
- Run the full fixture suite and PHPUnit before finishing a change:
  - `php tests/run_all_tests.php`
  - `./vendor/bin/phpunit`

- Also run the fixture suite with PHP 7.4 when possible (as a proxy for older runtimes):
  - `php74 tests/run_all_tests.php`

## Debugging tips

- To see which passes execute, use `FMTDEBUG` (see conditions in `fmt.stub.php`).

  - Example (prints the formatter output after each executed pass):
    - `export FMTDEBUG=1; php tests/run_all_tests.php --testNumber 525 -v`

  - Step mode (waits for Enter between passes):
    - `export FMTDEBUG=step; php tests/run_all_tests.php --testNumber 525 -v`

  - Disable afterwards:
    - `unset FMTDEBUG`

  Notes:
  - This mode is intentionally noisy; use it with a single test number.
  - `FMTDEBUG` changes the `CodeFormatter` subclass used at runtime to print intermediate states after each pass.
- If a change touches whitespace or special token sequences (e.g., heredoc/nowdoc), review passes like `ResizeSpaces` and any code that uses `printUntil(...)` or otherwise skips ranges of tokens.
