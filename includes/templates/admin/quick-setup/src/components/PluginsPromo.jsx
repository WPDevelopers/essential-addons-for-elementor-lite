import React, { useState, useEffect } from "react";
import PluginPromoItem from "./PluginPromoItem";
import { __ } from "@wordpress/i18n";

function PluginsPromo({ activeTab, handleTabChange, handleIntegrationSwitch }) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let plugins_content = eaelQuickSetup?.plugins_content?.plugins;
  let hasPluginPromo = Object.keys(eaelQuickSetup?.plugins_content?.plugins).length;
  const plugins = Object.keys(plugins_content || {}).filter(key => !isNaN(key)).map(key => plugins_content[key]);

  const [checkedPlugins, setCheckedPlugins] = useState({});
  const [buttonLabel, setButtonLabel] = useState( __('Enable Templates & Blocks', "essential-addons-for-elementor-lite") );

  useEffect(() => {
    if (hasPluginPromo > 0 && Object.keys(checkedPlugins).length === 0) {
      const defaultChecked = {};
      plugins.forEach(plugin => {
        defaultChecked[plugin.slug] = true;
      });
      setCheckedPlugins(defaultChecked);
    }
  }, []);

  const handleCheckboxChange = (slug) => {
    setCheckedPlugins((prev) => ({
      ...prev,
      [slug]: !prev[slug],
    }));
  };

  useEffect(() => {
    if( checkedPlugins?.templately && !checkedPlugins?.essential_blocks ){
      setButtonLabel( __('Enable Templates', "essential-addons-for-elementor-lite") );
    } else if( !checkedPlugins?.templately && checkedPlugins?.essential_blocks ){
      setButtonLabel( __('Enable Blocks', "essential-addons-for-elementor-lite") );
    } else if ( checkedPlugins?.templately && checkedPlugins?.essential_blocks ) {
      setButtonLabel( __('Enable Templates & Blocks', "essential-addons-for-elementor-lite") );
    } else{
      setButtonLabel( "" );
    }
  }, [checkedPlugins]);

  const handlePluginEnable = () => {
    const selectedPlugins = Object.keys(checkedPlugins).filter(slug => checkedPlugins[slug]);
    // handleSubmit(selectedPlugins); // send to your function

  };

  return (
    <>
      {plugins.map((plugin, index) => plugin.features ? <PluginPromoItem key={index} plugin={plugin} checkedPlugins={checkedPlugins} handleCheckbox={handleCheckboxChange} /> : '' )}

      <div className="eael-section-wrapper flex flex-end gap-4">
        <button
          className="previous-btn flex gap-2 items-center eael-setup-next-btn"
          type="button"
          data-next="integrations"
          onClick={handleTabChange}
        >
          {__("Skip", "essential-addons-for-elementor-lite")}
        </button>

        { "" !== buttonLabel ?
        <button
          className="primary-btn install-btn flex gap-2 items-center eael-setup-next-btn eael-quick-setup-next-button wpdeveloper-plugin-installer"
          type="button"
          data-next="integrations"
          data-action="install"
          data-slug="templately"
          onClick={async (event) => {
            console.log();
            
            // await handleIntegrationSwitch(event, templatelyPlugin, 1, setTemplatelyPlugin);
          }}
        >
          {buttonLabel}
        </button> : "" }
      </div>
    </>
  );
}

export default PluginsPromo;