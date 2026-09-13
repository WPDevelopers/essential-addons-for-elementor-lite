
---
name: wp-free-pro-separation-audit
description: "Executable audit that takes a Free plugin name and a Pro plugin name, reads both codebases in full, and emits a complete pass/fail separation checklist. Use when asked to verify that Free code lives in Free and Pro code lives in Pro; to find Pro code stranded in the Free plugin that is dead or fatal without Pro; to find Free code duplicated into Pro that will drift; to verify the two plugins connect only through sanctioned WordPress extension points (filters, actions, class_exists/defined guards); or to pre-flight a freemium plugin pair against WordPress.org removal risk. Companion to plugin-audit-skill.md — that file holds the rules, this file is the procedure."
version: 1.0.0
requires: ./plugin-audit-skill.md
compatibility: "WordPress 5.0+ through 7.x, PHP 7.0+. Needs filesystem access to BOTH plugin directories and bash."
---
# Free ↔ Pro Separation Audit

**Input:** the Free plugin name/slug and the Pro plugin name/slug.
**Output:** one complete, itemised checklist — every item marked `PASS` / `FAIL` / `WARN` / `N/A`, each `FAIL` carrying `file:line` evidence and a fix.

> **Read [`plugin-audit-skill.md`](plugin-audit-skill.md) first.** That file is the rulebook — all 18 WordPress.org guidelines, the Plugin Check parity list, the security pass, the severity model. This file is the *procedure* for the one dimension that file only summarises in its Section 10: **where each line of code is allowed to live, and how the two plugins are allowed to talk.**
>
> Never emit this checklist without also having run the rulebook's Section 8 (security) — a perfectly separated plugin pair with an unauthenticated AJAX handler still gets pulled.

---

## 0. Why this audit exists

WordPress.org has removed large, long-established plugins from the directory with no prior notice and no appeal window. The removals that relate to codebase structure cluster into four causes, and all four are detectable statically:

1. **Guideline 5 (trialware)** — the Free plugin ships a feature that does not work until you pay. Stranded Pro code in Free is how this happens by accident: the control exists, the handler exists, the asset ships, but the class that renders it is in Pro.
2. **Guideline 8 (remote code)** — the Free plugin loads or executes code it did not ship. A Free plugin that `require`s a file out of the Pro directory is loading code WP.org never reviewed.
3. **Fatal errors on the directory build** — the reviewer installs *only* the Free plugin. Any unguarded reference to a Pro constant, class, or path is a white screen on a clean install. This is the single most common automated-rejection cause for a freemium pair.
4. **Guideline 2 (developer responsibility)** — a security fix landed in Free but not in the duplicated Pro copy. The Free source is public, so the fix is a published diff and the Pro copy is a roadmap.

The goal of this audit is a Free plugin that is **complete, self-contained, and correct with the Pro plugin absent**, and a Pro plugin that **adds to it only through hooks**.

---

## 1. The classification rule

This is the core of the audit. Every reference in Free that mentions Pro falls into exactly one of three buckets. Classify before you judge — the most common audit error is flagging bucket 1.

```text
For each line in FREE that references Pro (constant, class, path, slug, asset, string):

  Does this line do useful work when Pro is ABSENT?
  │
  ├─ YES → BUCKET 1 · UPSELL — permitted, audit placement only
  │        Examples: teaser controls, the promo widget catalog, "upgrade" links,
  │        a `! pro_enabled` guard that shows a free-tier alternative.
  │        Rule: must be guarded by `! pro_enabled` so Pro users never see it.
  │        Judged under plugin-audit-skill.md §4.1–4.2 (Guideline 5 / 11), not here.
  │
  ├─ NO, and it references a Pro symbol → BUCKET 2 · STRANDED — violation
  │        The code path is unreachable without Pro. It is dead weight in the
  │        directory build, it was never exercised by review, and it is the
  │        mechanism by which Free appears to offer a paid feature.
  │        Rule: MOVE IT TO PRO. Replace with an extension point.
  │
  └─ NO, and it would ERROR without Pro → BUCKET 3 · FATAL — blocker
           Unguarded `EAEL_PRO_*`, `new \...\Pro\...`, `require` of a Pro path.
           Rule: guard it AND move it. A guard alone leaves bucket-2 dead code.
```

**Bucket 2 is what the user usually means by "Pro code in Free".** Bucket 3 is what actually gets the plugin rejected. Report them separately — they have different fixes.

### The inverse rule, for Pro

```text
For each line in PRO:

  Does it re-implement, copy, or redefine something Free already provides?
  │
  ├─ YES → BUCKET 4 · DRIFT — violation
  │        Two copies of one function. One will get the security fix; one will not.
  │        Rule: delete the Pro copy, call Free's. If Free's is not callable,
  │        that is a Free bug: promote it to the public API (§4).
  │
  └─ NO → Does it reach into Free by any means other than a hook, a public
          method, or a documented class?
          │
          ├─ YES → BUCKET 5 · COUPLING — violation
          │        Editing Free's files, reading Free's private state, redeclaring
          │        Free's hooks, or depending on Free's internal file layout.
          │        Rule: Free must expose an extension point; Pro must use it.
          │
          └─ NO → COMPLIANT
```

---

## 2. Phase A — Resolve the pair

Run this first. Everything downstream needs these variables. Substitute the two names you were given.

```bash
# ── INPUT ─────────────────────────────────────────────────────────────
PLUGINS_DIR="$(pwd)"                       # wp-content/plugins
FREE="essential-addons-for-elementor-lite" # ← Free slug / directory
PRO="essential-addons-elementor"           # ← Pro slug / directory
# ──────────────────────────────────────────────────────────────────────

for d in "$FREE" "$PRO"; do
  [ -d "$PLUGINS_DIR/$d" ] || { echo "MISSING: $d"; exit 1; }
done

# Main files, headers, versions
for d in "$FREE" "$PRO"; do
  f=$(grep -rl "Plugin Name:" --include="*.php" "$PLUGINS_DIR/$d" --exclude-dir=node_modules --exclude-dir=vendor | head -1)
  echo "=== $d → $f"
  grep -E "Plugin Name:|Version:|Text Domain:|Requires PHP:|Requires at least:" "$f"
done

# Namespaces and autoloader prefixes — the separation backbone
for d in "$FREE" "$PRO"; do
  echo "=== $d namespace prefix"
  grep -n "prefix\s*=\|base_dir\s*=" "$PLUGINS_DIR/$d/autoload.php" 2>/dev/null
done

# Constants each side defines
for d in "$FREE" "$PRO"; do
  echo "=== $d constants"
  grep -rhoE "define\(\s*'[A-Z_]+'" "$PLUGINS_DIR/$d"/*.php | sed "s/define(\s*'//;s/'//" | sort -u
done
```

**Record into the report header:**

|                     | Free | Pro |
| ------------------- | ---- | --- |
| Slug / directory    |      |     |
| Version             |      |     |
| Text domain         |      |     |
| Namespace prefix    |      |     |
| Autoloader base dir |      |     |
| Constants defined   |      |     |
| PHP files           |      |     |

**A1–A6 checklist:**

- [ ] **A1** Free and Pro use **distinct namespace prefixes** (e.g. `Vendor\Plugin\` vs `Vendor\Plugin\Pro\`). Identical prefixes across two plugins is a class-collision blocker.
- [ ] **A2** Each autoloader is `file_exists()`-guarded before `require`.
  > *Prefix shadowing is expected and harmless when A2 holds:* Free's prefix `Vendor\Plugin\` is a string-prefix of Pro's `Vendor\Plugin\Pro\`, so Free's autoloader will be offered Pro's class names first. With the `file_exists()` guard it falls through silently. **Do not flag this** — verify the guard, note it INFO. Without the guard it is a fatal.
  >
- [ ] **A3** Free and Pro use **different text domains**, each exactly matching its own slug.
- [ ] **A4** Constants are prefixed distinctly (`EAEL_*` vs `EAEL_PRO_*`); neither side defines a constant the other also defines.
- [ ] **A5** `Requires PHP` and `Requires at least` are consistent, or Pro's are stricter — never looser.
- [ ] **A6** Pro's `Version` and Free's `Version` are tracked; note the skew for §5.

---

## 3. Phase B — Pro code stranded in Free

The main event. Free must be complete and correct with Pro deleted from disk.

### B1 · Unguarded Pro constants (BUCKET 3 — blocker)

```bash
cd "$PLUGINS_DIR/$FREE"
PROC="EAEL_PRO_"   # ← Pro constant prefix from Phase A

# Every use of a Pro constant in Free
grep -rn "$PROC" --include="*.php" includes/ *.php 2>/dev/null | grep -v node_modules

# Separate guarded from unguarded. Look 15 lines BACK (guard precedes use) and
# 3 lines FORWARD (the `foreach ( [...] as $c ) { if ( ! defined( $c ) )` shape,
# where the constant name appears as a string before the guard that tests it).
grep -rn "$PROC" --include="*.php" includes/ | grep -v "^\s*\*" | while IFS=: read -r file line rest; do
  start=$(( line > 15 ? line - 15 : 1 ))
  end=$(( line + 3 ))
  if ! sed -n "${start},${end}p" "$file" | grep -qE "defined\(\s*'?${PROC}|defined\(\s*\\\$|class_exists|pro_enabled"; then
    echo "UNGUARDED: $file:$line — $(echo "$rest" | sed 's/^[[:space:]]*//' | cut -c1-90)"
  fi
done
```

> **Calibration note.** The forward window matters. Without it this sweep mis-flags the
> indirect-guard idiom, where constant names are iterated as *strings* and `defined()` is
> called on the variable one line later:
>
> ```php
> foreach ( [ 'EAEL_PLUGIN_PATH', 'EAEL_PRO_PLUGIN_PATH' ] as $constant ) {
>     if ( ! defined( $constant ) ) { continue; }   // ← guard is AFTER the mention
> ```
>
> That shape is correct. Verify every hit by reading it; the sweep produces leads, not verdicts.

- [ ] **B1** Every Pro constant referenced in Free is preceded by `defined( 'PRO_CONST' )`, `class_exists()`, or a `pro_enabled` short-circuit **on the same execution path**.

> **Worked example — a real finding in this codebase.**
> `includes/Traits/Ajax_Handler.php:346` and `:1276`:
>
> ```php
> } else if ( $template_info['dir'] === 'pro' ) {
>     $dir_path = sprintf( "%sincludes", EAEL_PRO_PLUGIN_PATH );   // ← no defined() guard
> }
> ```
>
> `$template_info` derives from `$_REQUEST['template_info']`, so **an anonymous request can reach this branch on a Free-only install and trigger a fatal**. Compare `includes/Traits/Template_Query.php:112-116`, which does it correctly:
>
> ```php
> if ( ! apply_filters( 'eael/pro_enabled', false ) ) { return ...; }
> if ( ! defined( 'EAEL_PRO_PLUGIN_PATH' ) ) { return ...; }
> ```
>
> Two call sites, two different standards — that inconsistency is exactly what this check exists to surface. Fix: guard `Ajax_Handler.php` the same way, and reject the `'pro'` branch outright when Pro is inactive.

### B2 · Pro classes, traits, and namespaces referenced in Free

```bash
PRONS="Essential_Addons_Elementor\\\\Pro"   # ← Pro namespace from Phase A

grep -rn "$PRONS" --include="*.php" includes/ | grep -v node_modules
# Instantiation / static calls without class_exists
grep -rnE "new\s+\\\\?${PRONS}|${PRONS}[A-Za-z_\\\\]*::" --include="*.php" includes/ | head -20
```

- [ ] **B2** Free never names a Pro class, trait, or interface. If it must, the reference is `class_exists()`-guarded **and** the branch is bucket-1 upsell, not bucket-2 logic.

### B3 · Pro file paths and `require`/`include` from Free

```bash
grep -rnE "(require|include)(_once)?[^;]*${PROC}" --include="*.php" includes/
grep -rnE "(require|include)(_once)?[^;]*(WP_PLUGIN_DIR|plugin_dir_path|/${PRO}/)" --include="*.php" includes/
# Hard-coded Pro directory names — breaks when users rename the folder
grep -rn "$PRO" --include="*.php" includes/ *.php 2>/dev/null | grep -v node_modules | head
```

- [ ] **B3** Free never `require`s or `include`s a file from the Pro directory. Loading unreviewed code is a **Guideline 8** exposure, independent of the fatal risk.
- [ ] **B4** Free never hard-codes the Pro directory name. Users rename plugin folders; derive from a Pro-defined constant, guarded.

### B4 · Stranded logic — the bucket-2 sweep

This is the part greps alone cannot finish. Enumerate every `pro_enabled` branch and classify each by hand.

```bash
grep -rn "pro_enabled" --include="*.php" includes/ *.php 2>/dev/null | grep -v node_modules > /tmp/pro_gates.txt
wc -l /tmp/pro_gates.txt

# Positive gates — code that runs ONLY when Pro is active. These are the suspects.
grep -vE "!\s*(apply_filters|\\\$this->)|!\\\$" /tmp/pro_gates.txt
```

For each **positive** gate (`if ( $pro_enabled )` / `if ( apply_filters( '…pro_enabled', false ) )` without negation), answer:

| Question                                                                      | If yes                                                                               |
| ----------------------------------------------------------------------------- | ------------------------------------------------------------------------------------ |
| Does the guarded block only register/render Pro functionality?                | **BUCKET 2** — move to Pro, replace with `do_action()`                      |
| Does it merely*suppress* an upsell that Free shows otherwise?               | **BUCKET 1** — compliant, leave it                                            |
| Does it adjust Free's own behaviour to accommodate Pro (e.g. reorder a menu)? | **BUCKET 1** if it degrades cleanly; otherwise convert to a filter Pro applies |

- [ ] **B5** No positive `pro_enabled` branch contains Pro *implementation*. Free may **omit** or **suppress** on Pro's behalf; it may not **implement** on Pro's behalf.
- [ ] **B6** Every negative (`! pro_enabled`) branch is genuine upsell or a working free-tier path — never a stub that pretends a feature exists.

> **Worked example — compliant bucket 1.** `includes/Traits/Elements.php::promote_pro_elements()` returns early when `$this->pro_enabled`, otherwise injects a catalog of Pro widget names/icons into the Elementor panel. It does useful work *only when Pro is absent* → bucket 1. Permitted under Guideline 5 as advertising, provided nothing is clickable-into-a-dead-end. Audit its *placement* under the rulebook §4.2, not here.
>
> **Worked example — bucket 1 with a fragile edge.** `includes/Traits/Elements.php:76`:
>
> ```php
> if ( $this->pro_enabled && \version_compare( EAEL_PRO_PLUGIN_VERSION, '3.3.0', '<' ) ) {
> ```
>
> The `$this->pro_enabled &&` short-circuit means `EAEL_PRO_PLUGIN_VERSION` is only evaluated when Pro is active — correct today. But it relies on `pro_enabled` and the constant being set by the *same* plugin at the *same* time. If anything else ever filters `pro_enabled` to true, this fatals. Prefer `defined()` on the constant itself: `defined( 'EAEL_PRO_PLUGIN_VERSION' ) && version_compare( … )`. **WARN**, not FAIL.

### B5 · Orphaned assets, templates, and config in Free

Free must not ship files only Pro consumes — they inflate the directory download, and a reviewer who finds a CSS file for a paid widget reads it as a gated feature.

```bash
# Widget/element registry: does Free declare anything it does not implement?
grep -oE "'class'\s*=>\s*'[^']+'" config.php | sed "s/.*'\(\\\\[^']*\)'/\1/" | sort -u | while read -r cls; do
  path="includes/$(echo "$cls" | sed 's/^\\\\*Essential_Addons_Elementor\\\\//; s/\\\\/\//g').php"
  [ -f "$path" ] || echo "DECLARED BUT MISSING IN FREE: $cls → $path"
done

# Templates in Free's Template dir that no Free class references
for t in $(find includes/Template -maxdepth 1 -type d 2>/dev/null | tail -n +2); do
  name=$(basename "$t")
  grep -rqs "$name" includes/Elements includes/Traits || echo "ORPHAN TEMPLATE DIR: $t"
done

# Front-end assets with no matching source and no config.php reference
for f in $(find assets/front-end/css/view assets/front-end/js/view -name "*.min.*" 2>/dev/null); do
  b=$(basename "$f" | sed 's/\.min\.\(css\|js\)$//')
  grep -qs "$b" config.php || echo "UNREFERENCED ASSET: $f"
done
```

- [ ] **B7** Every class named in Free's element/widget registry exists in Free.
- [ ] **B8** No template directory in Free is consumed only by a Pro class.
- [ ] **B9** No shipped front-end asset in Free is referenced only by Pro.
- [ ] **B10** Free's `.distignore` excludes anything Pro-only that must not reach the directory zip.

### B6 · Pro-only strings, settings, and options in Free

```bash
# Settings keys Free writes but only Pro reads
grep -rnoE "(update_option|get_option|add_option)\(\s*'[a-z0-9_]+'" --include="*.php" includes/ \
  | sed "s/.*'\(.*\)'/\1/" | sort -u > /tmp/free_opts.txt
grep -rnoE "(update_option|get_option|add_option)\(\s*'[a-z0-9_]+'" --include="*.php" "$PLUGINS_DIR/$PRO/includes/" \
  | sed "s/.*'\(.*\)'/\1/" | sort -u > /tmp/pro_opts.txt
echo "--- options Free writes that only Pro consumes (suspects):"
comm -12 /tmp/free_opts.txt /tmp/pro_opts.txt
```

- [ ] **B11** Shared options are a **documented contract**, not an accident. Each one is listed in the API surface (§4.6) with an owner.
- [ ] **B12** Free does not ship translatable strings that describe Pro-only features outside the upsell path — they bloat the `.pot` and mislead translators.

---

## 4. Phase C — Free code duplicated into Pro

```bash
cd "$PLUGINS_DIR"

# Same-named PHP files on both sides
comm -12 \
  <(cd "$FREE" && find includes -name "*.php" | sed 's|.*/||' | sort -u) \
  <(cd "$PRO"  && find includes -name "*.php" | sed 's|.*/||' | sort -u) > /tmp/dupes.txt
cat /tmp/dupes.txt

# For each duplicate, locate both and diff
while read -r f; do
  a=$(find "$FREE/includes" -name "$f" | head -1)
  b=$(find "$PRO/includes"  -name "$f" | head -1)
  echo "=== $f"
  echo "  free: $a ($(wc -l < "$a") lines)   pro: $b ($(wc -l < "$b") lines)"
  diff <(sed 's/[[:space:]]\+/ /g' "$a") <(sed 's/[[:space:]]\+/ /g' "$b") | head -5
done < /tmp/dupes.txt
```

A shared *filename* is not itself a defect — `Bootstrap.php`, `Helper.php`, `Enqueue.php` legitimately exist on both sides in different namespaces. What matters is **duplicated function bodies**.

```bash
# Function names defined on BOTH sides — the real drift surface
comm -12 \
  <(grep -rhoE "function [a-z0-9_]+" "$FREE/includes" --include="*.php" | sort -u) \
  <(grep -rhoE "function [a-z0-9_]+" "$PRO/includes"  --include="*.php" | sort -u) | head -40
```

- [ ] **C1** Every duplicated filename resolves to a **distinct namespace** (verified in A1).
- [ ] **C2** No function body is copy-pasted between Free and Pro. Where one exists, Pro calls Free's.
- [ ] **C3** Every security fix in Free's git history since the last Pro release has been checked against the Pro copy. **List the fixes checked** — this item is not satisfiable by grep alone.
- [ ] **C4** Pro does not redeclare a hook Free already registers (double-firing, or silent override by priority).
- [ ] **C5** Pro does not re-register an AJAX action name Free already registers.

```bash
# Duplicate AJAX action registrations across the pair
comm -12 \
  <(grep -rhoE "wp_ajax(_nopriv)?_[a-z0-9_]+" "$FREE/includes" | sort -u) \
  <(grep -rhoE "wp_ajax(_nopriv)?_[a-z0-9_]+" "$PRO/includes"  | sort -u)
```

> **Worked example — compliant.** Pro's `Instagram_Feed.php` and `Pinterest_Feed.php` do
> `use \Essential_Addons_Elementor\Classes\Helper as HelperClass;` — Pro *calling* Free's helper across the namespace boundary rather than copying it. That is the correct direction of dependency (Pro → Free) and the correct mechanism (a class import, not a file copy). Confirm the imported symbol is on the documented API surface (§4.6); if it is not, that is a Free-side finding, not a Pro-side one.

---

## 5. Phase D — The connection contract

How the two are *allowed* to talk. This is the "WordPress rules onujai filter diye connect" requirement.

### D1 · One detection filter, one setter

```bash
GATE="eael/pro_enabled"   # ← the detection filter from Phase A
echo "--- consumers in FREE:"; grep -rn "$GATE" "$FREE/includes" --include="*.php" | wc -l
echo "--- setters in PRO:";    grep -rn "add_filter.*$GATE" "$PRO/includes" --include="*.php"
echo "--- setters anywhere in FREE (should be ZERO):"; grep -rn "add_filter.*$GATE" "$FREE" --include="*.php" | grep -v node_modules
```

- [ ] **D1** Free reads the gate through `apply_filters( '<gate>', false )` — a filter with a **safe false default**, never `defined()` on a Pro constant as the primary signal.
- [ ] **D2** Pro is the **only** setter, applied once at bootstrap (`add_filter( '<gate>', '__return_true' )`).
- [ ] **D3** Free never sets the gate itself.
- [ ] **D4** The gate is read once and cached on a property (`$this->pro_enabled`) rather than re-filtered in hot paths — 95 scattered `apply_filters()` calls is a performance smell and an inconsistency risk.

### D2 · Free exposes, Pro consumes

The sanctioned mechanisms, in order of preference:

| Need                                  | Mechanism                                                          | Shape                          |
| ------------------------------------- | ------------------------------------------------------------------ | ------------------------------ |
| Pro adds behaviour at a point in Free | `do_action( 'prefix/context/event', $args )` in Free             | Pro:`add_action()`           |
| Pro modifies a value Free computed    | `apply_filters( 'prefix/context/value', $value, $args )` in Free | Pro:`add_filter()`           |
| Pro adds widgets/elements             | Free's registry accepts additions via filter                       | Pro filters the registry array |
| Pro extends a Free widget's controls  | Free calls`do_action()` inside `register_controls()`           | Pro:`add_action()`           |
| Pro reuses a Free helper              | A documented`public static` method                               | Pro: direct call               |
| Pro replaces a Free template          | Free resolves templates through a filterable path                  | Pro filters the path           |

```bash
# Extension points Free actually publishes
grep -rhoE "(do_action|apply_filters)\(\s*'[a-z0-9_/-]+'" "$FREE/includes" --include="*.php" \
  | sed "s/.*'\(.*\)'/\1/" | sort -u > /tmp/free_hooks.txt
wc -l /tmp/free_hooks.txt

# Which of them Pro consumes
grep -rhoE "(add_action|add_filter)\(\s*'[a-z0-9_/-]+'" "$PRO/includes" --include="*.php" \
  | sed "s/.*'\(.*\)'/\1/" | sort -u > /tmp/pro_hooks.txt

echo "--- Pro hooks into Free (the real API surface):"
comm -12 /tmp/free_hooks.txt /tmp/pro_hooks.txt

echo "--- Pro listens for hooks FREE NEVER FIRES (dead listeners / renamed hooks):"
comm -13 /tmp/free_hooks.txt <(grep -E "^(eael|<prefix>)" /tmp/pro_hooks.txt)
```

- [ ] **D5** Every Free→Pro handoff goes through a hook, a documented public method, or the registry filter — never through file layout, private state, or a copied constant.
- [ ] **D6** Pro has **no dead listeners**: every own-prefix hook Pro subscribes to is actually fired by Free.

> **Calibration note.** Before reporting a dead listener, rule out a **dynamically constructed
> hook name** on the Free side — `do_action( "eael/{$type}/layout/controls", … )` will not appear
> in the literal-string grep. Count them first; if the number is small the check is high-signal:
>
> ```bash
> grep -rnoE "(do_action|apply_filters)\(\s*[\"'][^\"']*\\\$" "$FREE/includes" --include="*.php" | wc -l
> ```
>
> Then confirm each candidate by searching for its distinctive tail segment rather than the whole name.
>
> **Worked example — a real finding in this codebase.** Pro subscribes to
> `eael/product_gallery/layout/controls`, but Free fires
> `eael/product_grid/layout/controls` (`includes/Elements/Product_Grid.php:444`) — **grid**, not
> **gallery**. The listener never runs. A renamed or mistyped hook fails silently in WordPress:
> no error, no warning, just a Pro feature that quietly does nothing. This class of bug is
> invisible to every other check in this audit and is the main reason D6 exists.

- [ ] **D7** Hook names follow one convention (`prefix/context/event`) and are namespaced to the vendor.
- [ ] **D8** Pro never edits, patches, or writes to a file inside the Free plugin directory.
- [ ] **D9** Pro never reads Free's private/protected state via reflection or by extending a class solely to reach it.

### D3 · Graceful degradation, both directions

- [ ] **D10** **Free with Pro deleted:** activates clean, no fatals, no PHP notices, no missing-widget errors, admin loads, front-end pages built with Pro widgets degrade to a silent no-op or a clear placeholder — never a fatal, never a raw exception.
- [ ] **D11** **Pro with Free deactivated:** shows a dismissible admin notice naming the required plugin and version, then no-ops. Never fatals, never white-screens a live site.
- [ ] **D12** **Pro active, Free updated to a newer major:** Pro's version gate catches the skew and warns rather than fataling.

**This item cannot be satisfied by grep — it must be executed:**

```bash
# The reviewer's exact scenario: Free alone, clean install
wp plugin deactivate "$PRO"
wp plugin activate "$FREE"
wp eval 'echo "activated ok\n";'
wp option get siteurl >/dev/null && echo "admin bootstrap ok"
# Load a page built with Pro widgets and confirm no fatal
wp eval 'echo (int) did_action("init") ? "init ok\n" : "init FAILED\n";'
tail -50 wp-content/debug.log 2>/dev/null
```

Turn on `WP_DEBUG` + `WP_DEBUG_LOG` before running. A clean log is the evidence for D10.

### D4 · Version compatibility gate

```bash
grep -rn "version_compare" "$PRO/includes" --include="*.php" | grep -i "eael_plugin_version\|free\|lite" | head
grep -rn "version_compare" "$FREE/includes" --include="*.php" | grep -i "pro" | head
```

- [ ] **D13** Pro declares a **minimum Free version** and checks it on load.
- [ ] **D14** Free declares a **minimum Pro version** for any accommodation branch it keeps (see `Elements.php:76`), and those branches are removed once the floor rises past them.
- [ ] **D15** A mismatch produces a dismissible notice — never a fatal, never a silent half-broken state.

### D5 · The public API surface

- [ ] **D16** There is a written list of everything Pro is allowed to consume from Free: hook names, public classes, public methods, shared options, shared constants.
- [ ] **D17** Nothing on that list is removed or signature-changed without a deprecation cycle and a coordinated Pro release.
- [ ] **D18** Anything **not** on the list is treated as private and is not referenced by Pro.

Generate the starting draft mechanically:

```bash
{
  echo "## Hooks Free fires that Pro consumes"; comm -12 /tmp/free_hooks.txt /tmp/pro_hooks.txt
  echo; echo "## Free classes Pro imports"
  grep -rhoE "use\s+\\\\?Essential_Addons_Elementor\\\\(Classes|Traits|Elements)[A-Za-z_\\\\]*" "$PRO/includes" --include="*.php" | sort -u
  echo; echo "## Free constants Pro reads"
  grep -rhoE "EAEL_[A-Z_]+" "$PRO/includes" --include="*.php" | grep -v "EAEL_PRO" | sort -u
  echo; echo "## Shared options"; comm -12 /tmp/free_opts.txt /tmp/pro_opts.txt
} > API-SURFACE.md
```

---

## 6. Phase E — Directory survivability

Cross-check against the rulebook. These are the separation-adjacent items that cause removals; run the full pass in [`plugin-audit-skill.md`](plugin-audit-skill.md) for the rest.

- [ ] **E1** *(G5)* No Free control, setting, or menu item leads to a dead end that only payment resolves. Every teaser is inert advertising, not a broken feature. → rulebook §4.1–4.2
- [ ] **E2** *(G8)* Free loads zero code it did not ship. No `require` across the plugin boundary, no remote fetch, no self-updater. → rulebook §5.2
- [ ] **E3** *(G11)* Upsell UI is dismissible, per-user, and scoped to the plugin's own screens. Count the foreign-element injection points. → rulebook §4.5
- [ ] **E4** *(G2)* Security-fix parity between duplicated files is verified and listed. → C3
- [ ] **E5** *(G16)* Free is feature-complete standing alone — a reviewer installing only Free sees a working plugin, not a demo.
- [ ] **E6** *(G12/G17)* Free's readme and name describe what **Free** does. Pro features may be listed under a clearly-labelled upgrade section, never as if included.
- [ ] **E7** The built Free zip (not the repo) contains no Pro assets, no `src/`, no `node_modules/`, no dev config. Verify against `.distignore` by building it.
- [ ] **E8** Fatal-error sweep passes on a **Free-only** install with `WP_DEBUG` on. → D10

---

## 7. Report format

Emit exactly this. The checklist must be **complete** — every item from A1 through E8 appears with a verdict, including the ones that passed. An omitted item reads as an unchecked item.

```markdown
# Free ↔ Pro Separation Audit — <Free name> / <Pro name>

## Pair
| | Free | Pro |
|---|---|---|
| Slug | | |
| Version | | |
| Namespace | | |
| Text domain | | |
| PHP files | | |
| Detection gate | | |

## Verdict
**<SEPARATED | LEAKING | BLOCKED>** — N blockers · N stranded · N drift · N coupling

| Bucket | Count | Meaning |
|---|---|---|
| 3 · Fatal | | Free errors without Pro — rejection risk |
| 2 · Stranded | | Pro logic living in Free — move it |
| 4 · Drift | | Duplicated bodies — fix-parity risk |
| 5 · Coupling | | Non-hook dependency — refactor to an extension point |
| 1 · Upsell | | Permitted; placement audited under the rulebook |

## Complete checklist
| ID | Item | Verdict | Evidence |
|---|---|---|---|
| A1 | Distinct namespace prefixes | PASS | `Vendor\Plugin\` vs `Vendor\Plugin\Pro\` |
| A2 | Autoloaders file_exists-guarded | PASS | `autoload.php:30` |
| … | … | … | … |
| B1 | Pro constants guarded in Free | **FAIL** | `includes/Traits/Ajax_Handler.php:346,1276` |
| … | … | … | … |
| E8 | Free-only install is fatal-free | | |

## Findings

### F1 — <title>
- **Bucket:** 3 · Fatal
- **Checklist item:** B1
- **Rule:** plugin-audit-skill.md §10.1 · Guideline 8 / fatal-on-review
- **Location:** `includes/Traits/Ajax_Handler.php:346`
- **Evidence:**
  ```php
  <snippet>
```

- **Reachable by:** <who can trigger it, and how>
- **Fix:**
  ```php
  <corrected code>
  ```
- **Also fix at:** <every other call site with the same shape></every>

## Verified clean

<explicit list of what passed — stops the next audit re-litigating settled ground>

## Not audited

<runtime behaviour not executed, the built zip, translations, third-party responses — state it plainly>

## API surface (generated)

<contents of API-SURFACE.md>
```

---

## 8. Refactor patterns

The fixes this audit will keep recommending. Use these shapes verbatim.

### P1 · Stranded registration → extension point

```php
// ❌ BEFORE — Free registers Pro's widget (bucket 2 + bucket 3)
if ( $this->pro_enabled ) {
    require_once EAEL_PRO_PLUGIN_PATH . 'includes/Elements/Smart_Post_List.php';
    $widgets_manager->register( new \Essential_Addons_Elementor\Pro\Elements\Smart_Post_List() );
}

// ✅ AFTER — Free publishes the point; Pro fills it
// free/includes/Traits/Elements.php
do_action( 'eael/elements/register', $widgets_manager );

// pro/includes/Classes/Bootstrap.php
add_action( 'eael/elements/register', function ( $widgets_manager ) {
    $widgets_manager->register( new \Essential_Addons_Elementor\Pro\Elements\Smart_Post_List() );
} );
```

Free no longer names a Pro class, no longer touches a Pro path, and no longer needs the gate.

### P2 · Registry contributions via filter

```php
// free/config.php — resolved through a filter, not a hard-coded merge
return apply_filters( 'eael/registered_elements', $free_elements );

// pro — adds its own, keyed identically
add_filter( 'eael/registered_elements', function ( $elements ) {
    return array_merge( $elements, require EAEL_PRO_PLUGIN_PATH . 'config.php' );
} );
```

### P3 · Guarded template resolution

```php
// ❌ BEFORE — client-controlled branch into an unguarded Pro constant
} else if ( $template_info['dir'] === 'pro' ) {
    $dir_path = sprintf( '%sincludes', EAEL_PRO_PLUGIN_PATH );
}

// ✅ AFTER — filterable, guarded, and closed by default
$dir_path = apply_filters( 'eael/template/base_path', null, $template_info );

if ( null === $dir_path ) {
    wp_send_json_error( 'invalid_template', 400 );   // Pro absent → clean rejection
}
// …then keep the existing realpath() containment check.

// pro
add_filter( 'eael/template/base_path', function ( $path, $info ) {
    return ( isset( $info['dir'] ) && 'pro' === $info['dir'] )
        ? EAEL_PRO_PLUGIN_PATH . 'includes'
        : $path;
}, 10, 2 );
```

Free stops referencing the Pro constant entirely; the `'pro'` branch becomes unreachable-by-default instead of fatal-by-default.

### P4 · Killing a duplicated body

```php
// ❌ pro/includes/Classes/Helper.php — a second copy that will drift
public static function get_post_data( $args ) { /* 60 copied lines */ }

// ✅ delete it; call Free's
use Essential_Addons_Elementor\Classes\Helper as FreeHelper;
$data = FreeHelper::get_post_data( $args );
```

If Free's method is not public, **that is the Free-side fix**: promote it, document it on the API surface, and treat it as public thereafter.

### P5 · Degradation guards

```php
// pro main file — Free missing → notice, not fatal
add_action( 'plugins_loaded', function () {
    if ( ! defined( 'EAEL_PLUGIN_VERSION' ) ) {
        add_action( 'admin_notices', function () {
            if ( ! current_user_can( 'activate_plugins' ) ) { return; }
            printf(
                '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
                esc_html__( 'Essential Addons Pro requires Essential Addons for Elementor to be installed and active.', 'essential-addons-elementor' )
            );
        } );
        return; // no-op, never fatal
    }

    if ( version_compare( EAEL_PLUGIN_VERSION, EAEL_PRO_MIN_FREE_VERSION, '<' ) ) {
        add_action( 'admin_notices', 'eael_pro_version_skew_notice' );
        return;
    }

    Pro\Classes\Bootstrap::instance();
}, 9 ); // before Free's own bootstrap priority
```

---

## 9. Known false positives — do not report these

- **Shared filenames across Free and Pro** (`Bootstrap.php`, `Helper.php`, `Enqueue.php`) in **different namespaces** — normal and correct. Only duplicated *bodies* are findings.
- **The indirect-guard idiom** — constant names iterated as strings with `defined( $var )` on the following line. The guard trails the mention, so a backward-looking grep mis-flags it. Read the hit before reporting (see the B1 calibration note).
- **A Pro listener whose Free-side hook name is built dynamically** — `do_action( "prefix/{$type}/event" )` is invisible to a literal-string grep. Rule this out before reporting a dead listener (see the D6 calibration note).
- **`$this->pro_enabled &&` short-circuits before a Pro constant** — correct today; downgrade to WARN and recommend `defined()` on the constant itself rather than flagging it as unguarded.
- **Autoloader prefix shadowing** where Free's prefix is a string-prefix of Pro's — harmless when `file_exists()` guards the `require` (item A2). Note INFO.
- **Pro importing a Free class** (`use Vendor\Plugin\Classes\Helper`) — this is the correct dependency direction. Only flag if the symbol is not on the documented API surface.
- **`! pro_enabled` upsell branches** — bucket 1, permitted. Their placement is judged in the rulebook, not here.
- **Free's Pro-widget promo catalog** — advertising, permitted under Guideline 5 while nothing is clickable into a dead end.
- **Free carrying a compatibility branch for an old Pro version** — legitimate while the floor is below it. Flag only as WARN, to schedule removal.
- **Free and Pro both hooking the same WordPress core hook** — expected. Only duplicate *registrations of the same custom action name* are findings.

---

## 10. Run order

1. Read [`plugin-audit-skill.md`](plugin-audit-skill.md) — §0 (two rulebooks) and §10 (the seam).
2. **Phase A** — resolve the pair, fill the header table. Stop if A1 or A2 fails; nothing downstream is meaningful.
3. **Phase B** — the stranded-code sweep. Classify every hit into buckets 1/2/3 before judging.
4. **Phase C** — duplication and drift.
5. **Phase D** — the connection contract; **execute** D10–D12, do not infer them.
6. **Phase E** — cross-check the directory-removal causes.
7. Generate `API-SURFACE.md`.
8. Emit the complete checklist — **every ID from A1 to E8**, passes included.
9. Run the rulebook's §8 security pass on both codebases. Separation without security is not compliance.
