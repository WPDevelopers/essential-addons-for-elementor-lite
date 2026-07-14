#!/usr/bin/env sh
# Lint staged PHP files: auto-fix with phpcbf, then gate with phpcs.
# Invoked by lint-staged (pre-commit) with the staged file paths as args.
#
# phpcbf exits 1 when it successfully fixes something, which lint-staged would
# treat as a failure. We swallow that and let phpcs be the real gate: the commit
# is blocked only when unfixable violations remain.
set -e

STD="--standard=phpcs.xml.dist"
PHPCBF="tools/vendor/bin/phpcbf"
PHPCS="tools/vendor/bin/phpcs"

if [ ! -x "$PHPCBF" ]; then
	echo "phpcs tools not installed — run 'composer run tools:install'." >&2
	exit 1
fi

# Auto-fix (exit 1 == fixed something, not an error for us).
"$PHPCBF" $STD "$@" || true

# Gate: non-zero here means unfixable violations remain.
"$PHPCS" $STD "$@"
