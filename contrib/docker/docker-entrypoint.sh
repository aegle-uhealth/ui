#!/usr/bin/env bash
set -euo pipefail

cd /app
composer install --no-interaction

exec "$@"
