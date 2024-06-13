import { __ } from "@wordpress/i18n";

function GettingStartedContent({activeTab, handleTabChange}) {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let getting_started_content = eaelQuickSetup?.getting_started_content;
  let youtube_promo_src = getting_started_content?.youtube_promo_src;
  
  return (
    <>
      <div className="eael-onboard-content-wrapper min-h-538">
        <div className="eael-onboard-content">
          <div className="eael-onboard-content-top">

            <a
              href="//www.youtube.com/watch?v=ZISSbnHo0rE&ab_channel=WPDeveloper"
              target="_blank"
            >
              <img
                src={youtube_promo_src}
                alt={__("Youtube Promo", "essential-addons-for-elementor-lite")}
              />
            </a>
            <h3>
              {__("Getting Started", "essential-addons-for-elementor-lite")}
            </h3>
            <p>
              {__(
                "Easily get started with this easy setup wizard and complete setting up your Knowledge Base.",
                "essential-addons-for-elementor-lite"
              )}
            </p>
          </div>
          <div className="eael-next-step-wrapper" id="eael-dashboard--wrapper">
            <p>
              {__(
                "By clicking this button I am allowing this app to collect my information.",
                "essential-addons-for-elementor-lite"
              )}
              <span className="collect-info eael-what-we-collect">
                {__("What We Collect?", "essential-addons-for-elementor-lite")}
              </span>
            </p>

            <input
              type="hidden"
              value="0"
              id="eael_user_email_address"
              name="eael_user_email_address"
            />

            <button
              className="primary-btn install-btn eael-setup-next-btn eael-user-email-address"
              type="button"
              data-next="configuration"
              onClick={handleTabChange}
            >
              {__(
                "Proceed to Next Step",
                "essential-addons-for-elementor-lite"
              )}
              <i className="ea-dash-icon ea-install"></i>
            </button>
            <span
              className="skip-item eael-setup-next-btn"
              type="button"
              data-next="configuration"
              onClick={handleTabChange}
            >
              {__("Skip This Step", "essential-addons-for-elementor-lite")}
            </span>
          </div>
        </div>
      </div>

      <section class="eael-modal-wrapper eael-d-none eael-what-we-collect-modal">
        <div class="eael-modal-content-wrapper eael-onboard-modal">
          <div class="">
            <h5>
              {__("What we collect?", "essential-addons-for-elementor-lite")}
            </h5>
            <p>
              {__(
                "We collect non-sensitive diagnostic data and plugin usage information. Your site URL, WordPress & PHP version, plugins & themes and email address to send you the discount coupon. This data lets us make sure this plugin always stays compatible with the most popular plugins and themes. No spam, we promise.",
                "essential-addons-for-elementor-lite"
              )}
            </p>
          </div>
          <div class="eael-modal-close-btn">
            <i class="ea-dash-icon ea-close"></i>
          </div>
        </div>
      </section>
    </>
  );
}

export default GettingStartedContent;
