
# fix: ea wpml accordion media type translation


Summary
The EA Accordion widget's media type fields (image/video) were not being properly registered for WPML translation in the wpml-config.xml file. This caused media-related content in the Accordion widget to not appear in the WPML String Translation panel, preventing translators from translating or syncing media across languages.

Reproduction (before fix)
Install WPML and Essential Addons for Elementor
Add an EA Accordion widget to a page with media type (image/video) set
Go to WPML → String Translation
Notice the Accordion widget's media type fields are missing from the translation list
Fix
Updated wpml-config.xml to correctly include the EA Accordion widget's media type fields under the proper <node></node> configuration, ensuring WPML can detect and register them for translation.

Test plan
Install WPML and Essential Addons for Elementor
Add an EA Accordion widget with media type (image/video) content
Go to WPML → String Translation
Confirm the Accordion media type fields now appear and can be translated
Switch language and verify media translation is applied correctly
Here's a comprehensive test plan for your WPML Advanced Accordion fix:

TEST PLAN: WPML Translation Fix for Advanced Accordion Media Type Field
Overview
This test plan verifies that the Advanced Accordion widget properly registers its media type fields for WPML translation, allowing language switching to work correctly. It also documents the caching behavior with old pages.

Prerequisites
WordPress site with WPML plugin installed and configured
Multiple languages enabled (e.g., English, Dutch, German)
Essential Addons for Elementor (latest dev version with the fix) installed
Elementor page builder active
Access to WordPress admin and front-end
Test Scenario 1: New Pages with Fixed Plugin (Expected: PASS)
Objective: Verify that newly created pages with the Advanced Accordion widget properly support WPML translation.

Steps:

Create a new Elementor page
Add an Advanced Accordion widget to the page
Add accordion items with:
Title: "Test Accordion Title (English)"
Content: "Test accordion content in English"
Add at least one Media Type accordion item with:
Tab Title: "Media Tab (English)"
Media content (image or video)
Publish the page
Go to WPML > Translations and translate the page to Dutch and German
Translate all accordion fields:
Tab titles
Tab content
Media tab titles
Media tab content
Clear any caches (Elementor, WPML, browser)
Visit the page front-end and switch language using language switcher
Verify accordion content displays in the selected language for:
Regular accordion items (titles and content)
Media type accordion items (titles and content)
Expected Result: ✅ All accordion content switches correctly to the selected language without displaying English text.

Test Scenario 2: Old Pages Before Fix (Expected: FAIL without duplication)
Objective: Demonstrate the caching issue with pages created before the plugin fix was uploaded.

Steps:

Note: These should be pages created and published BEFORE uploading the dev version of the plugin
Visit an old accordion page on the front-end
Switch language using the language switcher
Observe the accordion content behavior
Expected Result: ❌ Accordion content remains in English even after switching language. WPML has cached the old widget configuration that lacked the media type field registration.

Why This Happens:

Old pages were created when the plugin didn't properly register media type fields with WPML
WPML cached the widget structure in its database
Simply updating the plugin doesn't refresh WPML's cached data for existing pages
The widget data in the database doesn't match the new field structure
Test Scenario 3: Fix for Old Pages Using EA Duplicator (Expected: PASS)
Objective: Verify that duplicating old pages resolves the WPML caching issue.

Prerequisite: Have at least one old page with the accordion widget (from before the fix)

Steps:

Go to the old accordion page in WordPress admin
Use one of these methods to duplicate the page:
Option A: Use Essential Addons Duplicator (if available) - look for duplicate/copy option in the page actions
Option B: Use WordPress native "Duplicate Post" functionality
Option C: Go to the page, edit URL to change post type, use Elementor's duplicate feature
Give the duplicated page a new title (e.g., "Old Page - Fixed")
Publish the new duplicated page
Wait 2-3 seconds for WPML to re-scan the page structure
Go to WPML > Translations and check the new page
You should see prompts to translate the accordion fields (they will appear as new strings)
Translate all accordion content to Dutch and German
Clear all caches:
Elementor cache
WPML cache
Browser cache (Ctrl+Shift+Delete or Cmd+Shift+Delete on Mac)
Visit the duplicated page on front-end
Switch language using language switcher
Verify accordion content displays in selected language
Expected Result: ✅ The duplicated page now correctly displays accordion content in the selected language. The fix works as intended.

Why Duplication Works:

When a page is duplicated, it's saved as a new post
The new post triggers re-registration of all widget fields with WPML
The updated plugin properly registers media type fields this time
WPML's database cache is refreshed for this new post ID
Pass/Fail Criteria
PASS:

✅ New pages with accordion widget translate correctly
✅ Old pages show translation issue (demonstrating the problem)
✅ Old pages work correctly after duplication
✅ WPML configuration shows all accordion fields registered
✅ All language switches work without errors
✅ Media type fields are translatable
✅ No console errors or 404s
FAIL:

❌ New pages don't support accordion field translation
❌ Duplication doesn't fix old page issue
❌ Language switching shows incomplete translations
❌ WPML doesn't recognize media type fields
❌ JavaScript errors in console when switching languages




also Checked against the shipping plugin before updating: eael_adv_accordion_media_type appears nowhere in wpml-config.xml on dev, dev-pr, master, latest, or in the released 6.7.1 on WordPress.org - so these fields are still missing from String Translation.

While verifying, I found the first commit on this branch had replaced the whole file with just the eael-adv-accordion block, dropping the <wpml-config></wpml> and <elementor-widgets></elementor> opening tags. That file is not well-formed XML (junk after document element), so WPML would have failed to parse the entire config and every EA widget would have dropped out of String Translation, not only the accordion.

Fixed in a follow-up commit on the same branch (fix/wpml-accordion-media-type-translation): wpml-config.xml is now taken from dev unchanged, with only the media-type repeater added.

<fields-in-item items_of="eael_adv_accordion_media_type_tab">
    <field type="Advance Accordion: Media Tab Title" editor_type="LINE">eael_adv_accordion_media_type_tab_title</field>
    <field type="Advance Accordion: Media Tab Content" editor_type="VISUAL">eael_adv_accordion_media_type_tab_content</field>
    <field type="Advance Accordion: Media Image" key_of="eael_adv_accordion_media_type_media" editor_type="MEDIA">id</field>
    <field type="Advance Accordion: Media Video" key_of="eael_adv_accordion_media_type_video" editor_type="MEDIA">id</field>
</fields-in-item>
Control names verified against the Pro Extender trait, which registers this repeater on the lite eael-adv-accordion widget through the eael_adv_accordion_media_type_controls action. The file now parses as valid XML. Diff against dev is 6 added lines, nothing else touched.
