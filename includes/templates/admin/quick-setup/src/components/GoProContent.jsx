import { __ } from "@wordpress/i18n";

function GoProContent({ activeTab, handleTabChange }) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let go_pro_content = eaelQuickSetup?.go_pro_content;
  let feature_items = go_pro_content?.feature_items;
  let templately_local_plugin_data =eaelQuickSetup?.menu_items?.templately_local_plugin_data;
  let hasPluginPromo = Object.keys(eaelQuickSetup?.plugins_content?.plugins).length;

  return (
    <>
      <div className="eael-onboard-content-wrapper eael-onboard-pro mb-4">
        <div className="eael-connect-others flex gap-4 justify-between items-start mb-10">
          <div className="flex gap-4 flex-1">
            <div className="eael-others-icon eaicon-1">
              <i className="ea-dash-icon ea-lock"></i>
            </div>
            <div className="max-w-454">
              <h4>
                {__(
                  "Unlock 40+ Advanced PRO Widgets",
                  "essential-addons-for-elementor-lite"
                )}
              </h4>
              <p>
                {__(
                  "Elevate your web-building experience with an array of cool premium elements, cutting-edge extensions & robust integrations. ",
                  "essential-addons-for-elementor-lite"
                )}
                🔥
              </p>
            </div>
          </div>
          <a
            target="_blank"
            href="https://wpdeveloper.com/in/upgrade-essential-addons-elementor"
            rel="noopener noreferrer"
          >
            <span className="upgrade-button">
              <i className="ea-dash-icon ea-crown-1"></i>
              {__("Upgrade to PRO", "essential-addons-for-elementor-lite")}
            </span>
          </a>
        </div>
        <div className="eael-pro-features flex justify-between items-center">
          <div className="eael-features-content">
            <h2>
              {__(
                "Explore Premium Features",
                "essential-addons-for-elementor-lite"
              )}
            </h2>
            <p className="mb-7">
              {__(
                "Discover the premium features of the most popular elements library for Elementor. Experience the web building experience with:",
                "essential-addons-for-elementor-lite"
              )}
            </p>
            <div className="eael-feature-list-wrap mb-6">
              <div className="eael-feature-list-item flex gap-2 items-center mb-4">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5 7L6.33333 8.33333L9 5.66667M1 7C1 7.78793 1.15519 8.56815 1.45672 9.2961C1.75825 10.0241 2.20021 10.6855 2.75736 11.2426C3.31451 11.7998 3.97595 12.2417 4.7039 12.5433C5.43185 12.8448 6.21207 13 7 13C7.78793 13 8.56815 12.8448 9.2961 12.5433C10.0241 12.2417 10.6855 11.7998 11.2426 11.2426C11.7998 10.6855 12.2417 10.0241 12.5433 9.2961C12.8448 8.56815 13 7.78793 13 7C13 6.21207 12.8448 5.43185 12.5433 4.7039C12.2417 3.97595 11.7998 3.31451 11.2426 2.75736C10.6855 2.20021 10.0241 1.75825 9.2961 1.45672C8.56815 1.15519 7.78793 1 7 1C6.21207 1 5.43185 1.15519 4.7039 1.45672C3.97595 1.75825 3.31451 2.20021 2.75736 2.75736C2.20021 3.31451 1.75825 3.97595 1.45672 4.7039C1.15519 5.43185 1 6.21207 1 7Z" stroke="#750EF4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p>
                {__(
                  "Customization Flexibility in Design with Premium Creative Elements.",
                  "essential-addons-for-elementor-lite"
                )}
              </p>
              </div>
              <div className="eael-feature-list-item flex gap-2 items-center mb-4">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5 7L6.33333 8.33333L9 5.66667M1 7C1 7.78793 1.15519 8.56815 1.45672 9.2961C1.75825 10.0241 2.20021 10.6855 2.75736 11.2426C3.31451 11.7998 3.97595 12.2417 4.7039 12.5433C5.43185 12.8448 6.21207 13 7 13C7.78793 13 8.56815 12.8448 9.2961 12.5433C10.0241 12.2417 10.6855 11.7998 11.2426 11.2426C11.7998 10.6855 12.2417 10.0241 12.5433 9.2961C12.8448 8.56815 13 7.78793 13 7C13 6.21207 12.8448 5.43185 12.5433 4.7039C12.2417 3.97595 11.7998 3.31451 11.2426 2.75736C10.6855 2.20021 10.0241 1.75825 9.2961 1.45672C8.56815 1.15519 7.78793 1 7 1C6.21207 1 5.43185 1.15519 4.7039 1.45672C3.97595 1.75825 3.31451 2.20021 2.75736 2.75736C2.20021 3.31451 1.75825 3.97595 1.45672 4.7039C1.15519 5.43185 1 6.21207 1 7Z" stroke="#750EF4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p>
                {__(
                  "Advanced WooCommerce Widgets like Checkout, Cross-Sells & more.",
                  "essential-addons-for-elementor-lite"
                )}
                </p>
              </div>
              <div className="eael-feature-list-item flex gap-2 items-center mb-4">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5 7L6.33333 8.33333L9 5.66667M1 7C1 7.78793 1.15519 8.56815 1.45672 9.2961C1.75825 10.0241 2.20021 10.6855 2.75736 11.2426C3.31451 11.7998 3.97595 12.2417 4.7039 12.5433C5.43185 12.8448 6.21207 13 7 13C7.78793 13 8.56815 12.8448 9.2961 12.5433C10.0241 12.2417 10.6855 11.7998 11.2426 11.2426C11.7998 10.6855 12.2417 10.0241 12.5433 9.2961C12.8448 8.56815 13 7.78793 13 7C13 6.21207 12.8448 5.43185 12.5433 4.7039C12.2417 3.97595 11.7998 3.31451 11.2426 2.75736C10.6855 2.20021 10.0241 1.75825 9.2961 1.45672C8.56815 1.15519 7.78793 1 7 1C6.21207 1 5.43185 1.15519 4.7039 1.45672C3.97595 1.75825 3.31451 2.20021 2.75736 2.75736C2.20021 3.31451 1.75825 3.97595 1.45672 4.7039C1.15519 5.43185 1 6.21207 1 7Z" stroke="#750EF4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p>
                {__(
                  "Cutting-edge Extensions Like Custom JS, Content Protection & more.",
                  "essential-addons-for-elementor-lite"
                )}
                </p>
              </div>
            </div>
            <a
              href="https://essential-addons.com/demos/"
              target="_blank"
              rel="noopener noreferrer"
            >
              <span className="primary-btn changelog-btn">
                <i className="ea-dash-icon ea-link"></i>
                {__("Learn More", "essential-addons-for-elementor-lite")}
              </span>
            </a>
          </div>
          <div className="features-widget-wrapper">
            {feature_items.map((feature_item, index) => (
              <div className="features-widget-item" key={index}>
                <a
                  href={feature_item.link}
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  <img
                    src={feature_item.img_src}
                    alt={__(
                      feature_item.title,
                      "essential-addons-for-elementor-lite"
                    )}
                  />
                  <span className="eael-tooltip">
                    {__(
                      feature_item.title,
                      "essential-addons-for-elementor-lite"
                    )}
                  </span>
                </a>
              </div>
            ))}
          </div>
        </div>
      </div>
      <div className="eael-section-wrapper flex flex-end gap-4">
        <button
          className="previous-btn flex gap-2 items-center eael-setup-next-btn"
          type="button"
          data-next="elements"
          onClick={handleTabChange}
        >
          <i className="ea-dash-icon ea-left-arrow-long"></i>
          {__('Previous', "essential-addons-for-elementor-lite")}
        </button>
        <button
          className="primary-btn install-btn flex gap-2 items-center eael-setup-next-btn"
          type="button"
          data-next={ hasPluginPromo ? "pluginspromo" : "integrations" }
          onClick={handleTabChange}
        >
          {__('Next', "essential-addons-for-elementor-lite")}
          <i className="ea-dash-icon ea-right-arrow-long"></i>
        </button>
      </div>
    </>
  );
}

export default GoProContent;
