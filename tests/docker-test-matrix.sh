#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

# Usage examples:
#   PHP_VERSIONS="5.6 7.4 8.2" ./tests/docker-test-matrix.sh
#   DOCKER_PLATFORM=linux/amd64 ./tests/docker-test-matrix.sh

PHP_VERSIONS=${PHP_VERSIONS:-"5.6 7.0 7.1 7.2 7.3 7.4 8.0 8.1 8.2 8.3 8.4 8.5"}

# On macOS/Apple Silicon, old PHP images are often amd64-only.
DOCKER_PLATFORM=${DOCKER_PLATFORM:-"linux/amd64"}

# Run phpunit on supported versions (>=7.3) by default.
RUN_PHPUNIT=${RUN_PHPUNIT:-"1"}
# Opt-in: try running phpunit on PHP 7.0-7.2 via tests/composer.json + Composer 1.
RUN_PHPUNIT_LEGACY=${RUN_PHPUNIT_LEGACY:-"0"}
# If set to 1, phpunit failures will fail the whole script.
STRICT_PHPUNIT=${STRICT_PHPUNIT:-"0"}

if ! command -v docker >/dev/null 2>&1; then
  echo "ERROR: docker not found in PATH" >&2
  exit 2
fi

run_one() {
  local ver="$1"
  local image="php:${ver}-cli"

  echo "" 
  echo "=== PHP ${ver} (${image}) ==="

  docker run --rm \
    --platform "${DOCKER_PLATFORM}" \
    -v "${ROOT}:/src:ro" \
    -e RUN_PHPUNIT="${RUN_PHPUNIT}" \
    -e RUN_PHPUNIT_LEGACY="${RUN_PHPUNIT_LEGACY}" \
    -e STRICT_PHPUNIT="${STRICT_PHPUNIT}" \
    "${image}" sh /src/tests/docker-test-matrix-inner.sh
}

for ver in ${PHP_VERSIONS}; do
  if ! run_one "${ver}"; then
    echo "FAIL: PHP ${ver} (stopping)" >&2
    exit 1
  fi
  echo "OK: PHP ${ver}"
done

exit 0
