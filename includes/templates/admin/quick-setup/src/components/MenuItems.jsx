import { __ } from "@wordpress/i18n";

function MenuItems({ activeTab, handleTabChange }) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let menu_items = eaelQuickSetup?.menu_items;
  let wizard_column = menu_items?.wizard_column;
  let templately_status = menu_items?.templately_status;
  let eblocks_status = menu_items?.eblocks_status;
  let items = menu_items?.items;
  let templately_local_plugin_data = menu_items?.templately_local_plugin_data;
  let eblocks_local_plugin_data = menu_items?.eblocks_local_plugin_data;
  let ea_pro_local_plugin_data = menu_items?.ea_pro_local_plugin_data;
  let i = 0;
  let itemClass = "";
  let hasPluginPromo = Object.keys(eaelQuickSetup?.plugins_content?.plugins).length;
  

  return (
    <>
      <div
        className={`eael-onboard-nav-list flex justify-between ${eaelQuickSetup.menu_items.wizard_column}`}
        data-step="1"
      >
        {Object.keys(items).map((item, index) => {
          // Conditional logic to skip certain items

          if( 'pluginspromo' === item && !hasPluginPromo ) {
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
