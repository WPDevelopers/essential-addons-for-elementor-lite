#!/usr/bin/env bash
# Verify the release version is consistent across every source of truth and that
# the changelog documents it. Run locally before tagging, and in CI on release.
#
#   bin/check-version.sh            # consistency of the three version strings + changelog
#   bin/check-version.sh 6.6.12     # also assert they all equal this (e.g. the git tag)
set -euo pipefail

cd "$(dirname "$0")/.."

# Portable extraction (works with both BSD and GNU sed — no \s, always -E).
header=$(sed -nE 's/^[[:space:]]*\*[[:space:]]*Version:[[:space:]]*([0-9.]+).*/\1/p' essential_adons_elementor.php | head -1)
const=$(sed -nE "s/.*'EAEL_PLUGIN_VERSION'[[:space:]]*,[[:space:]]*'([0-9.]+)'.*/\1/p" essential_adons_elementor.php | head -1)
stable=$(sed -nE 's/^Stable tag:[[:space:]]*([0-9.]+).*/\1/p' readme.txt | head -1)

echo "Plugin header : $header"
echo "PHP constant  : $const"
echo "readme Stable : $stable"

fail=0
if [ "$header" != "$const" ] || [ "$header" != "$stable" ]; then
	echo "::error::Version mismatch — header ($header), constant ($const), Stable tag ($stable) must all match." >&2
	fail=1
fi

# Changelog must document the shipped version (readme uses '= X.Y.Z - date =').
if ! grep -qE "^= ${header//./\\.}( |$|\s)" readme.txt; then
	echo "::error::No changelog entry for $header in readme.txt (expected a '= $header - <date> =' heading)." >&2
	fail=1
fi

# When a version is passed (the tag), everything must equal it.
if [ "${1:-}" != "" ]; then
	want="${1#v}"
	echo "Expected      : $want"
	if [ "$header" != "$want" ]; then
		echo "::error::Tag $1 does not match plugin version $header." >&2
		fail=1
	fi
fi

if [ "$fail" -ne 0 ]; then
	exit 1
fi
echo "Version consistency OK."
