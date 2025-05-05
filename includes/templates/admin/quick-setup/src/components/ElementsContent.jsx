import { __ } from "@wordpress/i18n";
import { hasDisplayablePlugins, getPluginPromoCount } from "../utils/pluginPromoUtils";

function ElementsContent({ 
  activeTab, 
  handleTabChange, 
  showElements, 
  handleShowElements, 
  selectedPreference,
  checkedElements,
  handleElementCheck 
}) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let elements_content = eaelQuickSetup?.elements_content;
  let elements_list = elements_content?.elements_list;
  let init = 0;
  let disable = "";
  let ea_pro_local_plugin_data = eaelQuickSetup?.menu_items?.ea_pro_local_plugin_data;
  let hasPluginPromo = Object.keys(eaelQuickSetup?.plugins_content?.plugins).length;

  // Check if there are any non-installed plugins to display
    const shouldShowPluginsPromo = hasDisplayablePlugins();

  elements_list =
    typeof elements_list === "object"
      ? Object.entries(elements_list)
      : elements_list;

  return (
    <>
      <div className="eael-onboard-content-wrapper eael-onboard-elements mb-4">
        <div className="eael-connect-others flex gap-4 justify-between items-start mb-10">
          <div className="flex gap-4 flex-1">
            <div className="eael-others-icon eaicon-1">
              <i className="ea-dash-icon ea-elements"></i>
            </div>
            <div className="max-w-454">
              <h4>
                {__(
                  "Turn on the Elements That You Need",
                  "essential-addons-for-elementor-lite"
                )}
              </h4>
              <p>
                {__(
                  "Enable/Disable the elements anytime you want from the Essential Addons Dashboard",
                  "essential-addons-for-elementor-lite"
                )}
              </p>
            </div>
          </div>
          <button
            className="primary-btn changelog-btn flex items-center gap-2 view-all-elements eael-d-none"
            type="button"
            onClick={handleShowElements}
          >
            {__("View All", "essential-addons-for-elementor-lite")}
            <i className="ea-dash-icon ea-right-arrow-long"></i>
          </button>

          <a
              href="https://essential-addons.com/demos/"
              target="_blank"
              rel="noopener noreferrer"
            >
              <span className="primary-btn changelog-btn">
                {__("View All", "essential-addons-for-elementor-lite")}
                <i className="ea-dash-icon ea-right-arrow-long"></i>
              </span>
            </a>
        </div>
        <div className="onboard-scroll-wrap">
          <div id="Content" className="eael-contents">
            {elements_list.map((item, index) => {
              init++;
              disable = "";

              return (
                <div key={index}>
                  <div
                    className={`flex items-center gap-2 justify-between mb-4 eael-element-title-wrap ${ showElements ? '' : disable }`}
                  >
                    <h3 className="eael-content-title">{item[1].title}</h3>
                  </div>
                  <div
                    className={`eael-content-wrapper mb-10 eael-element-content-wrap ${ showElements ? '' : disable }`}
                  >
                    {item[1]?.elements.map((element) => {
                      const preferences = element.preferences || "";
                      const isChecked = checkedElements[element.key] || false;

                      return (
                        <div
                          className="eael-content-items eael-quick-setup-post-grid"
                          key={element.key}
                        >
                          <div className="eael-content-head">
                            <h5 className="toggle-label">{element.title}</h5>
                            <label className="toggle-wrap eael-quick-setup-toggler">
                              <input
                                type="checkbox"
                                data-preferences={preferences}
                                name={`eael_element[${element.key}]`}
                                checked={isChecked}
                                onChange={(e) => handleElementCheck(element.key, e.target.checked)}
                              />
                              <span className="slider"></span>
                            </label>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                </div>
              );
            })}
          </div>
        </div>
        <div className="eael-section-overlay"></div>
      </div>
      <div className="eael-section-wrapper flex flex-end gap-4">
        <button
          className="previous-btn flex gap-2 items-center eael-setup-next-btn"
          type="button"
          data-next="configuration"
          onClick={handleTabChange}
        >
          <i className="ea-dash-icon ea-left-arrow-long"></i>
          {__("Previous", "essential-addons-for-elementor-lite")}
        </button>
        <button
          className="primary-btn install-btn flex gap-2 items-center eael-setup-next-btn"
          type="button"
          data-next={ ! ea_pro_local_plugin_data ? "go-pro" : ( hasPluginPromo && shouldShowPluginsPromo ? "pluginspromo" : "integrations" ) }
          onClick={handleTabChange}
        >
          {__("Next", "essential-addons-for-elementor-lite")}
          <i className="ea-dash-icon ea-right-arrow-long"></i>
        </button>
      </div>
    </>
  );
}

export default ElementsContent;
