import { act, useState } from "react";
import MenuItems from "./MenuItems.jsx";
import GettingStartedContent from "./GettingStartedContent.jsx";
import ConfigurationContent from "./ConfigurationContent.jsx";
import ElementsContent from "./ElementsContent.jsx";
import GoProContent from "./GoProContent.jsx";
import TemplatelyContent from "./TemplatelyContent.jsx";
import IntegrationContent from "./IntegrationContent.jsx";
import ModalContent from "./ModalContent.jsx";

function App() {
  const [activeTab, setActiveTab] = useState('getting-started');
  let eaelQuickSetup = localize.eael_quick_setup_data;

  const handleTabChange = (event) => {
    setActiveTab(event.currentTarget.getAttribute('data-next'));
  };

  return (
    <>
      <section className="eael-onboard-main-wrapper eael-quick-setup-wizard-wrapper">
        <form
          className="eael-setup-wizard-form eael-quick-setup-wizard-form"
          method="post"
        >
          <div className="eael-menu-items">
            <MenuItems activeTab={activeTab}/>
          </div>

          <div className={`eael-setup-content eael-getting-started-content ${activeTab == 'getting-started' ? '' : 'eael-d-none'}`}>
            <GettingStartedContent activeTab={activeTab} handleTabChange={handleTabChange}/>
          </div>

          <div className={`eael-setup-content eael-configuration-content ${activeTab === 'configuration' ? '' : 'eael-d-none'}`}>
            <ConfigurationContent />
          </div>

          <div className={`eael-setup-content eael-elements-content ${activeTab === 'elements' ? '' : 'eael-d-none'}`}>
            <ElementsContent />
          </div>

          <div className={`eael-setup-content eael-go-pro-content ${activeTab === 'go-pro' ? '' : 'eael-d-none'}`}>
            <GoProContent />
          </div>

          <div className={`eael-setup-content eael-templately-content ${activeTab === 'templately' ? '' : 'eael-d-none'}`}>
            <TemplatelyContent />
          </div>

          <div className={`eael-setup-content eael-integrations-content ${activeTab === 'integrations' ? '' : 'eael-d-none'}`}>
            <IntegrationContent />
          </div>

          <div className={`eael-modal-content ${activeTab === 'modal' ? '' : 'eael-d-none'}`}>
            <ModalContent />
          </div>
        </form>
      </section>
    </>
  );
}

export default App;
