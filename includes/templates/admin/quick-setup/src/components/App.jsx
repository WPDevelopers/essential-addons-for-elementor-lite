import { useState } from "react";
import MenuItems from "./MenuItems.jsx";
import GettingStartedContent from "./GettingStartedContent.jsx";
import ConfigurationContent from "./ConfigurationContent.jsx";
import ElementsContent from "./ElementsContent.jsx";
import GoProContent from "./TemplatelyContent.jsx";
import TemplatelyContent from "./TemplatelyContent.jsx";
import IntegrationContent from "./IntegrationContent.jsx";
import ModalContent from "./ModalContent.jsx";

function App() {
  const [count, setCount] = useState(0);

  let eaelQuickSetup = localize.eael_quick_setup_data;

  return (
    <>
      <h1>EA Quick Setup 2 </h1>
      <div className="card">
        <button onClick={() => setCount((count) => count + 1)}>
          count is {count}
        </button>
        <p>
          Edit <code>src/App.jsx</code> and save to test HMR
        </p>
      </div>
      <p className="read-the-docs">
        Click on the Vite and React logos to learn more
      </p>

      <section className="eael-onboard-main-wrapper eael-quick-setup-wizard-wrapper">
        <form
          className="eael-setup-wizard-form eael-quick-setup-wizard-form"
          method="post"
        >
          <div className="eael-menu-items">
            <MenuItems />
          </div>

          <div className="eael-setup-content eael-getting-started-content">
            <GettingStartedContent />
          </div>

          <div className="eael-setup-content eael-configuration-content eael-d-none">
            <ConfigurationContent />
          </div>

          <div className="eael-setup-content eael-elements-content eael-d-none">
            <ElementsContent />
          </div>

          <div className="eael-setup-content eael-go-pro-content eael-d-none">
            <GoProContent />
          </div>

          <div className="eael-setup-content eael-templately-content eael-d-none">
            <TemplatelyContent />
          </div>

          <div className="eael-setup-content eael-integrations-content eael-d-none">
            <IntegrationContent />
          </div>

          <div className="eael-modal-content eael-d-none">
            <ModalContent />
          </div>
        </form>
      </section>
    </>
  );
}

export default App;
