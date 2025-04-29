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
  const [isProcessing, setIsProcessing] = useState(false);

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
    if( checkedPlugins?.templately && !checkedPlugins?.["essential-blocks"] ){
      setButtonLabel( __('Enable Templates', "essential-addons-for-elementor-lite") );
    } else if( !checkedPlugins?.templately && checkedPlugins?.["essential-blocks"] ){
      setButtonLabel( __('Enable Blocks', "essential-addons-for-elementor-lite") );
    } else if ( checkedPlugins?.templately && checkedPlugins?.["essential-blocks"] ) {
      setButtonLabel( __('Enable Templates & Blocks', "essential-addons-for-elementor-lite") );
    } else{
      setButtonLabel( "" );
    }
  }, [checkedPlugins]);

  const handlePluginEnable = async (event) => {
    try {
      setIsProcessing(true);
      const selectedPlugins = Object.keys(checkedPlugins).filter(slug => checkedPlugins[slug]);
      
      // Process each selected plugin
      for (const pluginSlug of selectedPlugins) {
        const plugin = plugins.find(p => p.slug === pluginSlug);
        if (plugin) {
          const syntheticEvent = {
            ...event,
            currentTarget: {
              getAttribute: (attr) => {
                switch(attr) {
                  case 'data-slug':
                    return plugin.slug;
                  case 'data-action':
                    return 'install';
                  case 'data-next':
                    return 'integrations';
                  default:
                    return null;
                }
              }
            }
          };
          
          await handleIntegrationSwitch(syntheticEvent, plugin, 1);
        }
      }
      // Only change tab after all plugins are processed
      handleTabChange(event);
    } catch (error) {
      console.error('Error processing plugins:', error);
    } finally {
      setIsProcessing(false);
    }
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
          disabled={isProcessing}
        >
          {__("Skip", "essential-addons-for-elementor-lite")}
        </button>

        { "" !== buttonLabel ?
        <button
          className="primary-btn install-btn flex gap-2 items-center eael-setup-next-btn eael-quick-setup-next-button wpdeveloper-plugin-installer"
          type="button"
          data-next="integrations"
          onClick={handlePluginEnable}
          disabled={isProcessing}
        >
          {isProcessing ? __("Processing...", "essential-addons-for-elementor-lite") : buttonLabel}
        </button> : "" }
      </div>
    </>
  );
}

export default PluginsPromo;