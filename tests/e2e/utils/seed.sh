#!/bin/bash
set -e

TEMPLATE_DIR="/var/www/html/wp-content/plugins/essential-addons-for-elementor-lite/tests/e2e/templates"

echo "==> Activating plugins..."
ELEMENTOR_SLUG=$(wp plugin list --field=name --allow-root | grep elementor | grep -v essential | head -1)
EAEL_SLUG=$(wp plugin list --field=name --allow-root | grep essential-addons | head -1)
[ -n "$ELEMENTOR_SLUG" ] && wp plugin activate "$ELEMENTOR_SLUG" --allow-root 2>/dev/null || true
[ -n "$EAEL_SLUG" ] && wp plugin activate "$EAEL_SLUG" --allow-root 2>/dev/null || true

echo "==> Setting permalink structure..."
wp rewrite structure '/%postname%/' --allow-root
wp rewrite flush --allow-root

# Create an Elementor page from a JSON template.
# Usage: create_elementor_page "Title" "slug" "template.json"
create_elementor_page() {
    local TITLE="$1" SLUG="$2" TEMPLATE="$3"

    EXISTING=$(wp post list --post_type=page --name="$SLUG" --field=ID --allow-root 2>/dev/null || true)
    [ -n "$EXISTING" ] && wp post delete $EXISTING --force --allow-root

    PAGE_ID=$(wp post create \
        --post_type=page \
        --post_title="$TITLE" \
        --post_status=publish \
        --post_name="$SLUG" \
        --porcelain \
        --allow-root)

    wp post meta update "$PAGE_ID" _elementor_edit_mode builder --allow-root
    wp post meta update "$PAGE_ID" _elementor_template_type wp-page --allow-root
    wp post meta update "$PAGE_ID" _elementor_version "3.25.0" --allow-root
    wp post meta update "$PAGE_ID" _elementor_data "$(cat "$TEMPLATE_DIR/$TEMPLATE")" --allow-root

    echo "    Created: /$SLUG/ (ID: $PAGE_ID)"
}

echo "==> Creating test pages..."
create_elementor_page "Info Box Test" "info-box-test" "info-box.json"

echo "==> Flushing Elementor CSS..."
wp elementor flush-css --allow-root 2>/dev/null || true

echo "==> Done. http://localhost:8888 | wp-admin: admin / password"
echo "    Pages: /info-box-test/"
