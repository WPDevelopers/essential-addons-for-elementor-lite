import { __ } from "@wordpress/i18n";

function ConfigurationContent({activeTab, handleTabChange}) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let configuration_content = eaelQuickSetup?.configuration_content;
  let ea_logo_src = configuration_content?.ea_logo_src;

  return (
    <>
      <div className="eael-onboard-content-wrapper mb-4 min-h-538">
        <div className="eael-onboard-content">
          <div className="eael-onboard-content-top">
            <img
              src={ea_logo_src}
              alt={__("EA Logo", "essential-addons-for-elementor-lite")}
            />

            <h3>
              {__(
                "Get Started with Essential Addons",
                "essential-addons-for-elementor-lite"
              )}{" "}
              🚀
            </h3>
            <p>
              {__(
                "Enhance your Elementor page building experience with 50+ amazing elements & extensions",
                "essential-addons-for-elementor-lite"
              )}{" "}
              🔥
            </p>
          </div>
          <div className="eael-onboard-content-select">
            <label className="flex-1 checkbox--label">
              <input
                id="basic"
                value="basic"
                className="eael_preferences eael-d-none"
                name="eael_preferences"
                type="radio"
                checked
              />

              <span className="select--wrapper">
                <span className="check-mark"></span>
                <h4>
                  {__("Basic", "essential-addons-for-elementor-lite")}{" "}
                  <span>
                    {__("(Recommended)", "essential-addons-for-elementor-lite")}
                  </span>
                </h4>
                <p>
                  {__(
                    "For websites where you want to only use the basic features and keep your site lightweight. Most basic elements are activated in this option.",
                    "essential-addons-for-elementor-lite"
                  )}
                </p>
              </span>
            </label>
            <label className="flex-1 checkbox--label">
              <input
                id="advance"
                value="advance"
                className="eael_preferences eael-d-none"
                name="eael_preferences"
                type="radio"
              />
              <span className="select--wrapper">
                <span className="check-mark"></span>
                <h4>{__("Advanced", "essential-addons-for-elementor-lite")}</h4>
                <p>
                  {__(
                    "For advanced users who are trying to build complex websites with advanced functionalities with Elementor. All the dynamic elements will be activated in this option.",
                    "essential-addons-for-elementor-lite"
                  )}
                </p>
              </span>
            </label>
            <label className="flex-1 checkbox--label">
              <input
                id="custom"
                value="custom"
                className="eael_preferences eael-d-none"
                name="eael_preferences"
                type="radio"
              />
              <span className="select--wrapper">
                <span className="check-mark"></span>
                <h4>{__("Custom", "essential-addons-for-elementor-lite")}</h4>
                <p>
                  {__(
                    "Pick this option if you want to configure the elements as per your wish.",
                    "essential-addons-for-elementor-lite"
                  )}
                </p>
              </span>
            </label>
          </div>
        </div>
      </div>
      <div
        id="eael-dashboard--wrapper"
        className="eael-section-wrapper flex flex-end"
      >
        <button
          className="primary-btn install-btn flex gap-2 items-center eael-setup-next-btn"
          type="button"
          data-next="elements"
          onClick={handleTabChange}
        >
          {__("Next", "essential-addons-for-elementor-lite")}
          <i className="ea-dash-icon ea-right-arrow-long"></i>
        </button>
      </div>
    </>
  );
}

export default ConfigurationContent;
