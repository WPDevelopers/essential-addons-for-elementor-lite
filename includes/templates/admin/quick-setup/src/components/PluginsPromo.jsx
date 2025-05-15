import React, { useState, useEffect } from "react";
import PluginPromoItem from "./PluginPromoItem";
import { __ } from "@wordpress/i18n";
import { getNonInstalledPlugins } from "../utils/pluginPromoUtils";

function PluginsPromo({ activeTab, handleTabChange }) {
  const plugins = getNonInstalledPlugins();

  const [checkedPlugins, setCheckedPlugins] = useState({});
  const [buttonLabel, setButtonLabel] = useState( __('Enable Templates & Blocks', "essential-addons-for-elementor-lite") );
  const [isProcessing, setIsProcessing] = useState(false);
  const [displayedPlugins, setDisplayedPlugins] = useState(plugins);

  useEffect(() => {
    // Filter out already installed plugins
    const filteredPlugins = plugins.filter(plugin => plugin.local_plugin_data === false);
    setDisplayedPlugins(filteredPlugins);

    // Set default checked state for remaining plugins
    if (plugins.length > 0 && Object.keys(checkedPlugins).length === 0) {
      const defaultChecked = {};
      filteredPlugins.forEach(plugin => {
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

  const handlePluginEnable = async () => {
    try {
      setIsProcessing(true);
      const selectedPlugins = Object.keys(checkedPlugins).filter(slug => checkedPlugins[slug]);

      // Process each selected plugin
      for (const pluginSlug of selectedPlugins) {
        const plugin = plugins.find(p => p.slug === pluginSlug);

        if (plugin) {
          // Prepare request data for installation
          let requestData = {
            action: 'wpdeveloper_install_plugin',
            security: localize.nonce,
            slug: plugin.slug,
            promotype: 'quick-setup'
          };

          // Make the AJAX request
          const response = await fetch(localize.ajaxurl, {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams(requestData).toString(),
          });

          const result = await response.json();

          if (result.success) {
            // Update plugin status in the local state
            plugin.local_plugin_data = true;
            plugin.is_active = true;

            // Remove the installed plugin from displayedPlugins
            setDisplayedPlugins(prev => prev.filter(p => p.slug !== plugin.slug));
            setCheckedPlugins(prev => {
              const newChecked = { ...prev };
              delete newChecked[plugin.slug];
              return newChecked;
            });
          }
        }
      }

      // Immediately navigate to integrations page after installation
      const nextButton = document.createElement('button');
      nextButton.setAttribute('data-next', 'integrations');
      handleTabChange({ currentTarget: nextButton });
      setButtonLabel( "" );
      setIsProcessing(false);
    } catch (error) {
      console.error('Error processing plugins:', error);
      setIsProcessing(false);
    }
  };

  // If no plugins to display, automatically redirect to next step
  useEffect(() => {
    if (displayedPlugins.length === 0 && activeTab === "pluginspromo") {
      // Create a simple button element with the data-next attribute
      const button = document.createElement('button');
      button.setAttribute('data-next', 'integrations');

      // Call handleTabChange with the button as the event target
      setTimeout(() => {
        handleTabChange({ currentTarget: button });
      }, 100);
    }
  }, [displayedPlugins, activeTab]);

  // If no plugins to display, don't render anything
  if (displayedPlugins.length === 0) {
    return null;
  }

  return (
    <>
      {displayedPlugins.map((plugin, index) => plugin.features ? <PluginPromoItem key={index} plugin={plugin} checkedPlugins={checkedPlugins} handleCheckbox={handleCheckboxChange} /> : '' )}

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

        { "" !== buttonLabel && displayedPlugins.length > 0 ?
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