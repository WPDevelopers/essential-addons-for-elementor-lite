import { __ } from "@wordpress/i18n";

function IntegrationContent({
  activeTab,
  handleTabChange,
  modalTarget,
  handleModalChange,
  closeModal,
}) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let integrations_content = eaelQuickSetup?.integrations_content;
  let templately_local_plugin_data =
    eaelQuickSetup?.menu_items?.templately_local_plugin_data;
  let plugin_list = integrations_content?.plugin_list;

  const handleIntegrationSwitch = async (event, plugin) => {
    const action = plugin.local_plugin_data === false ? 'install' : 'activate';
    const identifier = action === 'install' ? plugin.slug : plugin.basename;

    let requestData = {
      action: action === 'install' ? 'wpdeveloper_install_plugin' : 'wpdeveloper_activate_plugin',
      security: localize.nonce,
    };
    requestData[action === 'install' ? 'slug' : 'basename'] = identifier;

    const button = event.target
      .closest('.eael-integration-footer')
      .querySelector('.wpdeveloper-plugin-installer');

    if (button) {
      button.disabled = true;
      button.textContent = action === 'install' ? 'Installing...' : 'Activating...';

      try {
        const response = await axios.post(localize.ajaxurl, requestData);

        if (response.data.success) {
          button.textContent = 'Activated';
          button.dataset.action = 'completed';

          if (
            (pagenow === 'admin_page_eael-setup-wizard' &&
              button.classList.contains('eael-quick-setup-next-button')) ||
            (pagenow === 'toplevel_page_eael-settings' &&
              button.classList.contains('eael-dashboard-templately-install-btn'))
          ) {
            button.textContent = 'Enabled Templates';
          }
          document.body.dispatchEvent(
            new CustomEvent('eael_after_active_plugin', {
              detail: { plugin: identifier },
            })
          );
        } else {
          button.textContent = action === 'install' ? 'Install' : 'Activate';
          button.disabled = false;
        }
      } catch (error) {
        console.log(error.response.data);
        button.textContent = action === 'install' ? 'Install' : 'Activate';
        button.disabled = false;
      }
    }
  };

  const handleSaveClick = async (event) => {
    event.preventDefault();
    const button = event.target;
    button.setAttribute("disabled", "disabled");

    if (button.id === "eael-count-me-bt") {
      document.getElementById("eael_user_email_address").value = 1;
    }

    let fields = new FormData(
      document.querySelector("form.eael-setup-wizard-form")
    );

    console.log(fields);
    fields = new URLSearchParams(fields).toString();
    console.log(fields);

    try {
      const response = await fetch(localize.ajaxurl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "save_setup_wizard_data",
          security: localize.nonce,
          fields: fields,
        }),
      });

      const result = await response.json();

      if (result.success) {
        handleModalChange({ currentTarget: { getAttribute: () => "modal" } });

        setTimeout(() => {
          window.location = result.data.redirect_url;
        }, 3000);
      } else {
        button.removeAttribute("disabled");
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "error",
        });
      }
    } catch (error) {
      button.removeAttribute("disabled");
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "error",
      });
    }
  };

  return (
    <>
      <div className="eael-onboard-content-wrapper eael-onboard-integrations mb-4">
        <div className="eael-connect-others flex gap-4 justify-between items-start mb-10">
          <div className="flex gap-4 flex-1">
            <div className="eael-others-icon eaicon-1">
              <i className="ea-dash-icon ea-plug"></i>
            </div>
            <div className="max-w-454">
              <h4>
                {__(
                  "Fusion Hub for WordPress",
                  "essential-addons-for-elementor-lite"
                )}
              </h4>
              <p>
                {__(
                  "Boost your websites with some exclusive and popular plugins to get the most out of WordPress.",
                  "essential-addons-for-elementor-lite"
                )}
              </p>
            </div>
          </div>
          <div className="toggle-wrapper flex items-center gap-2 eael-d-none">
            <h5>
              {__(
                "Enable All Integrations",
                "essential-addons-for-elementor-lite"
              )}
            </h5>
            <label className="toggle-wrap">
              <input type="checkbox" />
              <span className="slider"></span>
            </label>
          </div>
        </div>
        <div className="eael-integration-content-wrapper onboard-scroll-wrap">
          {plugin_list.map((plugin) => (
            <div className="eael-integration-item" key={plugin.basename}>
              <div className="eael-integration-header flex gap-2 items-center">
                <img src={plugin.logo} alt="logo" width="30" />
                <h5>{plugin.title}</h5>
              </div>
              <div className="eael-integration-footer">
                <p>{plugin.desc}</p>
                <div className="integration-settings flex justify-between items-center">
                  <h5 className="toggle-label">
                    {plugin.local_plugin_data === false
                      ? __("Install", "essential-addons-for-elementor-lite")
                      : plugin.is_active
                      ? __("Activated", "essential-addons-for-elementor-lite")
                      : __("Activate", "essential-addons-for-elementor-lite")}
                  </h5>
                  <button
                    className="wpdeveloper-plugin-installer eael-quick-setup-wpdeveloper-plugin-installer eael-d-none"
                    data-action={
                      plugin.local_plugin_data === false ? 'install' : 'activate'
                    }
                    data-slug={plugin.slug}
                    data-basename={plugin.basename}
                  >
                    {plugin.local_plugin_data === false
                      ? __('Install', 'essential-addons-for-elementor-lite')
                      : plugin.is_active
                      ? __('Activated', 'essential-addons-for-elementor-lite')
                      : __('Activate', 'essential-addons-for-elementor-lite')}
                  </button>
                  <label className="toggle-wrap">
                    <input
                      type="checkbox"
                      className="enable-integration-switch"
                      defaultChecked={plugin.is_active}
                      onChange={(event) =>
                        handleIntegrationSwitch(event, plugin)
                      }
                    />
                    <span className="slider"></span>
                  </label>
                </div>
              </div>
            </div>
          ))}
        </div>
        <div className="eael-section-overlay"></div>
      </div>
      <div className="eael-section-wrapper flex flex-end gap-4">
        <button
          className="previous-btn flex gap-2 items-center eael-setup-next-btn"
          type="button"
          data-next={
            !templately_local_plugin_data !== false ? "templately" : "go-pro"
          }
          onClick={handleTabChange}
        >
          <i className="ea-dash-icon ea-left-arrow-long"></i>
          {__("Previous", "essential-addons-for-elementor-lite")}
        </button>
        <button
          className="primary-btn install-btn flex gap-2 items-center eael-setup-next-btn1 eael-setup-wizard-save"
          data-next=""
          onClick={handleSaveClick}
        >
          {__("Finish", "essential-addons-for-elementor-lite")}
        </button>
      </div>
    </>
  );
}

export default IntegrationContent;
