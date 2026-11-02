import { __ } from "@wordpress/i18n";

function GettingStartedContent({activeTab, handleTabChange, modalTarget, handleModalChange, closeModal, emailAddress}) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let getting_started_content = eaelQuickSetup?.getting_started_content;
  let youtube_promo_src = getting_started_content?.youtube_promo_src;  

  return (
    <>
      <div className="eael-onboard-content-wrapper min-h-538">
        <div className="eael-onboard-content">
          <div className="eael-onboard-content-top">

            <a
              href="https://youtu.be/XPKZzYJcjZU"
              target="_blank"
            >
              <img
                src={youtube_promo_src}
                alt={__("Youtube Promo", "essential-addons-for-elementor-lite")}
              />
            </a>
            <h3>
              {__("Welcome to Essential Addons for Elementor!", "essential-addons-for-elementor-lite")}
            </h3>
            <p>
              {__(
                "Follow the simple steps in this quick setup wizard to start building with Elementor!",
                "essential-addons-for-elementor-lite"
              )}
            </p>
          </div>
          <div className="eael-next-step-wrapper" id="eael-dashboard--wrapper">
            <p>
              {__(
                "Help us improve Essential Addons by sharing usage data with WPDeveloper. We never track your site visitors or content.",
                "essential-addons-for-elementor-lite"
              )}{" "}
              <span className="collect-info eael-what-we-collect" onClick={handleModalChange} data-target="what-we-collect">
                {__("Find out what we collect", "essential-addons-for-elementor-lite")}
              </span>
            </p>

            <input
              type="hidden"
              value={emailAddress}
              id="eael_user_email_address"
              name="eael_user_email_address"
            />

            {/* Two equal-weight choices. Only "Allow & Continue" carries the
                eael-user-email-address class that App.jsx treats as consent. */}
            <div className="eael-consent-actions">
              <button
                className="primary-btn eael-setup-next-btn eael-user-email-address"
                type="button"
                data-next="configuration"
                onClick={handleTabChange}
              >
                {__("Allow & Continue", "essential-addons-for-elementor-lite")}
              </button>
              <button
                className="primary-btn eael-setup-next-btn"
                type="button"
                data-next="configuration"
                onClick={handleTabChange}
              >
                {__("Skip and Continue", "essential-addons-for-elementor-lite")}
              </button>
            </div>
          </div>
        </div>
      </div>

      <section class={`eael-modal-wrapper ${ modalTarget == 'what-we-collect' ?  '' : 'eael-d-none' } eael-what-we-collect-modal`}>
        <div class="eael-modal-content-wrapper eael-onboard-modal">
          <div class="">
            <h5>
              {__("What We Collect?", "essential-addons-for-elementor-lite")}
            </h5>
            <p>
              {__("We only collect usage data if you choose to share it.", "essential-addons-for-elementor-lite")}
            </p>
            <p>
              {__("Here is what is included:", "essential-addons-for-elementor-lite")}
            </p>
            <ul className="eael-collect-list">
              <li>
                <strong>{__("System Specs:", "essential-addons-for-elementor-lite")}</strong>{" "}
                {__("WordPress, PHP, and server versions, plus site language and charset.", "essential-addons-for-elementor-lite")}
              </li>
              <li>
                <strong>{__("Plugin Data:", "essential-addons-for-elementor-lite")}</strong>{" "}
                {__("Active plugins, active theme, total count of inactive plugins, and which Essential Addons widgets you use.", "essential-addons-for-elementor-lite")}
              </li>
              <li>
                <strong>{__("Site Info & Contact:", "essential-addons-for-elementor-lite")}</strong>{" "}
                {__("Site URL, site name, and admin email for the coupon.", "essential-addons-for-elementor-lite")}
              </li>
            </ul>
            <p>
              <strong>{__("What we NEVER collect:", "essential-addons-for-elementor-lite")}</strong>{" "}
              {__("Your website content, visitor data, or the names of your inactive plugins. Zero spam promise.", "essential-addons-for-elementor-lite")}
            </p>
          </div>
          <div class="eael-modal-close-btn" onClick={closeModal}>
            <i class="ea-dash-icon ea-close"></i>
          </div>
        </div>
      </section>
    </>
  );
}

export default GettingStartedContent;
