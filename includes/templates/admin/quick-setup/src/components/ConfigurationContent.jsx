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
                "Choose Your Preferred Mode for Essential Addons",
                "essential-addons-for-elementor-lite"
              )}{" "}
              🚀
            </h3>
            <p>
              {__(
                "Select any mode you desire to start with; you can also easily access features of all modes at any point later.",
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
                defaultChecked="checked"
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
                    "Use the basic features of Essential Addons and keep your site lightweight. Most basic elements are activated in this option.",
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
                    "Build complex websites with the advanced functionalities of Essential Addons. All dynamic elements will be activated in this option.",
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
                    "Configure the elements of Essential Addons according to your preferences to make your website engaging, captivate, and stand out.",
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
