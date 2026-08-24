
# Stored XSS in Mega Menu editor preview via unescaped Label/Toggle Text (}})

Summary
Two plain-text Mega Menu controls are rendered with Underscore's unescaped triple-mustache ({{{ }}}) in the Elementor editor's Backbone preview template, instead of the escaped double-mustache ({{ }}). This allows a stored XSS payload saved in either field to execute as live JavaScript inside the Elementor editor (an authenticated wp-admin context) the next time anyone opens the widget for editing.

The frontend is not affected — it correctly escapes the same two values. Only the editor preview is exploitable.

Severity
High. Any user who can edit a page with this widget (e.g. an Author/Contributor with Elementor access in a multi-author site) can plant a payload that executes in a higher-privileged user's (Administrator/Editor) authenticated admin session the next time they open that widget — a real privilege-escalation path (nonce theft, arbitrary admin actions via the victim's session), not a cosmetic bug.

Affected code
includes/MegaMenu/Renderers/Editor_Renderer.php:

// Toggle text — unescaped
<span class="eael-mega-menu__toggle-text">{{{ toggleText }}}</span>

// Menu item label — unescaped
<span class="eael-mega-menu__item-label">{{{ item.eael_mega_menu_item_label }}}</span>
includes/MegaMenu/Controls/Content_Controls.php:198 — the repeater's own accordion-row title also uses the unescaped form:

'title_field' => '{{{ eael_mega_menu_item_label }}}',
Both source controls are declared as plain text — there is no legitimate reason for raw HTML output here:

// Content_Controls.php:47
$repeater->add_control( 'eael_mega_menu_item_label', [
    'label'   => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
    'type'    => Controls_Manager::TEXT,
    ...
    'dynamic' => [ 'active' => true ],
] );

// Content_Controls.php:377
$widget->add_control( 'eael_mega_menu_toggle_text', [
    'label'   => esc_html__( 'Toggle Text', 'essential-addons-for-elementor-lite' ),
    'type'    => Controls_Manager::TEXT,
    ...
    'dynamic' => [ 'active' => true ],
] );
Proof the frontend already knows better
The exact same two values are correctly sanitized on the public-facing render path, confirming this is an oversight specific to the editor template rather than a deliberate design choice:

includes/MegaMenu/Templates/menu-item.php:47 — echo wp_kses_post( $prepared['label'] );
includes/MegaMenu/Templates/mobile-toggle.php:42 — echo esc_html( $text );
Every other dynamic value in the same Backbone template (url, tag, itemId, itemClasses, position) already uses the escaped {{ }} form — these two (plus the title_field) are the only outliers.

Steps to reproduce
In Elementor, add a Mega Menu widget.
Set a menu item's Label (or the widget's Toggle Text) to <img src=x onerror=alert(document.cookie)>.
Save, then reopen the widget for editing (or have another user open it).
The payload executes immediately in the editor preview — confirmed via manual testing against the current theme-builder branch.
Suggested fix
Change the three unescaped references from triple- to double-mustache, matching every other field in the same file:

- <span class="eael-mega-menu__toggle-text">{{{ toggleText }}}</span>

+ <span class="eael-mega-menu__toggle-text">{{ toggleText }}</span>

- <span class="eael-mega-menu__item-label">{{{ item.eael_mega_menu_item_label }}}</span>

+ <span class="eael-mega-menu__item-label">{{ item.eael_mega_menu_item_label }}</span>

- 'title_field' => '{{{ eael_mega_menu_item_label }}}',

+ 'title_field' => '{{ eael_mega_menu_item_label }}',
  Related, lower-confidence item worth a look in the same pass
  Editor_Renderer.php also builds href="{{ url }}" from the Link control using only _.escape() (no protocol whitelist), so a javascript: URL would render live in the editor preview too. This mirrors a broader Elementor-core convention for URL fields in Backbone templates rather than being unique to this widget, so it's not filed as a separate blocking issue — but worth revisiting alongside the fix above.
