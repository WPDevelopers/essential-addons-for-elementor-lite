import { React, useState, useRef, useEffect } from "react";
import MenuItems from "./MenuItems.jsx";
import GettingStartedContent from "./GettingStartedContent.jsx";
import ConfigurationContent from "./ConfigurationContent.jsx";
import ElementsContent from "./ElementsContent.jsx";
import GoProContent from "./GoProContent.jsx";
import PluginsPromo from "./PluginsPromo.jsx";
import IntegrationContent from "./IntegrationContent.jsx";
import ModalContent from "./ModalContent.jsx";

function App() {
  let eaelQuickSetup = localize.eael_quick_setup_data;
  let is_tracking_allowed = eaelQuickSetup?.getting_started_content?.is_tracking_allowed;
  let currentTabValue = ! is_tracking_allowed ? 'getting-started' : 'configuration';
  let hasPluginPromo = Object.keys(eaelQuickSetup?.plugins_content?.plugins).length;

  const [activeTab, setActiveTab] = useState(currentTabValue);
  const [modalTarget, setModalTarget] = useState("");
  const [showElements, setShowElements] = useState(0);
  const [emailAddress, setEmailAddress] = useState(is_tracking_allowed);
  const [disableSwitches, setDisableSwitches] = useState(false);
  const [selectedPreference, setSelectedPreference] = useState("basic");
  const [checkedElements, setCheckedElements] = useState({});

  // Initialize checkedElements with basic elements on component mount
  useEffect(() => {
    const elements_content = eaelQuickSetup?.elements_content;
    const elements_list = elements_content?.elements_list;

    if (elements_list) {
      const initialCheckedState = {};
      Object.entries(elements_list).forEach(([category, categoryData]) => {
        categoryData.elements.forEach(element => {
          initialCheckedState[element.key] = element.preferences === "basic";
        });
      });
      setCheckedElements(initialCheckedState);
    }
  }, []); // Empty dependency array means this runs once on mount

  const handleTabChange = (event) => {
    setActiveTab(event.currentTarget.getAttribute("data-next"));

    if (event.currentTarget.classList.contains("eael-user-email-address")) {
      setEmailAddress("1");
      document.getElementById("eael_user_email_address").value = 1;
      saveWPIns();
    }
  };

  const handlePreferenceChange = (event) => {
    const newPreference = event.target.value;
    setSelectedPreference(newPreference);

    // Get all elements from the elements list
    const elements_content = eaelQuickSetup?.elements_content;
    const elements_list = elements_content?.elements_list;

    if (newPreference === "custom") {
      // For custom, don't auto-check anything
      setCheckedElements({});
    } else {
      // For basic or advance, check elements with matching preferences
      const newCheckedState = {};
      Object.entries(elements_list).forEach(([category, categoryData]) => {
        categoryData.elements.forEach(element => {
          newCheckedState[element.key] = element.preferences === newPreference;
        });
      });
      setCheckedElements(newCheckedState);
    }
  };

  const handleElementCheck = (elementKey, isChecked) => {
    setCheckedElements(prev => ({
      ...prev,
      [elementKey]: isChecked
    }));
  };

  const saveWPIns = async (event) => {
    let fields = new FormData(
      document.querySelector("form.eael-setup-wizard-form")
    );

    fields = new URLSearchParams(fields).toString();

    try {
      const response = await fetch(localize.ajaxurl, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "enable_wpins_process",
          security: localize.nonce,
          fields: fields,
        }),
      });

      const result = await response.json();

      if (result.success) {
      } else {
      }
    } catch (error) {}
  };

  const handleModalChange = (event) => {
    setModalTarget(event.currentTarget.getAttribute("data-target"));
  };

  const closeModal = () => {
    setModalTarget("");
  };

  const handleShowElements = (event) => {
    setShowElements(1);
  };

  const handleIntegrationSwitch = async (event, plugin, isTemplately = 0, setTemplatelyPlugin = '') => {
    setDisableSwitches(true);
    const isChecked = event.target.checked ?? 0;

    const isActionInstall = event.target.getAttribute("data-local_plugin_data") === "false";

    const action = isActionInstall
      ? "install"
      : isChecked
      ? "activate"
      : "deactivate";
    const identifier = isActionInstall ? plugin?.slug : plugin?.basename;

    let requestData = {
      action: `wpdeveloper_${action}_plugin`,
      security: localize.nonce,
    };
    requestData[isActionInstall ? "slug" : "basename"] = identifier;

    let label = '';
    let dataNext = '';

    if ( isTemplately ) {
        requestData['action'] = 'wpdeveloper_install_plugin';
        requestData['slug'] = plugin?.slug;
        requestData['promotype'] = 'quick-setup';
        label = event.currentTarget;
        dataNext = event.currentTarget.getAttribute("data-next");
        if ( plugin?.local_plugin_data ) {
          setActiveTab(dataNext);
          return;
        }
    } else {
      label = event.target
      .closest(".eael-integration-footer")
      .querySelector(".toggle-label")
    }

    if (label) {
      label.textContent = "Processing...";

      try {
        const response = await fetch(localize.ajaxurl, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams(requestData).toString(),
        });

        const result = await response.json();

        if (result.success) {
          if( isTemplately ) {
            label.textContent = 'Next';
            setActiveTab(dataNext);

            setTemplatelyPlugin((prevPlugin) => {
              return {
                ...prevPlugin,
                local_plugin_data: true,
              };
            });

          } else {
            label.textContent = isChecked ? "Deactivate" : "Activate";
          }

          if (isActionInstall) {
            setPluginList((prevList) =>
              prevList.map((p) =>
                p.slug === plugin.slug ? { ...p, local_plugin_data: true } : p
              )
            );
          }

        } else {
          if( isTemplately ) {
            setActiveTab(dataNext);
          } else {
            label.textContent = isChecked ? "Activate" : "Deactivate";
          }
        }
      } catch (error) {
        // if( isTemplately ) {
        //   setActiveTab(dataNext);
        // } else {
        //   label.textContent = isChecked ? "Activate" : "Deactivate";
        // }
      }
    }

    setDisableSwitches(false);
  };

  return (
    <>
      <section className="eael-onboard-main-wrapper eael-quick-setup-wizard-wrapper">
        <form
          className="eael-setup-wizard-form eael-quick-setup-wizard-form"
          method="post"
        >
          <div className="eael-menu-items">
            <MenuItems
              activeTab={activeTab}
              handleTabChange={handleTabChange}
            />
          </div>

          {!is_tracking_allowed && (
            <div
              className={`eael-setup-content eael-getting-started-content ${
                activeTab == "getting-started" ? "" : "eael-d-none"
              }`}
            >
              <GettingStartedContent
                activeTab={activeTab}
                handleTabChange={handleTabChange}
                modalTarget={modalTarget}
                handleModalChange={handleModalChange}
                closeModal={closeModal}
                emailAddress={emailAddress}
              />
            </div>
          )}

          <div
            className={`eael-setup-content eael-configuration-content ${
              activeTab === "configuration" ? "" : "eael-d-none"
            }`}
          >
            <ConfigurationContent
              activeTab={activeTab}
              handleTabChange={handleTabChange}
              isTrackingAllowed={is_tracking_allowed}
              selectedPreference={selectedPreference}
              handlePreferenceChange={handlePreferenceChange}
            />
          </div>

          <div
            className={`eael-setup-content eael-elements-content ${
              activeTab === "elements" ? "" : "eael-d-none"
            }`}
          >
            <ElementsContent
              activeTab={activeTab}
              handleTabChange={handleTabChange}
              showElements={showElements}
              handleShowElements={handleShowElements}
              selectedPreference={selectedPreference}
              checkedElements={checkedElements}
              handleElementCheck={handleElementCheck}
            />
          </div>

          <div
            className={`eael-setup-content eael-go-pro-content ${
              activeTab === "go-pro" ? "" : "eael-d-none"
            }`}
          >
            <GoProContent
              activeTab={activeTab}
              handleTabChange={handleTabChange}
            />
          </div>

          { hasPluginPromo ?
          <div
            className={`eael-setup-content eael-plugins-promo-content ${
              activeTab === "pluginspromo" ? "" : "eael-d-none"
            }`}
          >
            <PluginsPromo
              activeTab={activeTab}
              handleTabChange={handleTabChange}
            />
          </div>
          : '' }

          <div
            className={`eael-setup-content eael-integrations-content ${
              activeTab === "integrations" ? "" : "eael-d-none"
            }`}
          >
            <IntegrationContent
              activeTab={activeTab}
              handleTabChange={handleTabChange}
              modalTarget={modalTarget}
              handleModalChange={handleModalChange}
              closeModal={closeModal}
              handleIntegrationSwitch={handleIntegrationSwitch}
              disableSwitches={disableSwitches}
            />
          </div>

          {modalTarget === "modal" && (
            <div className="eael-modal-content">
              <ModalContent
                activeTab={activeTab}
                handleTabChange={handleTabChange}
                modalTarget={modalTarget}
                handleModalChange={handleModalChange}
                closeModal={closeModal}
              />
            </div>
          )}
        </form>
      </section>
    </>
  );
}

export default App;
