import { __ } from "@wordpress/i18n";

function MenuItems({ activeTab, handleTabChange }) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let menu_items = eaelQuickSetup?.menu_items;
  let items = menu_items?.items;
  let ea_pro_local_plugin_data = menu_items?.ea_pro_local_plugin_data;
  let i = 0;
  let itemClass = "";
  let hasPluginPromo = Object.keys(eaelQuickSetup?.plugins_content?.plugins || {}).length;

  // Check if there are any non-installed plugins to display in the promo page
  const hasDisplayablePlugins = (() => {
    const plugins_content = eaelQuickSetup?.plugins_content?.plugins;
    if (!plugins_content || Object.keys(plugins_content).length === 0) return false;

    const plugins = Object.keys(plugins_content).filter(key => !isNaN(key)).map(key => plugins_content[key]);
    if (plugins.length === 0) return false;

    // Check if there are any plugins that are not installed
    return plugins.some(plugin => plugin.local_plugin_data === false);
  })();

  return (
    <>
      <div
        className={`eael-onboard-nav-list flex justify-between ${eaelQuickSetup.menu_items.wizard_column}`}
        data-step="1"
      >
        {Object.keys(items).map((item, index) => {
          // Conditional logic to skip certain items

          if ('pluginspromo' === item && (!hasPluginPromo || !hasDisplayablePlugins)) {
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
