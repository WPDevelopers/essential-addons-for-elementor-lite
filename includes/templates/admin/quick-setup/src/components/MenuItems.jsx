import { __ } from "@wordpress/i18n";
import { hasDisplayablePlugins, getPluginPromoCount } from "../utils/pluginPromoUtils";

/**
 * Menu keys whose tab id is not just the slugified key. The rest of the wizard
 * navigates by these ids (data-next / activeTab), so the nav item has to resolve
 * to the same value or it never gets the active state.
 */
const TAB_IDS = {
  started: "getting-started",
  go_pro: "go-pro",
};

const getTabId = (key) =>
  TAB_IDS[key] ?? key.trim().toLowerCase().replace(/[\s_]+/g, "-");

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

          // Hide the "Boost SEO & Speed" tab once both ThinkRank and xSpeed
          // are installed.
          if ( 'thinkrank' === item && eaelQuickSetup?.thinkrank_content?.all_installed !== false ) {
            return null;
          }

          itemClass = getTabId(item);

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
