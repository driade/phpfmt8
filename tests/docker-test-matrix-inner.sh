#!/bin/sh
set -eu

mkdir -p /work
# Copy repo into container FS (avoid modifying host); exclude vendor to keep it small.
tar -C /src --exclude=vendor --exclude=.git -cf - . | tar -C /work -xf -
cd /work

echo '--- php -v ---'
php -v

echo '--- run_all_tests.php (tests/run_all_tests.php) ---'
php tests/run_all_tests.php

echo '--- run_all_tests.php exit:' $?

php_mm="$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')"
echo "--- PHP detected: ${php_mm} ---"

php_major="$(php -r 'echo PHP_MAJOR_VERSION;')"
php_minor="$(php -r 'echo PHP_MINOR_VERSION;')"

if [ "$php_major" -eq 5 ] && [ "$php_minor" -eq 6 ]; then
  echo '--- phpunit (PHP 5.6, phpunit 5.7 phar) ---'

  # Avoid Composer + system package installs on EOL Debian images.
  php -r "copy('https://phar.phpunit.de/phpunit-5.7.phar', '/tmp/phpunit.phar');" || {
    echo 'ERROR: failed to download phpunit-5.7.phar' >&2
    exit 1
  }

  # Minimal phpunit.xml compatible with PHPUnit 5.x.
  cat > /tmp/phpunit.xml <<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<phpunit colors="false" stopOnFailure="true">
  <testsuites>
    <testsuite name="Unit">
      <directory suffix="Test.php">/work/tests/Unit</directory>
    </testsuite>
  </testsuites>
</phpunit>
XML

  php /tmp/phpunit.phar --version
  php /tmp/phpunit.phar --configuration /tmp/phpunit.xml
  exit 0
fi

if [ "${RUN_PHPUNIT:-1}" != "1" ]; then
  echo '--- phpunit skipped (RUN_PHPUNIT!=1) ---'
  exit 0
fi

# Helper: install composer.phar using PHP itself (no curl/wget requirement).
install_composer() {
  url="$1"
  php -r "copy('${url}', '/tmp/composer.phar');" || return 1
  php /tmp/composer.phar --version >/dev/null 2>&1 || return 1
}

# Best-effort: ensure unzip/git exist for composer installs (may fail on very old images).
ensure_tools() {
  if command -v unzip >/dev/null 2>&1 && command -v git >/dev/null 2>&1; then
    return 0
  fi

  echo '--- installing required tools (git, unzip) ---'

  if command -v apk >/dev/null 2>&1; then
    apk add --no-cache git unzip ca-certificates
  elif command -v apt-get >/dev/null 2>&1; then
    export DEBIAN_FRONTEND=noninteractive

    apt_update() {
      apt-get -o Acquire::Check-Valid-Until=false update
    }

    fix_debian_eol_sources() {
      # Old PHP images (php:5.6/7.0/7.1/7.2) are often based on EOL Debian (e.g. stretch).
      # Switch to archive mirrors and disable Valid-Until checks.
      if [ -d /etc/apt ]; then
        printf '%s\n' \
          'Acquire::Check-Valid-Until "false";' \
          'Acquire::AllowInsecureRepositories "true";' \
          'Acquire::AllowDowngradeToInsecureRepositories "true";' \
          > /etc/apt/apt.conf.d/99phpfmt-eol 2>/dev/null || true

        for f in /etc/apt/sources.list /etc/apt/sources.list.d/*.list; do
          [ -f "$f" ] || continue
          sed -i \
            -e 's|http://deb.debian.org/debian|http://archive.debian.org/debian|g' \
            -e 's|http://security.debian.org/debian-security|http://archive.debian.org/debian-security|g' \
            -e 's|http://security.debian.org|http://archive.debian.org/debian-security|g' \
            "$f" 2>/dev/null || true

          # archive.debian.org does not provide *-updates suites consistently; disable them.
          sed -i \
            -e '/-updates/ s/^deb /# deb /' \
            -e '/-updates/ s/^deb-src /# deb-src /' \
            "$f" 2>/dev/null || true
        done
      fi
    }

    if ! apt_update; then
      echo '--- apt-get update failed; trying archive.debian.org (EOL Debian) ---'
      fix_debian_eol_sources
      apt_update
    fi

    apt-get install -y --no-install-recommends git unzip ca-certificates
  elif command -v yum >/dev/null 2>&1; then
    yum install -y git unzip ca-certificates
  else
    echo 'ERROR: no supported package manager found to install git/unzip (need git or unzip for Composer).' >&2
    exit 2
  fi

  if ! command -v unzip >/dev/null 2>&1 && ! command -v git >/dev/null 2>&1; then
    echo 'ERROR: failed to install git/unzip; Composer will not be able to download dependencies.' >&2
    exit 2
  fi
}

if [ "$php_major" -gt 7 ] || { [ "$php_major" -eq 7 ] && [ "$php_minor" -ge 3 ]; }; then
  echo '--- phpunit (root composer.json, phpunit ^9.6) ---'
  ensure_tools
  install_composer 'https://getcomposer.org/download/2.2.23/composer.phar'
  rm -f composer.*
  php /tmp/composer.phar require phpunit/phpunit
  ./vendor/bin/phpunit
  exit 0
fi

if [ "$php_major" -eq 7 ] && [ "$php_minor" -ge 0 ] && [ "$php_minor" -le 2 ]; then
  if [ "${RUN_PHPUNIT_LEGACY:-0}" = "1" ]; then
    echo '--- phpunit (tests/composer.json, phpunit ^6.5) ---'
    ensure_tools
    install_composer 'https://getcomposer.org/download/1.10.26/composer.phar'
    (cd tests && rm -f composer.* && php /tmp/composer.phar require phpunit/phpunit && ./vendor/bin/phpunit) || {
      if [ "${STRICT_PHPUNIT:-0}" = "1" ]; then
        echo 'ERROR: legacy phpunit failed' >&2
        exit 1
      fi
      echo 'WARN: legacy phpunit failed; continuing (set STRICT_PHPUNIT=1 to fail)' >&2
    }
  else
    echo '--- phpunit skipped on PHP 7.0-7.2 (set RUN_PHPUNIT_LEGACY=1 to try) ---'
  fi
  exit 0
fi

echo '--- phpunit skipped on this PHP version ---'
