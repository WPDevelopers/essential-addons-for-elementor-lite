import { React, useState, useRef } from "react";
import MenuItems from "./MenuItems.jsx";
import GettingStartedContent from "./GettingStartedContent.jsx";
import ConfigurationContent from "./ConfigurationContent.jsx";
import ElementsContent from "./ElementsContent.jsx";
import GoProContent from "./GoProContent.jsx";
import TemplatelyContent from "./TemplatelyContent.jsx";
import IntegrationContent from "./IntegrationContent.jsx";
import ModalContent from "./ModalContent.jsx";

function App() {
  let eaelQuickSetup = localize.eael_quick_setup_data;
  let is_tracking_allowed =
    eaelQuickSetup?.getting_started_content?.is_tracking_allowed;
  let currentTabValue = ! is_tracking_allowed ? 'getting-started' : 'configuration';

  const [activeTab, setActiveTab] = useState(currentTabValue);
  const [modalTarget, setModalTarget] = useState("");
  const [showElements, setShowElements] = useState(0);
  const [emailAddress, setEmailAddress] = useState(is_tracking_allowed);
  const [disableSwitches, setDisableSwitches] = useState(false);

  const handleTabChange = (event) => {
    setActiveTab(event.currentTarget.getAttribute("data-next"));

    if (event.currentTarget.classList.contains("eael-user-email-address")) {
      setEmailAddress("1");
      document.getElementById("eael_user_email_address").value = 1;
      saveWPIns();
    }
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

  const handleIntegrationSwitch = async (event, plugin, isTemplately = 0) => {
    setDisableSwitches(true);

    const isChecked = event.target.checked ?? 0;

    const isActionInstall =
      event.target.getAttribute("data-local_plugin_data") === "false";

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
        requestData['slug'] = 'templately';
        label = event.currentTarget;
        dataNext = event.currentTarget.getAttribute("data-next");
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
            label.textContent = 'Enabled Templates';
            setActiveTab(dataNext);
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

          <div
            className={`eael-setup-content eael-templately-content ${
              activeTab === "templately" ? "" : "eael-d-none"
            }`}
          >
            <TemplatelyContent
              activeTab={activeTab}
              handleTabChange={handleTabChange}
              handleIntegrationSwitch={handleIntegrationSwitch}
            />
          </div>

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
