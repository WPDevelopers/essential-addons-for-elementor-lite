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
  const [activeTab, setActiveTab] = useState("getting-started");
  const [modalTarget, setModalTarget] = useState("");
  const [showElements, setShowElements] = useState(0);
  let eaelQuickSetup = localize.eael_quick_setup_data;

  const handleTabChange = (event) => {
    setActiveTab(event.currentTarget.getAttribute("data-next"));
  };

  const handleModalChange = (event) => {
    console.log(event.currentTarget);
    console.log(event.currentTarget.getAttribute("data-target"));
    setModalTarget(event.currentTarget.getAttribute("data-target"));
    console.log(modalTarget);
  };

  const closeModal = () => {
    setModalTarget("");
  };

  const handleShowElements = (event) => {
    setShowElements(1);
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

          {activeTab === "getting-started" && (
            <div className="eael-setup-content eael-getting-started-content">
              <GettingStartedContent
                activeTab={activeTab}
                handleTabChange={handleTabChange}
                modalTarget={modalTarget}
                handleModalChange={handleModalChange}
                closeModal={closeModal}
              />
            </div>
          )}

          {activeTab === "configuration" && (
            <div className="eael-setup-content eael-configuration-content">
              <ConfigurationContent
                activeTab={activeTab}
                handleTabChange={handleTabChange}
              />
            </div>
          )}

          {activeTab === "elements" && (
            <div className="eael-setup-content eael-elements-content">
              <ElementsContent
                activeTab={activeTab}
                handleTabChange={handleTabChange}
                showElements={showElements}
                handleShowElements={handleShowElements}
              />
            </div>
          )}

          {activeTab === "go-pro" && (
            <div className="eael-setup-content eael-go-pro-content">
              <GoProContent
                activeTab={activeTab}
                handleTabChange={handleTabChange}
              />
            </div>
          )}

          {activeTab === "templately" && (
            <div className="eael-setup-content eael-templately-content">
              <TemplatelyContent
                activeTab={activeTab}
                handleTabChange={handleTabChange}
              />
            </div>
          )}

          {activeTab === "integrations" && (
            <div className="eael-setup-content eael-integrations-content">
              <IntegrationContent
                activeTab={activeTab}
                handleTabChange={handleTabChange}
                modalTarget={modalTarget}
                handleModalChange={handleModalChange}
                closeModal={closeModal}
              />
            </div>
          )}

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
