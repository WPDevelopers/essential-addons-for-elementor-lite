# Code Snippet Widget

> Syntax-highlighted code block rendered with Highlight.js and a macOS-style "window" header (traffic-light circles, filename, language icon, copy button). Supports 27 languages, light / dark themes, line numbers, three view modes (default / fixed / collapsed), and a public JS API on `window.EaelCodeSnippet` for re-init and language-swap.

**Class file:** [`includes/Elements/Code_Snippet.php`](../../includes/Elements/Code_Snippet.php)
**Slug:** `code-snippet` (widget id `eael-code-snippet`)
**Public docs:** <https://essential-addons.com/elementor/docs/ea-code-snippet/>
**Pro-shared:** ❌ — Lite-only widget. No `eael_section_pro` upsell panel, no `pro_enabled` gate, no `do_action` or `apply_filters` calls of any kind. Pro neither subclasses nor references this widget. **Second widget after Feature_List with zero Pro extension surface.**

---

## Overview

Code Snippet renders a single code block with macOS-Terminal-styled chrome — three traffic-light circles, a language-specific emoji icon (or custom), the filename, and a copy-to-clipboard button with optional tooltip. Highlight.js v11 with GitHub Light / GitHub Dark themes drives the syntax colouring; the widget supports 27 languages out of the box with a Lite-side aliasing table (e.g. `jsx` → `javascript`, `html` → `xml`).

The widget is heavy on JavaScript — `code-snippet.js` (~570 lines) handles Highlight.js library polling, copy-to-clipboard with Clipboard API + `execCommand` fallback, tooltip positioning, a MutationObserver for dynamically-loaded snippets, and a collapsed / expanded toggle. A public API on `window.EaelCodeSnippet` exposes `init`, `reinit`, `updateSnippetLanguage`, and other methods for theme / plugin integration. The widget also dispatches a custom DOM event `eael-code-copied` on successful copy.

## Features

- Syntax highlighting via Highlight.js v11 for 27 languages — HTML, CSS, SCSS, PHP, Python, JavaScript, JSX, Vue, TypeScript, SQL, JSON, XML, Java, Ruby, Bash, YAML, C++, C#, Go, Rust, Swift, Kotlin, Markdown, Shell, PowerShell, Docker
- Lite-side language aliasing for Highlight.js compatibility (`html` → `xml`, `jsx` → `javascript`, `cs` → `csharp`, etc.)
- Two themes: light (GitHub theme) and dark (GitHub Dark theme)
- macOS-style header chrome: three traffic-light circles, language emoji icon, filename, copy button
- Custom filename with automatic extension append based on language (e.g. `hero-section` + language `tsx` → `hero-section.tsx`)
- Default language emoji icons (🌐 HTML, 🐍 Python, 🐘 PHP, 🦀 Rust, etc.) overridable with custom image or icon picker
- Copy-to-clipboard via modern Clipboard API with `document.execCommand('copy')` fallback for older browsers
- Optional tooltip on the copy button (shows "Copy to clipboard" / "Copied!" / "Copy Failed")
- Custom DOM event `eael-code-copied` dispatched on successful copy
- Three view modes: default (no height limit), fixed (configurable height), collapsed (expandable indicator)
- Configurable collapse indicator — text or icon, full-width or button, with separate collapsed / expanded states
- Optional line numbers (computed server-side, rendered as `<div class="line-number">` per line)
- Configurable height (responsive, px / em / rem)
- Wrapper, header, content, and line-numbers all have dedicated Style sections
- Public JS API on `window.EaelCodeSnippet` for programmatic re-init and language swap

## Pro vs Lite

| Capability | Lite | Pro |
| ---------- | ---- | --- |
| All 27 languages | ✅ | ✅ |
| Light + Dark themes | ✅ | ✅ |
| Copy-to-clipboard with fallback | ✅ | ✅ |
| Three view modes | ✅ | ✅ |
| Public JS API (`window.EaelCodeSnippet`) | ✅ | ✅ |
| Pro-specific features for this widget | — | — |
| `eael_section_pro` upsell panel | ❌ — none present | — |
| Filter or action hooks for Pro extension | ❌ — none emitted | — |

The widget ships zero Pro extension surface — no upsell panel, no `pro_enabled` gate, no `do_action` injection points. Pro does not reference Code_Snippet anywhere. Whatever ships in Lite is the entire widget.

## Use Cases

- Documentation page showing API examples
- Tutorial blog post with code-along snippets
- Plugin / theme docs hosting custom-CSS examples
- Comparison page where two snippets sit side-by-side (different languages, same logic)
- Cheat-sheet page with collapsed snippets that expand on demand
- Code-heavy landing page where developers expect a polished terminal-like presentation

---

## File Map

| File | Role |
| ---- | ---- |
| [`includes/Elements/Code_Snippet.php`](../../includes/Elements/Code_Snippet.php) | PHP widget class — controls, render, `get_file_icon_by_language()` helper |
| [`src/css/view/code-snippet.scss`](../../src/css/view/code-snippet.scss) | Source styles — wrapper, theme variants, traffic lights, copy button, view modes |
| [`src/js/view/code-snippet.js`](../../src/js/view/code-snippet.js) | Frontend logic — Highlight.js init, copy logic, tooltip positioning, MutationObserver, public API |
| [`config.php`](../../config.php#L703) entry `'code-snippet'` | `Asset_Builder` dependency declaration (CSS + JS + Highlight.js + themes) |
| `assets/front-end/css/lib-view/code-snippet/github.min.css` | Vendor — Highlight.js GitHub light theme |
| `assets/front-end/css/lib-view/code-snippet/github-dark.min.css` | Vendor — Highlight.js GitHub dark theme |
| `assets/front-end/js/lib-view/code-snippet/highlight.min.js` | Vendor — Highlight.js v11 core (single bundle with all 27 languages registered) |
| `assets/front-end/css/view/code-snippet.min.css` | Built output (do not edit) |
| `assets/front-end/js/view/code-snippet.min.js` | Built output (do not edit) |

## Architecture

- **Zero Pro extension surface** — no `eael_section_pro` upsell, no `pro_enabled` check, no `do_action()` / `apply_filters()` of any kind. Same pattern as Feature_List. Whatever ships in Lite is the entire widget. Customisation is via CSS overrides or the public JS API.
- **Public JS API on `window.EaelCodeSnippet`** — exposed methods: `init`, `reinit`, `initCopyButton`, `applySyntaxHighlighting`, `applySyntaxHighlightingToBlock`, `updateSnippetLanguage`, `getHighlightLanguage`, `loadHighlightJs`. Themes and other plugins can call `EaelCodeSnippet.updateSnippetLanguage(snippet, 'rust')` to swap a snippet's language at runtime ([line 500-509](../../src/js/view/code-snippet.js#L500)).
- **Highlight.js loaded via WordPress dependency system, JS polls `window.hljs`** — `code-snippet.js` does not load Highlight.js itself; `Asset_Builder` registers it as a `lib`-type JS dependency in `config.php`. The JS polls `window.hljs` up to 10 times at 100ms intervals (1 second total) before giving up and logging a console warning ([line 84-103](../../src/js/view/code-snippet.js#L84)). This handles the case where Highlight.js is loaded async or out-of-order.
- **Lite-side language aliasing for Highlight.js compatibility** — the panel exposes user-friendly language ids (`jsx`, `vue`, `ts`, `cs`, `rs`, etc.) but Highlight.js v11 expects different ids (`javascript`, `typescript`, `csharp`, `rust`). The `languageMap` constant in JS ([line 21-37](../../src/js/view/code-snippet.js#L21)) translates EA's panel ids to Highlight.js ids before calling `hljs.highlightElement()`. Same mapping is referenced in the PHP for the file-icon emoji table.
- **MutationObserver for AJAX-loaded snippets** — `code-snippet.js` registers a global `MutationObserver` ([line 519-553](../../src/js/view/code-snippet.js#L519)) on `document.body` that watches for new `.eael-code-snippet-wrapper` elements being added. When found, debounces a 100ms timeout and calls `reinitialize()`. Handles the case where AJAX loads new widgets (popup, infinite scroll, etc.) without firing `elementor/frontend/element_ready`.
- **Auto-init on DOMContentLoaded plus `frontend/element_ready` registration** — the JS runs `initializeCodeSnippets()` both at DOM-ready (line 511-516) AND via Elementor's `frontend/element_ready/eael-code-snippet.default` action ([line 563-568](../../src/js/view/code-snippet.js#L563)). The auto-init ensures snippets in non-Elementor contexts also work; the action handler ensures Elementor's editor preview and SPA navigation also fire.
- **Modern Clipboard API with `execCommand` fallback** — `copyToClipboard()` ([line 190-207](../../src/js/view/code-snippet.js#L190)) checks `navigator.clipboard && window.isSecureContext` first. Falls back to a hidden `<textarea>` + `document.execCommand('copy')` for HTTP sites and older browsers. `isSecureContext` requirement means HTTP sites use the fallback even on modern browsers.
- **Custom DOM event `eael-code-copied`** — successful copies dispatch `new CustomEvent('eael-code-copied', { detail: { snippet, code, language } })` on `document` ([line 440-447](../../src/js/view/code-snippet.js#L440)). Themes can listen for it to track usage analytics, show a custom notification, or trigger a follow-up action.
- **Server-side line numbers** — when `show_line_numbers === 'yes'`, `render()` splits the code by `\n`, counts lines, and emits one `<div class="line-number">` per line in a separate `<div class="eael-code-snippet-line-numbers">`. CSS positions these next to the code via flexbox. The numbers are NOT regenerated client-side, so editing the code via the JS API doesn't update the numbers — a minor inconsistency.
- **Default file icon as emoji** — `get_file_icon_by_language()` ([line 1057-1086](../../includes/Elements/Code_Snippet.php#L1057)) returns a hardcoded emoji per language (🌐 HTML, 🐘 PHP, 🦀 Rust, 🐳 Dockerfile, etc.). Users can override with a custom image (MEDIA control) or custom icon (ICONS control) per widget. Default emojis are unicode characters in `<span>`, not images.

## Render Output

```html
<div id="eael-code-snippet-<widget-id>"
     class="eael-code-snippet-wrapper theme-light view-mode-default"
     data-language="javascript"
     data-copy-button="1"
     data-snippet-id="eael-code-snippet-<widget-id>">
  [?] <div class="eael-code-snippet-header eael-file-preview-header">
        <div class="eael-file-preview-left">
          [?] <div class="eael-traffic-lights">
                <span class="traffic-light traffic-light-red"></span>
                <span class="traffic-light traffic-light-yellow"></span>
                <span class="traffic-light traffic-light-green"></span>
              </div>
          <div class="eael-file-info">
            [?] <div class="eael-file-icon">
                  [?] <!-- custom uploaded image, or custom icon, or default emoji -->
                  <span class="eael-file-icon-emoji">🌐</span>
                </div>
            [?] <div class="eael-file-name">
                  <span class="file-name-text">hero-section.tsx</span>
                </div>
          </div>
        </div>
        [?] <div class="eael-file-preview-right">
              <div class="eael-code-snippet-copy-container">
                <button class="eael-code-snippet-copy-button"
                        data-clipboard-target="#<id> .eael-code-snippet-code code"
                        aria-label="Copy code to clipboard">
                  <svg>…copy icon…</svg>
                </button>
                [?] <div class="eael-code-snippet-tooltip">Copy to clipboard</div>
              </div>
            </div>
      </div>

  <div class="eael-code-snippet-content">
    [?] <div class="eael-code-snippet-line-numbers" aria-hidden="true">
          <div class="line-number">1</div>
          <div class="line-number">2</div>
          …
        </div>
    <pre class="eael-code-snippet-code language-javascript"><code>// Paste or type your code here…</code></pre>

    [?] <div class="eael-code-snippet-collapsed-indicator-wrapper">
          <div class="eael-code-snippet-collapsed-indicator
                      eael-cs-indicator-type-full_width eael-cs-code-collapsed">
            <span class="eael-code-snippet-collapsed-indicator-text eael-csi-collapsed">Show more</span>
            <span class="eael-code-snippet-collapsed-indicator-text eael-csi-expanded">Show less</span>
          </div>
        </div>
  </div>
</div>
```

Notes:

- Root class composition: `eael-code-snippet-wrapper` always; `theme-{light|dark}` for theme; `view-mode-{default|fixed|collapsed}` for view mode.
- `data-language` on the root carries the panel-selected language id (not the Highlight.js id — JS translates via `languageMap`).
- `<pre class="eael-code-snippet-code language-<id>">` carries the original (panel) language id. JS rewrites it to `language-<mapped-id>` before calling `hljs.highlightElement()`.
- After Highlight.js runs, `<code>` gets `class="hljs language-<mapped-id>"` and a `data-highlighted` attribute, and the inner HTML is replaced with `<span>` tokens for syntax colouring.
- The `data-clipboard-target` attribute on the copy button references the code element by widget id; the JS uses a different lookup path (`snippet.querySelector('.eael-code-snippet-code code')` first), so the attribute is informational not load-bearing.
- The collapsed indicator emits both "Show more" and "Show less" texts (or icons); CSS hides one based on `.eael-csi-collapsed` / `.eael-csi-expanded` classes — JS toggles `view-mode-expanded` / `view-mode-collapsed` on the wrapper, which CSS uses to swap visibility.
- File name automatically appends the language extension if no `.` is present (`hero-section` + `tsx` → `hero-section.tsx`).
- Code content is `esc_html()`-escaped before insertion — safe from XSS even though `<code>` content is later replaced by Highlight.js spans.

## Controls Reference

Source [`register_controls()`](../../includes/Elements/Code_Snippet.php#L51) is the truth — this table orients without enumerating every property.

| ID | Type | Default | Tab → Section | Affects |
| --- | ---- | ------- | ------------- | ------- |
| `language` | SELECT2 | `html` | Content → Code Settings | `data-language`, `.language-<id>` class on `<pre>`, file icon emoji default |
| `code_content` | CODE | `"// Paste or type…"` | Content → Code Settings | `<code>` inner text (escaped) |
| `theme` | CHOOSE | `light` | Content → Appearance | Root class `theme-{light|dark}` |
| `show_header` | SWITCHER | `yes` | Content → Appearance | Renders the header bar |
| `show_copy_button` | SWITCHER | `yes` | Content → Appearance | Renders the copy button (conditional on header) |
| `show_copy_tooltip` | SWITCHER | `no` | Content → Appearance | Renders the tooltip on copy button |
| `show_line_numbers` | SWITCHER | `no` | Content → Appearance | Renders `.eael-code-snippet-line-numbers` |
| `code_view_mode` | CHOOSE | `default` | Content → Appearance | Root class `view-mode-{default|fixed|collapsed}` |
| `code_snippet_height` | SLIDER (responsive) | `300px` | Content → Appearance | `height` on `.eael-code-snippet-content` (conditional on non-default view mode) |
| `code_collapse_inidicator_type` | CHOOSE | `full_width` | Content → Appearance | `.eael-cs-indicator-type-{full_width|button}` (collapsed mode) |
| `code_collapse_inidicator_position` | CHOOSE | empty | Content → Appearance | `justify-content` on the indicator wrapper |
| `code_collapse_inidicator_content_type` | CHOOSE | `text` | Content → Appearance | Renders text spans or icon (collapsed mode) |
| `code_collapse_inidicator_text_collapsed` / `_expanded` | TEXT | `"Show more"` / `"Show less"` | Content → Appearance | Indicator label content (text mode) |
| `code_collapse_inidicator_icon_collapsed` / `_expanded` | ICONS | `fa-angle-down` / (similar) | Content → Appearance | Indicator icon (icon mode) |
| `file_name` | TEXT (dynamic) | `"filename"` | Content → Header | `.file-name-text`; language extension auto-appended if no `.` present |
| `show_traffic_lights` | SWITCHER | `yes` | Content → Header | Renders the macOS traffic-light circles |
| `show_file_icon` | SWITCHER | `yes` | Content → Header | Renders the language icon (or custom override) |
| `file_icon_type` | CHOOSE | `image` | Content → Header | Selects between image and icon picker |
| `file_icon` | MEDIA | empty | Content → Header | Custom image (image type) |
| `file_icon_custom` | ICONS | empty | Content → Header | Custom icon glyph (icon type) |

Plus Style sections for Wrapper (border, padding, margin, radius, shadow), Header (background, text colour, padding, border), Line Numbers (colour, width, separator), Code Content Area (background, padding, font, syntax-token overrides), Collapse Indicator (text / icon styling, padding, border, background — collapsed mode only).

## Conditional Dependencies

```text
# Content → Appearance
show_copy_button                 → visible when show_header == 'yes'
show_copy_tooltip                → visible when show_header == 'yes' AND show_copy_button == 'yes'
code_snippet_height              → visible when code_view_mode != 'default'

# Collapsed view-mode controls
code_collapse_inidicator_type / _position / _content_type / _text_collapsed / _text_expanded /
_icon_collapsed / _icon_expanded
                                 → visible when code_view_mode == 'collapsed'
code_collapse_inidicator_text_*  → visible when ... AND code_collapse_inidicator_content_type == 'text'
code_collapse_inidicator_icon_*  → visible when ... AND code_collapse_inidicator_content_type == 'icon'

# Content → Header (entire section)
file_preview_section             → visible when show_header == 'yes'
file_icon_type / file_icon / file_icon_custom
                                 → visible when show_file_icon == 'yes'
file_icon                        → also visible when file_icon_type == 'image'
file_icon_custom                 → also visible when file_icon_type == 'icon'
```

⚠️ Several control ids have a typo — `code_collapse_inidicator_*` (sic; should be `_indicator_`). Renaming would break saved widget data. Legacy.

## Behavior Flow

1. User drops the widget → `register_controls()` runs. No filter / action hooks are emitted.
2. User pastes code, picks a language, optionally configures theme, header, line numbers, view mode.
3. Editor preview re-renders via [`render()`](../../includes/Elements/Code_Snippet.php#L1090).
4. `render()` builds the root attribute list (class `theme-<theme> view-mode-<mode>`, `data-language`, `data-snippet-id`), then emits the header (if enabled), traffic lights, file icon (custom image / icon / language emoji), filename, copy button + tooltip.
5. Inside `.eael-code-snippet-content`: line numbers (if enabled) computed server-side from `explode("\n", $code)`; then `<pre class="eael-code-snippet-code language-<id>"><code>{esc_html(code)}</code></pre>`.
6. If view mode is collapsed: emit the collapsed-indicator wrapper with both collapsed-state and expanded-state texts / icons.
7. Browser receives static HTML. Elementor's `frontend/init` event fires.
8. `code-snippet.js` runs: registers `frontend/element_ready/eael-code-snippet.default` handler AND auto-initialises on DOMContentLoaded.
9. The handler runs `initializeCodeSnippets()` which:
   1. Queries all `.eael-code-snippet-wrapper` on the page.
   2. Polls for `window.hljs` (up to 1 second) — once available, calls `applySyntaxHighlighting()` which iterates all `:not(.hljs)` code blocks and runs `hljs.highlightElement()` on each.
   3. For each snippet, calls `initCopyButton()` which binds a click handler, fixes the language map, attaches tooltip mouse/blur handlers, and dispatches the custom event on copy.
10. A global `MutationObserver` watches `document.body` for new `.eael-code-snippet-wrapper` elements (AJAX-loaded content) and triggers `reinitialize()` after 100ms debounce.
11. Collapsed view-mode handler in `code-snippet.js` (registered via `$scope.find().on('click', ...)` at the end of the function) toggles `view-mode-expanded` / `view-mode-collapsed` classes on the wrapper and `eael-cs-code-collapsed` / `eael-cs-code-expanded` on the indicator when clicked.

## JavaScript Lifecycle

- **Triggers (dual):**
  - `elementorFrontend.hooks.addAction('frontend/element_ready/eael-code-snippet.default', CodeSnippet)` — fires for Elementor-rendered widgets
  - `document.addEventListener('DOMContentLoaded', initializeCodeSnippets)` (or immediate if document already loaded) — covers non-Elementor contexts
  - Global `MutationObserver` on `document.body` — picks up AJAX-loaded snippets after a 100ms debounce
- **Guard:** none — no `elementStatusCheck`; the JS relies on the `:not(.hljs)` selector for highlighter idempotence (Highlight.js adds `hljs` class to already-highlighted elements, so re-running is safe).
- **Reads on init:** all `.eael-code-snippet-wrapper` on the page; per snippet: `dataset.language`, `.eael-code-snippet-code code` (or fallback to `.eael-code-snippet-code` / `<code>` / `<pre>`).
- **Highlight.js polling:** `setInterval(check, 100)` × 10 attempts (1s total); aborts with console warning if `window.hljs` never appears.
- **Copy handler:** stored on the button as `copyButton._eaelClickHandler` so subsequent inits can `removeEventListener` before re-binding (idempotent on re-init).
- **Tooltip handler:** binds `mouseenter`, `mouseleave`, `blur` on copy button; `scroll` and `resize` on `window` for repositioning. Positions tooltip absolutely above the button with `getBoundingClientRect()`.
- **Custom event:** `eael-code-copied` dispatched on successful copy. Detail object: `{ snippet, code, language }`.
- **Public API:** `window.EaelCodeSnippet.{init, reinit, initCopyButton, applySyntaxHighlighting, applySyntaxHighlightingToBlock, updateSnippetLanguage, getHighlightLanguage, loadHighlightJs}`.
- **Runtime state:** module-level `highlightJsLoaded` and `highlightJsLoading` flags prevent duplicate library loads (defensive — Asset_Builder also de-dupes).
- **Collapse handler:** scoped to `$scope` via `$scope.find('.eael-code-snippet-collapsed-indicator').on('click', ...)`. Toggles `view-mode-expanded` / `view-mode-collapsed` classes; CSS swaps overflow / max-height.

## Asset Dependencies

`Asset_Builder` enqueues only when at least one Code Snippet widget is detected. See [`asset-loading.md`](../architecture/asset-loading.md) for detection caveats.

### CSS

| File / Handle | Source | Loaded |
| ------------- | ------ | ------ |
| `code-snippet.min.css` | self (built from `src/css/view/code-snippet.scss`) | Always when widget present |
| `github.min.css` | Vendor — Highlight.js GitHub light theme | Always when widget present |
| `github-dark.min.css` | Vendor — Highlight.js GitHub dark theme | Always when widget present |

Both themes load unconditionally even though only one is active per snippet. Trade-off: switching themes at runtime works without an extra HTTP request.

### JS

| File | Source | Purpose | Loaded |
| ---- | ------ | ------- | ------ |
| `highlight.min.js` | Vendor — Highlight.js v11 (bundled with all 27 languages) | Syntax highlighting engine | Always (load order before self) |
| `code-snippet.min.js` | self | Init, copy logic, tooltip, MutationObserver, public API | Always when widget present |

⚠️ Highlight.js v11 is bundled with all language modules — ~150 KB minified. Loading per-language would save bandwidth but require detecting which languages are used. Trade-off accepted historically.

## Hooks & Filters

N/A — the widget emits **no widget-specific filter or action hooks** and consumes no `eael/pro_enabled` gate. Extension is via CSS overrides or the public JS API (`window.EaelCodeSnippet`).

The JS-side custom event `eael-code-copied` (dispatched on `document`) is the only public hook for runtime integration — third-party JS can listen for it via `document.addEventListener('eael-code-copied', handler)`.

## Customization Recipes

### Recipe 1 — Track copy events for analytics

```js
document.addEventListener('eael-code-copied', function (event) {
    const { snippet, code, language } = event.detail;
    if (window.gtag) {
        gtag('event', 'code_copied', {
            'event_category': 'engagement',
            'event_label': language,
            'value': code.length
        });
    }
});
```

Fires once per successful copy. `detail.snippet` is the `.eael-code-snippet-wrapper` DOM element; `detail.code` is the copied string; `detail.language` is the EA panel language id (not the Highlight.js id).

### Recipe 2 — Override the GitHub theme with a custom syntax colouring

```scss
.eael-code-snippet-wrapper.theme-light pre code.hljs {
    background: #fdfdfd;
    color: #1a1a1a;
}
.eael-code-snippet-wrapper.theme-light .hljs-keyword { color: #af00db; }
.eael-code-snippet-wrapper.theme-light .hljs-string  { color: #008000; }
.eael-code-snippet-wrapper.theme-light .hljs-comment { color: #6a737d; font-style: italic; }
```

The Highlight.js token classes (`hljs-keyword`, `hljs-string`, etc.) are standardised; override them site-wide via theme CSS to deviate from GitHub colours without touching the widget controls.

### Recipe 3 — Swap a snippet's language at runtime

```js
const snippet = document.querySelector('#eael-code-snippet-XXXXX');
window.EaelCodeSnippet.updateSnippetLanguage(snippet, 'rust');
```

Useful for tabbed interfaces where the same code block needs to swap between languages. The method removes existing `hljs` class, sets the new `language-<id>` class, and re-runs Highlight.js. The EA `data-language` attribute is updated too.

### Recipe 4 — Add a custom language not in the picker (e.g. Elixir)

```js
// Wait for Highlight.js to load
window.EaelCodeSnippet.loadHighlightJs(function (loaded) {
    if (!loaded) return;
    // Register a custom Highlight.js language definition
    hljs.registerLanguage('elixir', function (hljs) {
        return {
            name: 'Elixir',
            keywords: 'def defp do end if else case cond when in fn',
            contains: [
                hljs.QUOTE_STRING_MODE,
                hljs.COMMENT('#', '$')
            ]
        };
    });
    // Find your snippet and apply
    const elixirSnippets = document.querySelectorAll('.eael-code-snippet-wrapper[data-language="elixir"]');
    elixirSnippets.forEach(function (snippet) {
        const codeBlock = snippet.querySelector('.eael-code-snippet-code');
        if (codeBlock) {
            codeBlock.className = codeBlock.className.replace(/\bhljs\b|\blanguage-\w+/g, '').trim() + ' language-elixir';
            codeBlock.removeAttribute('data-highlighted');
            hljs.highlightElement(codeBlock);
        }
    });
});
```

The widget's language picker doesn't expose Elixir but `data-language="elixir"` on the wrapper triggers the JS to attempt highlighting if a language definition is registered. Combine with a custom panel-extension snippet (see Image Accordion Recipe 1 for the `update_control` pattern) to add Elixir to the picker.

## Common Issues

### Syntax highlighting doesn't apply

- **Likely cause:** Highlight.js failed to load; or `window.hljs` is taking longer than 1 second to become available (the JS polls for up to 1s before giving up)
- **Diagnose:** browser console for `Essential Addons: Syntax highlighting unavailable` warning; Network tab for `highlight.min.js` 200
- **Fix:** ensure `Asset_Builder` is enqueueing the vendor JS; clear Elementor's CSS / JS cache; verify no other plugin is dequeuing `code-snippet`'s JS

### Wrong syntax colours for JSX / Vue / TypeScript

- **Likely cause:** the language alias table maps `jsx` → `javascript`, `vue` → `javascript`, `ts` → `typescript`. JSX-specific tokens (component names, props) aren't separately tokenised — they highlight as plain JavaScript
- **Diagnose:** by design; Highlight.js v11 has limited JSX support
- **Fix:** none; switch to a syntax token aware highlighter like Prism if needed (would require a fork)

### Copy button does nothing in Safari on HTTP

- **Likely cause:** the modern Clipboard API requires `window.isSecureContext === true`; on HTTP, the JS falls back to `document.execCommand('copy')`. Some Safari versions on HTTP block this too
- **Diagnose:** browser console for the warning; check if HTTPS is available
- **Fix:** serve the site over HTTPS; the modern Clipboard API works on `localhost` even on HTTP, but third-party HTTP sites fail silently

### Tooltip on copy button shows behind the code block

- **Likely cause:** the tooltip is absolute-positioned via `getBoundingClientRect()` but a parent has `overflow: hidden` or `transform: translateX(0)` creating a stacking context
- **Diagnose:** in DevTools inspect the tooltip — does it have `position: fixed` correctly applied? Inspect the parent chain for `overflow` and `transform`
- **Fix:** the tooltip is positioned absolutely with `style.left` / `style.top` — override the parent's clipping with `overflow: visible` or remove the `transform` ancestor

### Collapsed view-mode indicator doesn't toggle

- **Likely cause:** the click handler is registered on `$scope.find('.eael-code-snippet-collapsed-indicator')` inside `CodeSnippet($scope, $)`, which means it only binds inside Elementor's editor preview iframe and on Elementor-rendered pages. If the widget appears via AJAX in a non-Elementor context, the click handler may not fire
- **Diagnose:** in DevTools inspect — is there a click handler on `.eael-code-snippet-collapsed-indicator`?
- **Fix:** the MutationObserver triggers `reinitialize()` which calls `initializeCodeSnippets()` but does NOT re-register the collapse handler (it's scoped to `$scope`, not the global DOM). Override via theme JS: `document.addEventListener('click', function (e) { if (e.target.closest('.eael-code-snippet-collapsed-indicator')) { … }})`

### Line numbers stop matching the code

- **Likely cause:** line numbers are computed server-side from `explode("\n", $code)` at render time; if the JS API (`updateSnippetLanguage` etc.) replaces the `<code>` content client-side, the numbers don't update
- **Diagnose:** are you using the JS API to swap content?
- **Fix:** disable line numbers if using runtime content swap; or manually re-emit the line-number block via JS

### Custom file icon doesn't appear

- **Likely cause:** `show_file_icon` is on but the user picked `file_icon_type: image` and uploaded an attachment that has no public URL (e.g. private media)
- **Diagnose:** inspect `<div class="eael-file-icon">` — does it have any child?
- **Fix:** re-upload the image; or switch `file_icon_type` to `icon` and pick from Font Awesome

### Filename gets a duplicate extension

- **Likely cause:** the user typed `hero-section.tsx` AND `language` is `ts` (TypeScript); the auto-append logic checks `strpos($file_name, '.')` — if a dot is present, nothing is appended; if not, language extension is added
- **Diagnose:** check the filename field — does it have a `.`?
- **Fix:** type without extension (e.g. `hero-section`); the widget appends the right extension based on the language picker

### Highlight.js v11 doesn't highlight Dockerfile syntax

- **Likely cause:** the language map sends `dockerfile` straight to Highlight.js, which v11 supports natively, but the bundle in `assets/front-end/js/lib-view/code-snippet/highlight.min.js` may not include the Dockerfile module
- **Diagnose:** in DevTools run `hljs.listLanguages()` — does it include `dockerfile`?
- **Fix:** rebuild the Highlight.js bundle with the Dockerfile module; or use a different language like `bash` for Docker commands as a workaround

## Testing Checklist

- [ ] Drop at default — HTML code block with traffic lights, file icon (🌐 emoji), filename, copy button; no PHP notices
- [ ] Switch language through each of the 27 options — `data-language` and `.language-<id>` update; Highlight.js applies the right syntax colours
- [ ] Verify language alias map: pick `jsx`, `cs`, `rs`, `kt` — all highlight via the mapped Highlight.js id
- [ ] Switch theme light ↔ dark — root class `theme-light` / `theme-dark` updates; GitHub Light / Dark colours apply
- [ ] Hide header — header bar omitted; copy button, traffic lights, filename all gone
- [ ] Hide copy button — `.eael-file-preview-right` block omitted from header
- [ ] Click copy button — clipboard receives the code; copy icon animates to checkmark for 1s; `eael-code-copied` event dispatched (verify via `document.addEventListener` test)
- [ ] Click copy on HTTP (no `isSecureContext`) — fallback `execCommand('copy')` path runs; success / fail tooltip text updates
- [ ] Enable copy tooltip — hover the copy button; tooltip appears positioned above
- [ ] Enable line numbers — `<div class="eael-code-snippet-line-numbers">` rendered; line count matches `\n` splits
- [ ] Switch view mode to Fixed — `view-mode-fixed` class; height slider control becomes visible
- [ ] Switch view mode to Collapsed — `view-mode-collapsed` class; indicator block renders; click toggles expanded / collapsed
- [ ] Collapsed indicator type: full-width vs button — `.eael-cs-indicator-type-{full_width|button}` class updates
- [ ] Collapsed indicator content type: text vs icon — `.eael-csi-collapsed` / `.eael-csi-expanded` spans / icons render accordingly
- [ ] File name auto-append — type `hero-section` with language `tsx` → renders as `hero-section.tsx`
- [ ] File name with dot present — type `package.json` → renders verbatim (no double-append)
- [ ] Default emoji icon — verify each of the 27 emojis renders (🌐 HTML, 🐘 PHP, 🦀 Rust, 🐳 Dockerfile, etc.)
- [ ] Custom uploaded image icon — `<img>` renders in place of emoji
- [ ] Custom Font Awesome icon — FA glyph renders in place of emoji
- [ ] Special characters in code (`<script>`, `&`, `<`) — `esc_html()` escapes them in `<code>`; Highlight.js handles the escaped entities correctly
- [ ] Multiple snippets on same page — each gets unique `id="eael-code-snippet-<widget-id>"`
- [ ] AJAX-load a snippet after page load — MutationObserver picks it up; Highlight.js applies within 100ms debounce
- [ ] Public JS API — `window.EaelCodeSnippet.updateSnippetLanguage(snippet, 'rust')` switches a snippet's language at runtime
- [ ] After source changes, run `npm run build` and verify on `http://localhost:8888`

## Architecture Decisions

### Highlight.js v11 with all 27 languages bundled

- **Context:** the widget supports 27 languages; per-language Highlight.js modules would total ~50–80 KB combined, while the bundled `highlight.min.js` is ~150 KB.
- **Decision:** ship the bundle with all 27 languages registered upfront. Add a Lite-side alias table to translate EA panel ids to Highlight.js ids.
- **Alternatives rejected:** load per-language on-demand — would require detecting which language a snippet uses, adding latency; bundle a smaller subset (top 5 languages) — limits the picker.
- **Consequences:** every page with at least one Code Snippet loads ~150 KB of Highlight.js. Trade-off accepted; cacheable.

### Public JS API on `window.EaelCodeSnippet`

- **Context:** themes and plugins occasionally need to swap a snippet's language at runtime (e.g. tabbed language switchers), re-initialise after AJAX, or re-trigger highlighting.
- **Decision:** expose a small public API on `window.EaelCodeSnippet` with named methods.
- **Alternatives rejected:** require third parties to use jQuery selectors and re-invoke Highlight.js directly — burdens the integration; emit PHP-side hooks instead — doesn't help client-side use cases.
- **Consequences:** API surface to maintain; renaming any of the exposed methods is a public-contract change.

### Custom DOM event `eael-code-copied`

- **Context:** themes want to track copy events for analytics or trigger follow-up actions without forking the widget.
- **Decision:** dispatch `new CustomEvent('eael-code-copied', { detail: { snippet, code, language } })` on `document` after each successful copy.
- **Alternatives rejected:** PHP-side `do_action` hook — fires server-side, not at copy time; expose a setter on the public API for a callback — less idiomatic than DOM events.
- **Consequences:** themes integrate with one `document.addEventListener` call. The event name is part of the public contract.

### MutationObserver for AJAX-loaded snippets

- **Context:** widgets loaded via AJAX (popups, infinite-scroll posts, etc.) don't fire Elementor's `frontend/element_ready` action again.
- **Decision:** register a global `MutationObserver` on `document.body` that watches for new `.eael-code-snippet-wrapper` elements and re-invokes `initializeCodeSnippets()` after a 100ms debounce.
- **Alternatives rejected:** rely on third-party plugins to call `EaelCodeSnippet.reinit()` — requires opt-in by every plugin; poll `document.querySelectorAll(...)` periodically — wastes CPU.
- **Consequences:** one global observer per page (small overhead); handles all AJAX cases without coordination.

### Server-side line numbers

- **Context:** line numbers can be computed server-side once or client-side per render.
- **Decision:** count lines in PHP via `explode("\n", $code)` and emit one `<div>` per line.
- **Alternatives rejected:** CSS `counter` increment — pixel-perfect alignment is harder; client-side line counting — re-runs on every JS init.
- **Consequences:** line numbers are static and don't update if the code is modified at runtime (e.g. via the public JS API). Documented limitation.

## Known Limitations

- **Control id typo `code_collapse_inidicator_*`** (should be `_indicator_`) across the entire collapsed-mode control set. Renaming would break saved widget data; legacy.
- **Highlight.js v11 polling has a 1-second timeout** — if Highlight.js is delayed by a plugin or slow CDN beyond 1s, highlighting silently fails with a console warning. No automatic retry after 1s.
- **All 27 languages always loaded** — ~150 KB Highlight.js bundle even for sites with one snippet.
- **Both Light + Dark theme CSS always loaded** — switching themes at runtime is fast, but the wasted bandwidth on single-theme pages is real.
- **Line numbers don't update on JS-side content swap** — server-side computation; the public JS API doesn't re-render them.
- **Collapsed view-mode click handler is `$scope`-scoped** — AJAX-loaded snippets get their copy buttons re-initialised by MutationObserver but NOT their collapse handlers. Cleanup target.
- **JSX / Vue / TypeScript syntax limitations** — language aliasing maps these to `javascript` / `javascript` / `typescript`. Framework-specific tokens (props, directives, decorators) aren't independently tokenised.
- **No keyboard shortcut for copy** — only the copy button is bound; users can't `Ctrl+C` the code (without first selecting it manually).
- **`eael-code-copied` event detail.language is the EA panel id, not the Highlight.js id** — analytics handlers receiving `language === 'jsx'` should know it maps to `javascript` internally.
- **`data-clipboard-target` attribute on the copy button is informational** — the JS uses a different lookup path; if the attribute is missing or wrong, copy still works.
- **Default file icon is unicode emoji in a `<span>`** — older browsers / OS without emoji fonts may show squares. No graceful fallback besides users uploading a custom icon.
- **Filename auto-extension uses `strpos($file_name, '.')`** — if user types `hero.v2` (intending `v2` as suffix, not extension), the dot bypasses the auto-append.

## Recent Significant Changes

No significant documented changes yet. Future entries here only when public contract / control id / rendered class changes — not for every commit. Format: `version — description (#card)`.
