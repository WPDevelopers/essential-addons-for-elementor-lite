/**
 * Utility functions for plugin promo management
 */

/**
 * Check if there are any non-installed plugins to display in the promo page
 * 
 * @returns {boolean} True if there are displayable plugins, false otherwise
 */
export const hasDisplayablePlugins = () => {
  const eaelQuickSetup = window.localize?.eael_quick_setup_data;
  const plugins_content = eaelQuickSetup?.plugins_content?.plugins;
  
  if (!plugins_content || Object.keys(plugins_content).length === 0) return false;
  
  const plugins = Object.keys(plugins_content)
    .filter(key => !isNaN(key))
    .map(key => plugins_content[key]);
  
  if (plugins.length === 0) return false;
  
  // Check if there are any plugins that are not installed
  return plugins.some(plugin => plugin.local_plugin_data === false);
};

/**
 * Get the count of plugins in the promo page
 * 
 * @returns {number} The number of plugins in the promo page
 */
export const getPluginPromoCount = () => {
  const eaelQuickSetup = window.localize?.eael_quick_setup_data;
  return Object.keys(eaelQuickSetup?.plugins_content?.plugins || {}).length;
};

/**
 * Check if the "Plugins" promo step is rendered in the wizard.
 *
 * Mirrors the skip condition in MenuItems.jsx, so navigation buttons never
 * point at a step that was never rendered.
 *
 * @returns {boolean} True if the step is visible, false otherwise
 */
export const isPluginsPromoStepVisible = () => {
  return getPluginPromoCount() > 0 && hasDisplayablePlugins();
};

/**
 * Check if the "Boost SEO & Speed" (ThinkRank + xSpeed) step is rendered in
 * the wizard.
 *
 * The step is hidden once both plugins are installed. Mirrors the skip
 * condition in MenuItems.jsx and the render guard in App.jsx.
 *
 * @returns {boolean} True if the step is visible, false otherwise
 */
export const isThinkRankStepVisible = () => {
  const eaelQuickSetup = window.localize?.eael_quick_setup_data;
  return eaelQuickSetup?.thinkrank_content?.all_installed === false;
};

/**
 * Get all non-installed plugins from the promo page
 *
 * @returns {Array} Array of non-installed plugins
 */
export const getNonInstalledPlugins = () => {
  const eaelQuickSetup = window.localize?.eael_quick_setup_data;
  const plugins_content = eaelQuickSetup?.plugins_content?.plugins;
  
  if (!plugins_content || Object.keys(plugins_content).length === 0) return [];
  
  return Object.keys(plugins_content)
    .filter(key => !isNaN(key))
    .map(key => plugins_content[key])
    .filter(plugin => plugin.local_plugin_data === false);
};
