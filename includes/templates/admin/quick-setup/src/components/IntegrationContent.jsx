import { __ } from "@wordpress/i18n";

function IntegrationContent({activeTab, handleTabChange}) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let integrations_content = eaelQuickSetup?.integrations_content;
  let templately_local_plugin_data =
    eaelQuickSetup?.menu_items?.templately_local_plugin_data;
  let plugin_list = integrations_content?.plugin_list;

  return (
    <>
      <div className="eael-onboard-content-wrapper eael-onboard-integrations mb-4">
        <div className="eael-connect-others flex gap-4 justify-between items-start mb-10">
          <div className="flex gap-4 flex-1">
            <div className="eael-others-icon eaicon-1">
              <i className="ea-dash-icon ea-plug"></i>
            </div>
            <div className="max-w-454">
              <h4>
                {__("Integration", "essential-addons-for-elementor-lite")}
              </h4>
              <p>
                {__(
                  "Enable/Disable the elements anytime you want from Essential Addons Dashboard",
                  "essential-addons-for-elementor-lite"
                )}
              </p>
            </div>
          </div>
          <div className="toggle-wrapper flex items-center gap-2 eael-d-none">
            <h5>
              {__(
                "Enable All Integrations",
                "essential-addons-for-elementor-lite"
              )}
            </h5>
            <label className="toggle-wrap">
              <input type="checkbox" />
              <span className="slider"></span>
            </label>
          </div>
        </div>
        <div className="eael-integration-content-wrapper onboard-scroll-wrap">
          {plugin_list.map((plugin) => (
            <div className="eael-integration-item" key={plugin.basename}>
              <div className="eael-integration-header flex gap-2 items-center">
                <img src={plugin.logo} alt="logo" width="30" />
                <h5>{plugin.title}</h5>
              </div>
              <div className="eael-integration-footer">
                <p>{plugin.desc}</p>
                <div className="integration-settings flex justify-between items-center">
                  <h5 className="toggle-label eael-d-none">
                    {__("Integration", "essential-addons-for-elementor-lite")}
                  </h5>
                  {plugin.local_plugin_data === false ? (
                    <button
                      className="wpdeveloper-plugin-installer eael-quick-setup-wpdeveloper-plugin-installer"
                      data-action="install"
                      data-slug={plugin.slug}
                      onClick={() => handleActionClick("install", plugin.slug)}
                    >
                      {__("Install", "essential-addons-for-elementor-lite")}
                    </button>
                  ) : (
                    plugin.is_active ? 
									  <button className="wpdeveloper-plugin-installer button__white-not-hover eael-quick-setup-wpdeveloper-plugin-installer">{__("Activated", "essential-addons-for-elementor-lite")}</button>
                    :
                    <button
                      className="wpdeveloper-plugin-installer eael-quick-setup-wpdeveloper-plugin-installer"
                      data-action="activate"
                      data-basename={plugin.basename}
                      onClick={() =>
                        handleActionClick("activate", plugin.basename)
                      }
                    >
                      {__("Activate", "essential-addons-for-elementor-lite")}
                    </button>
                  )}
                  <label className="toggle-wrap">
                    <input
                      type="checkbox"
                      className="enable-integration-switch"
                      defaultChecked={plugin.is_active ? 'checked' : null}
                    />
                    <span className="slider"></span>
                  </label>
                </div>
              </div>
            </div>
          ))}
        </div>
        <div className="eael-section-overlay"></div>
      </div>
      <div className="eael-section-wrapper flex flex-end gap-4">
        <button
            className="previous-btn flex gap-2 items-center eael-setup-next-btn"
            data-next={ ! templately_local_plugin_data !== false ? 'templately' : 'go-pro'}
        >
          <i className="ea-dash-icon ea-left-arrow-long"></i>
          {__("Previous", "essential-addons-for-elementor-lite")}
        </button>
        <button
            className="primary-btn install-btn flex gap-2 items-center eael-setup-next-btn1 eael-setup-wizard-save"
            data-next=""
        >
          {__("Finish", "essential-addons-for-elementor-lite")}
        </button>
      </div>
    </>
  );
}

export default IntegrationContent;
