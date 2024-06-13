import { __ } from "@wordpress/i18n";

function GoProContent({activeTab, handleTabChange}) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let go_pro_content = eaelQuickSetup?.go_pro_content;
  let feature_items = go_pro_content?.feature_items;
  let templately_local_plugin_data =
    eaelQuickSetup?.menu_items?.templately_local_plugin_data;

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
                  "Unlocking 35+ Advanced PRO Elements",
                  "essential-addons-for-elementor-lite"
                )}
              </h4>
              <p>
                {__(
                  "Lorem ipsum is placeholder text commonly used in the graphic",
                  "essential-addons-for-elementor-lite"
                )}
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
                "Explore Premiere Pro features",
                "essential-addons-for-elementor-lite"
              )}
            </h2>
            <p className="mb-7">
              {__(
                "Learn all about the tools and techniques you can use to edit videos, animate titles, add effects, mix sound, and more.",
                "essential-addons-for-elementor-lite"
              )}
            </p>
            <a
              href="https://essential-addons.com/demos/"
              target="_blank"
              rel="noopener noreferrer"
            >
              <span className="primary-btn changelog-btn">
                <i className="ea-dash-icon ea-link"></i>
                {__("View More", "essential-addons-for-elementor-lite")}
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
            data-next="elements"
        >
          <i className="ea-dash-icon ea-left-arrow-long"></i>
          {__('Previous', "essential-addons-for-elementor-lite")}
        </button>
        <button
            className="primary-btn install-btn flex gap-2 items-center eael-setup-next-btn"
            data-next={
              !templately_local_plugin_data !== false
                  ? "templately"
                  : "integrations"
            }
        >
          {__('Next', "essential-addons-for-elementor-lite")}
          <i className="ea-dash-icon ea-right-arrow-long"></i>
        </button>
      </div>
    </>
  );
}

export default GoProContent;
