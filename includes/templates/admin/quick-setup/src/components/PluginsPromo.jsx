import React, { useState } from "react";
import PluginPromoItem from "./PluginPromoItem";
import { __ } from "@wordpress/i18n";

function PluginsPromo({ activeTab, handleTabChange, handleIntegrationSwitch }) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let plugins_content = eaelQuickSetup?.plugins_content?.plugins;

  const plugins = Object.keys(plugins_content || {})
    .filter(key => !isNaN(key)) // Only numeric keys
    .map(key => plugins_content[key]);

  return (
    <>
      {plugins.map((plugin, index) => plugin.features ? <PluginPromoItem key={index} plugin={plugin} /> : '' )}

      <div className="eael-section-wrapper flex flex-end gap-4">
        <button
          className="previous-btn flex gap-2 items-center eael-setup-next-btn"
          type="button"
          data-next="integrations"
          onClick={handleTabChange}
        >
          {__("Skip", "essential-addons-for-elementor-lite")}
        </button>

        <button
          className="primary-btn install-btn flex gap-2 items-center eael-setup-next-btn eael-quick-setup-next-button wpdeveloper-plugin-installer"
          type="button"
          data-next="integrations"
          data-action="install"
          data-slug="templately"
          onClick={async (event) => {
            // await handleIntegrationSwitch(event, templatelyPlugin, 1, setTemplatelyPlugin);
          }}
        >
          {__("Enable Templates", "essential-addons-for-elementor-lite")}
        </button>
      </div>
    </>
  );
}

export default PluginsPromo;