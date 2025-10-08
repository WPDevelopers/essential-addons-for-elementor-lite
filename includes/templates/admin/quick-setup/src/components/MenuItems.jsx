import { __ } from "@wordpress/i18n";
import { hasDisplayablePlugins, getPluginPromoCount } from "../utils/pluginPromoUtils";

function MenuItems({ activeTab, handleTabChange }) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let menu_items = eaelQuickSetup?.menu_items;
  let items = menu_items?.items;
  let ea_pro_local_plugin_data = menu_items?.ea_pro_local_plugin_data;
  let i = 0;
  let itemClass = "";
  let hasPluginPromo = getPluginPromoCount();

  // Check if there are any non-installed plugins to display
  const shouldShowPluginsPromo = hasDisplayablePlugins();

  return (
    <>
      <div
        className={`eael-onboard-nav-list flex justify-between ${eaelQuickSetup.menu_items.wizard_column}`}
        data-step="1"
      >
        {Object.keys(items).map((item, index) => {
          // Conditional logic to skip certain items

          if ('pluginspromo' === item && (!hasPluginPromo || !shouldShowPluginsPromo)) {
            return null;
          }

          if ( 'go_pro' === item && ea_pro_local_plugin_data ) {
            return null;
          }

          itemClass = item.trim().toLowerCase().replace(/ /g, "-");

          return (
            <div
              className={`eael-onboard-nav ${
                activeTab === itemClass ? "active" : ""
              } ${itemClass}`}
              key={index}
            >
              <span className="eael-nav-count">{++i}</span>
              <span className="eael-nav-text">{items[item]}</span>
            </div>
          );
        })}
      </div>
    </>
  );
}

export default MenuItems;
