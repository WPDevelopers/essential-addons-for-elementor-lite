# Essential Addons — Feature Ownership (Free vs Pro)

Generated 2026-09-13 (updated after the Pro ownership move) from the **live registry** on the dev site (Lite 6.8.3 + Pro 7.0.3 active): `apply_filters( 'eael/registered_elements' )` and `eael/registered_extensions`. Each class was resolved with `ReflectionClass` to the file PHP actually loads.

**Owner** means: **Free** = code only in Lite · **Pro** = code only in Pro · **Free + Pro add-on** = widget/extension code in Lite, and Pro adds controls/assets through Lite's hooks and registry filter.

| | Free | Free + Pro add-on | Pro | Total |
|---|---|---|---|---|
| Widgets | 49 | 16 | 42 | 107 |
| Extensions | 9 | 2 | 9 | 20 |

## Separation checks

| Check | Result |
|---|---|
| Widget/extension class in the wrong plugin | ✅ none — every Pro class resolves to Pro, every Free class to Lite |
| Same widget name registered by both plugins | ✅ none |
| Free config registering a Pro class | ✅ none — Pro injects its own via `eael/registered_elements` / `eael/registered_extensions` |
| Free dashboard "PRO" label vs real code owner | ✅ no mismatch |
| Unguarded Pro constant in Free | ✅ none (fixed earlier) |
| Pro code using Free code | ✅ allowed direction — `Classes\Helper`, `Traits\Helper`, `Template_Query`, `Twitter_Feed`, `Login_Registration` |
| Lite files loaded **only** by Pro | ✅ Fixed — Pro ships and loads its own `marquee` and `three`. Lite copies stay only for Pro ≤ 7.0.3 |
| Pro widget logic inside Free code | ✅ PHP fixed — Post Block / Dynamic Gallery load-more logic now lives in Pro `Traits/Load_More.php`, attached via `eael/load_more/prepare_query` and `eael/load_more/before_items_html`; Lite's legacy copy runs only for Pro ≤ 7.0.3. ⚠️ Still open: `src/js/view/load-more.js` has 3 Dynamic Gallery branches (needs JS hooks + a rebuild of both plugins) |
| Free code calling Pro licensing | ⚠️ `Bootstrap.php` `LicenseManager` shim (Pro 6.2.2–6.2.3 only; kept so those users get updates) |

### How the move keeps every site working

Many Pro sites auto-update Lite from WordPress.org while staying on an older Pro (for example, after a licence lapses). So nothing Pro depends on was deleted from Lite; ownership was moved, and Lite's copy became a fallback:

| Lite | Pro | What runs |
|---|---|---|
| new | new | Pro's own assets and `Load_More` handler. Lite skips its legacy copy, because Pro returns `true` for `eael/load_more/pro_prepares_own_widgets` |
| new | ≤ 7.0.3 | Pro still loads the Lite asset copies, and Lite runs `legacy_pro_load_more_prepare()` |
| old | new | Old Lite never fires the new filters, so its built-in code runs; Pro's handler stays idle and nothing is applied twice |
| Lite only | — | Legacy copy is unreachable in practice: Pro widget classes never load |

Verified on the dev site: an equivalence test (17/17) shows Pro's handler returns identical settings, query args and taxonomy map to Lite's original code for Post Block and Dynamic Gallery cases; an older-Pro simulation (6/6) shows the fallback engages without double application; the live `load_more` endpoint and homepage respond normally.

**Phase 2 (a later release, product decision):** once Pro ≤ 7.0.3 is no longer supported, delete from Lite `legacy_pro_load_more_prepare()`, `build_dfg_acf_taxonomy_map()`, `get_dfg_post_taxonomy_classes()`, the legacy `found_posts` branch, and `assets/front-end/js/lib-view/{marquee,three}/`.

"Pro checks in Free class" counts `pro_enabled` references — the upsell/teaser gates in Free widgets. Per the #897 audit these are advertising only; no working feature is locked.

## Feature table

### Widgets (107)

| # | Feature | Slug | Owner | Class file | Pro checks in Free class | Result |
|---|---|---|---|---|---|---|
| 1 | Better Payment | `better-payment` | **Free** | Lite: `includes/Elements/Better_Payment.php` | 0 | ✅ correct plugin |
| 2 | BetterDocs Category Box | `betterdocs-category-box` | **Free** | Lite: `includes/Elements/Betterdocs_Category_Box.php` | 0 | ✅ correct plugin |
| 3 | BetterDocs Category Grid | `betterdocs-category-grid` | **Free** | Lite: `includes/Elements/Betterdocs_Category_Grid.php` | 0 | ✅ correct plugin |
| 4 | BetterDocs Search Form | `betterdocs-search-form` | **Free** | Lite: `includes/Elements/Betterdocs_Search_Form.php` | 0 | ✅ correct plugin |
| 5 | Breadcrumbs | `breadcrumbs` | **Free** | Lite: `includes/Elements/Breadcrumbs.php` | 0 | ✅ correct plugin |
| 6 | Business Reviews | `business-reviews` | **Free** | Lite: `includes/Elements/Business_Reviews.php` | 0 | ✅ correct plugin |
| 7 | Caldera Forms | `caldera-form` | **Free** | Lite: `includes/Elements/Caldera_Forms.php` | 0 | ✅ correct plugin |
| 8 | Call to Action | `call-to-action` | **Free** | Lite: `includes/Elements/Cta_Box.php` | 1 | ✅ correct plugin |
| 9 | Code Snippet | `code-snippet` | **Free** | Lite: `includes/Elements/Code_Snippet.php` | 0 | ✅ correct plugin |
| 10 | Contact Form 7 | `contact-form-7` | **Free** | Lite: `includes/Elements/Contact_Form_7.php` | 1 | ✅ correct plugin |
| 11 | Content Ticker | `content-ticker` | **Free** | Lite: `includes/Elements/Content_Ticker.php` | 2 | ✅ correct plugin |
| 12 | Countdown | `count-down` | **Free** | Lite: `includes/Elements/Countdown.php` | 1 | ✅ correct plugin |
| 13 | Dual Color Heading | `dual-header` | **Free** | Lite: `includes/Elements/Dual_Color_Header.php` | 1 | ✅ correct plugin |
| 14 | EasyJobs Career Page | `career-page` | **Free** | Lite: `includes/Elements/Career_Page.php` | 0 | ✅ correct plugin |
| 15 | EmbedPress | `embedpress` | **Free** | Lite: `includes/Elements/EmbedPress.php` | 0 | ✅ correct plugin |
| 16 | Event Calendar | `event-calendar` | **Free** | Lite: `includes/Elements/Event_Calendar.php` | 2 | ✅ correct plugin |
| 17 | Facebook Feed | `facebook-feed` | **Free** | Lite: `includes/Elements/Facebook_Feed.php` | 0 | ✅ correct plugin |
| 18 | Feature List | `feature-list` | **Free** | Lite: `includes/Elements/Feature_List.php` | 0 | ✅ correct plugin |
| 19 | Flip Box | `flip-box` | **Free** | Lite: `includes/Elements/Flip_Box.php` | 6 | ✅ correct plugin |
| 20 | Fluent Forms | `fluentform` | **Free** | Lite: `includes/Elements/FluentForm.php` | 0 | ✅ correct plugin |
| 21 | Formstack | `formstack` | **Free** | Lite: `includes/Elements/Formstack.php` | 0 | ✅ correct plugin |
| 22 | Gravity Forms | `gravity-form` | **Free** | Lite: `includes/Elements/GravityForms.php` | 0 | ✅ correct plugin |
| 23 | Interactive Circle | `interactive-circle` | **Free** | Lite: `includes/Elements/Interactive_Circle.php` | 0 | ✅ correct plugin |
| 24 | Mega Menu | `mega-menu` | **Free** | Lite: `includes/Elements/Mega_Menu.php` | 0 | ✅ correct plugin |
| 25 | NFT Gallery | `nft-gallery` | **Free** | Lite: `includes/Elements/NFT_Gallery.php` | 0 | ✅ correct plugin |
| 26 | Ninja Forms | `ninja-form` | **Free** | Lite: `includes/Elements/NinjaForms.php` | 0 | ✅ correct plugin |
| 27 | Post Grid | `post-grid` | **Free** | Lite: `includes/Elements/Post_Grid.php` | 0 | ✅ correct plugin |
| 28 | Post Timeline | `post-timeline` | **Free** | Lite: `includes/Elements/Post_Timeline.php` | 1 | ✅ correct plugin |
| 29 | Simple Menu | `simple-menu` | **Free** | Lite: `includes/Elements/Simple_Menu.php` | 0 | ✅ correct plugin |
| 30 | Sticky Video | `sticky-video` | **Free** | Lite: `includes/Elements/Sticky_Video.php` | 0 | ✅ correct plugin |
| 31 | Testimonial | `testimonials` | **Free** | Lite: `includes/Elements/Testimonial.php` | 1 | ✅ correct plugin |
| 32 | Tooltip | `tooltip` | **Free** | Lite: `includes/Elements/Tooltip.php` | 1 | ✅ correct plugin |
| 33 | Typeform | `typeform` | **Free** | Lite: `includes/Elements/TypeForm.php` | 0 | ✅ correct plugin |
| 34 | weForm | `weforms` | **Free** | Lite: `includes/Elements/WeForms.php` | 1 | ✅ correct plugin |
| 35 | Woo Add To Cart | `woo-add-to-cart` | **Free** | Lite: `includes/Elements/Woo_Add_To_Cart.php` | 0 | ✅ correct plugin |
| 36 | Woo Cart | `woo-cart` | **Free** | Lite: `includes/Elements/Woo_Cart.php` | 2 | ✅ correct plugin |
| 37 | Woo Product Compare | `woo-product-compare` | **Free** | Lite: `includes/Elements/Woo_Product_Compare.php` | 0 | ✅ correct plugin (wrong docblock namespace fixed) |
| 38 | Woo Product Description | `woo-product-description` | **Free** | Lite: `includes/Elements/Woo_Product_Description.php` | 0 | ✅ correct plugin |
| 39 | Woo Product Gallery | `woo-product-gallery` | **Free** | Lite: `includes/Elements/Woo_Product_Gallery.php` | 0 | ✅ correct plugin |
| 40 | Woo Product Grid | `product-grid` | **Free** | Lite: `includes/Elements/Product_Grid.php` | 1 | ✅ correct plugin |
| 41 | Woo Product Images | `woo-product-images` | **Free** | Lite: `includes/Elements/Woo_Product_Images.php` | 0 | ✅ correct plugin |
| 42 | Woo Product List | `woo-product-list` | **Free** | Lite: `includes/Elements/Woo_Product_List.php` | 0 | ✅ correct plugin |
| 43 | Woo Product Price | `woo-product-price` | **Free** | Lite: `includes/Elements/Woo_Product_Price.php` | 0 | ✅ correct plugin |
| 44 | Woo Product Rating | `woo-product-rating` | **Free** | Lite: `includes/Elements/Woo_Product_Rating.php` | 0 | ✅ correct plugin |
| 45 | Woo Product Short Description | `woo-product-short-description` | **Free** | Lite: `includes/Elements/Woo_Product_Short_Description.php` | 0 | ✅ correct plugin |
| 46 | Woo Product Tabs | `woo-product-tabs` | **Free** | Lite: `includes/Elements/Woo_Product_Tabs.php` | 0 | ✅ correct plugin |
| 47 | Woo Product Title | `woo-product-title` | **Free** | Lite: `includes/Elements/Woo_Product_Title.php` | 0 | ✅ correct plugin |
| 48 | WPForms | `wpforms` | **Free** | Lite: `includes/Elements/WpForms.php` | 0 | ✅ correct plugin |
| 49 | X (Twitter) Feed | `twitter-feed` | **Free** | Lite: `includes/Elements/Twitter_Feed.php` | 1 | ✅ correct plugin |
| 50 | Advanced Accordion | `adv-accordion` | **Free + Pro add-on** | Lite: `includes/Elements/Adv_Accordion.php` | 1 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 51 | Advanced Data Table | `advanced-data-table` | **Free + Pro add-on** | Lite: `includes/Elements/Advanced_Data_Table.php` | 4 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 52 | Advanced Tabs | `adv-tabs` | **Free + Pro add-on** | Lite: `includes/Elements/Adv_Tabs.php` | 1 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 53 | Creative Button | `creative-btn` | **Free + Pro add-on** | Lite: `includes/Elements/Creative_Button.php` | 6 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 54 | Data Table | `data-table` | **Free + Pro add-on** | Lite: `includes/Elements/Data_Table.php` | 3 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 55 | Fancy Text | `fancy-text` | **Free + Pro add-on** | Lite: `includes/Elements/Fancy_Text.php` | 2 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 56 | Filterable Gallery | `filter-gallery` | **Free + Pro add-on** | Lite: `includes/Elements/Filterable_Gallery.php` | 1 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 57 | Image Accordion | `image-accordion` | **Free + Pro add-on** | Lite: `includes/Elements/Image_Accordion.php` | 3 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 58 | Info Box | `info-box` | **Free + Pro add-on** | Lite: `includes/Elements/Info_Box.php` | 4 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 59 | Login / Register Form | `login-register` | **Free + Pro add-on** | Lite: `includes/Elements/Login_Register.php` | 12 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 60 | Pricing Table | `price-table` | **Free + Pro add-on** | Lite: `includes/Elements/Pricing_Table.php` | 1 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 61 | Progress Bar | `progress-bar` | **Free + Pro add-on** | Lite: `includes/Elements/Progress_Bar.php` | 5 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 62 | SVG Draw | `svg-draw` | **Free + Pro add-on** | Lite: `includes/Elements/SVG_Draw.php` | 1 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 63 | Team Member | `team-members` | **Free + Pro add-on** | Lite: `includes/Elements/Team_Member.php` | 1 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 64 | Woo Checkout | `woo-checkout` | **Free + Pro add-on** | Lite: `includes/Elements/Woo_Checkout.php` | 5 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 65 | Woo Product Carousel | `woo-product-carousel` | **Free + Pro add-on** | Lite: `includes/Elements/Woo_Product_Carousel.php` | 1 | ✅ Pro now loads its own `marquee.min.js` (Lite copy kept only for older Pro) |
| 66 | 360 Degree Photo Viewer | `sphere-photo-viewer` | **Pro** | Pro: `includes/Elements/Sphere_Photo_Viewer.php` | — | ✅ Pro now loads its own `three.min.js` (Lite copy kept only for older Pro) |
| 67 | Advanced Google Map | `adv-google-map` | **Pro** | Pro: `includes/Elements/Google_Map.php` | — | ✅ correct plugin |
| 68 | Advanced Menu | `advanced-menu` | **Pro** | Pro: `includes/Elements/Advanced_Menu.php` | — | ✅ correct plugin |
| 69 | Advanced Search | `advanced-search` | **Pro** | Pro: `includes/Elements/Advanced_Search.php` | — | ⚠️ Free promo card names it `eaicon-advanced-search`; real widget name is `eael-advanced-search` |
| 70 | Content Timeline | `content-timeline` | **Pro** | Pro: `includes/Elements/Content_Timeline.php` | — | ✅ correct plugin |
| 71 | Counter | `counter` | **Pro** | Pro: `includes/Elements/Counter.php` | — | ✅ correct plugin |
| 72 | Divider | `divider` | **Pro** | Pro: `includes/Elements/Divider.php` | — | ✅ correct plugin |
| 73 | Dynamic Gallery | `dynamic-filter-gallery` | **Pro** | Pro: `includes/Elements/Dynamic_Filterable_Gallery.php` | — | ✅ Load-more logic moved to Pro `Traits/Load_More.php` (Lite legacy copy runs only for older Pro). JS branch still in Lite `load-more.js` |
| 74 | Fancy Chart | `fancy-chart` | **Pro** | Pro: `includes/Elements/Fancy_Chart.php` | — | ✅ correct plugin |
| 75 | Figma to Elementor Converter | `figma-to-elementor` | **Pro** | Pro: `includes/Elements/Figma_To_Elementor.php` | — | ✅ correct plugin |
| 76 | Flip Carousel | `flip-carousel` | **Pro** | Pro: `includes/Elements/Flip_Carousel.php` | — | ✅ correct plugin |
| 77 | Image Comparison | `img-comparison` | **Pro** | Pro: `includes/Elements/Image_Comparison.php` | — | ✅ correct plugin |
| 78 | Image Hotspots | `image-hotspots` | **Pro** | Pro: `includes/Elements/Image_Hot_Spots.php` | — | ✅ correct plugin |
| 79 | Image Scroller | `image-scroller` | **Pro** | Pro: `includes/Elements/Image_Scroller.php` | — | ✅ correct plugin |
| 80 | Instagram Feed | `instagram-gallery` | **Pro** | Pro: `includes/Elements/Instagram_Feed.php` | — | ✅ correct plugin |
| 81 | Interactive Card | `interactive-cards` | **Pro** | Pro: `includes/Elements/Interactive_Card.php` | — | ✅ correct plugin |
| 82 | Interactive Promo | `interactive-promo` | **Pro** | Pro: `includes/Elements/Interactive_Promo.php` | — | ✅ correct plugin |
| 83 | LearnDash Course List | `learn-dash-course-list` | **Pro** | Pro: `includes/Elements/LD_Course_List.php` | — | ✅ correct plugin |
| 84 | Lightbox & Modal | `lightbox` | **Pro** | Pro: `includes/Elements/Lightbox.php` | — | ✅ correct plugin |
| 85 | Logo Carousel | `logo-carousel` | **Pro** | Pro: `includes/Elements/Logo_Carousel.php` | — | ✅ correct plugin |
| 86 | Mailchimp | `mailchimp` | **Pro** | Pro: `includes/Elements/Mailchimp.php` | — | ✅ correct plugin |
| 87 | Multicolumn Pricing Table | `multicolumn-pricing-table` | **Pro** | Pro: `includes/Elements/Multicolumn_Pricing_Table.php` | — | ✅ correct plugin |
| 88 | Offcanvas | `offcanvas` | **Pro** | Pro: `includes/Elements/Offcanvas.php` | — | ✅ correct plugin |
| 89 | One Page Navigation | `one-page-navigation` | **Pro** | Pro: `includes/Elements/One_Page_Navigation.php` | — | ✅ correct plugin |
| 90 | Pinterest Feed | `pinterest-feed` | **Pro** | Pro: `includes/Elements/Pinterest_Feed.php` | — | ✅ correct plugin |
| 91 | Post Block | `post-block` | **Pro** | Pro: `includes/Elements/Post_Block.php` | — | ✅ Load-more logic moved to Pro `Traits/Load_More.php` (Lite legacy copy runs only for older Pro) |
| 92 | Post Carousel | `post-carousel` | **Pro** | Pro: `includes/Elements/Post_Carousel.php` | — | ✅ correct plugin |
| 93 | Price Menu | `price-menu` | **Pro** | Pro: `includes/Elements/Price_Menu.php` | — | ✅ correct plugin |
| 94 | Pricing Slider | `pricing-slider` | **Pro** | Pro: `includes/Elements/Pricing_Slider.php` | — | ✅ correct plugin |
| 95 | Protected Content | `protected-content` | **Pro** | Pro: `includes/Elements/Protected_Content.php` | — | ✅ correct plugin |
| 96 | Smart Post List | `post-list` | **Pro** | Pro: `includes/Elements/Post_List.php` | — | ✅ correct plugin |
| 97 | Stacked Cards | `stacked-cards` | **Pro** | Pro: `includes/Elements/Stacked_Cards.php` | — | ✅ correct plugin |
| 98 | Static Product | `static-product` | **Pro** | Pro: `includes/Elements/Static_Product.php` | — | ✅ correct plugin |
| 99 | Team Member Carousel | `team-member-carousel` | **Pro** | Pro: `includes/Elements/Team_Member_Carousel.php` | — | ✅ correct plugin |
| 100 | Testimonial Slider | `testimonial-slider` | **Pro** | Pro: `includes/Elements/Testimonial_Slider.php` | — | ✅ correct plugin |
| 101 | Toggle | `toggle` | **Pro** | Pro: `includes/Elements/Toggle.php` | — | ✅ correct plugin |
| 102 | Woo Account Dashboard | `woo-account-dashboard` | **Pro** | Pro: `includes/Elements/Woo_Account_Dashboard.php` | — | ✅ correct plugin |
| 103 | Woo Cross Sells | `woo-cross-sells` | **Pro** | Pro: `includes/Elements/Woo_Cross_Sells.php` | — | ✅ correct plugin |
| 104 | Woo Product Collections | `woo-collections` | **Pro** | Pro: `includes/Elements/Woo_Collections.php` | — | ✅ correct plugin |
| 105 | Woo Product Slider | `woo-product-slider` | **Pro** | Pro: `includes/Elements/Woo_Product_Slider.php` | — | ✅ correct plugin |
| 106 | Woo Thank You | `woo-thank-you` | **Pro** | Pro: `includes/Elements/Woo_Thank_You.php` | — | ✅ correct plugin |
| 107 | X (Twitter) Feed Carousel | `twitter-feed-carousel` | **Pro** | Pro: `includes/Elements/Twitter_Feed_Carousel.php` | — | ✅ correct plugin |

### Extensions (20)

| # | Feature | Slug | Owner | Class file | Pro checks in Free class | Result |
|---|---|---|---|---|---|---|
| 1 | Custom JS | `custom-js` | **Free** | Lite: `includes/Extensions/Custom_JS.php` | 0 | ✅ correct plugin |
| 2 | Duplicator | `post-duplicator` | **Free** | Lite: `includes/Extensions/Post_Duplicator.php` | 0 | ✅ correct plugin |
| 3 | Hover Interactions | `special-hover-effect` | **Free** | Lite: `includes/Extensions/Hover_Effect.php` | 0 | ✅ correct plugin |
| 4 | Promotion | `promotion` | **Free** | Lite: `includes/Extensions/Promotion.php` | 1 | ✅ correct plugin |
| 5 | Reading Progress Bar | `reading-progress` | **Free** | Lite: `includes/Extensions/Reading_Progress.php` | 0 | ✅ correct plugin |
| 6 | Scroll to Top | `scroll-to-top` | **Free** | Lite: `includes/Extensions/Scroll_to_Top.php` | 0 | ✅ correct plugin |
| 7 | Table of Contents | `table-of-content` | **Free** | Lite: `includes/Extensions/Table_of_Content.php` | 0 | ✅ correct plugin |
| 8 | Vertical Text Orientation | `vertical-text-orientation` | **Free** | Lite: `includes/Extensions/Vertical_Text_Orientation.php` | 0 | ✅ correct plugin |
| 9 | Wrapper Link | `wrapper-link` | **Free** | Lite: `includes/Extensions/Wrapper_Link.php` | 0 | ✅ correct plugin |
| 10 | Image Masking | `image-masking` | **Free + Pro add-on** | Lite: `includes/Extensions/Image_Masking.php` | 2 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 11 | Liquid Glass Effects | `liquid-glass-effect` | **Free + Pro add-on** | Lite: `includes/Extensions/Liquid_Glass_Effect.php` | 3 | ✅ Free code in Lite; Pro adds controls/assets via hooks |
| 12 | Advanced Slider | `advanced-slider` | **Pro** | Pro: `includes/Extensions/Advanced_Slider.php` | — | ✅ correct plugin |
| 13 | Advanced Tooltip | `tooltip-section` | **Pro** | Pro: `includes/Extensions/EAEL_Tooltip_Section.php` | — | ✅ correct plugin |
| 14 | Conditional Display | `conditional-display` | **Pro** | Pro: `includes/Extensions/Conditional_Display.php` | — | ✅ correct plugin |
| 15 | Content Protection | `content-protection` | **Pro** | Pro: `includes/Extensions/Content_Protection.php` | — | ✅ correct plugin |
| 16 | Custom Cursor | `custom-cursor` | **Pro** | Pro: `includes/Extensions/Custom_Cursor.php` | — | ✅ correct plugin |
| 17 | Dynamic Tags | `advanced-dynamic-tags` | **Pro** | Pro: `includes/Extensions/Advanced_Dynamic_Tags.php` | — | ✅ correct plugin |
| 18 | Interactive Animations | `smooth-animation` | **Pro** | Pro: `includes/Extensions/Smooth_Animation.php` | — | ✅ correct plugin |
| 19 | Parallax | `section-parallax` | **Pro** | Pro: `includes/Extensions/EAEL_Parallax_Section.php` | — | ✅ correct plugin |
| 20 | Particles | `section-particles` | **Pro** | Pro: `includes/Extensions/EAEL_Particle_Section.php` | — | ✅ correct plugin |
