import { useState } from "react";
import { __ } from "@wordpress/i18n";

function TemplatelyContent() {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let templately_content = eaelQuickSetup?.templately_content;

  return (
    <>
		<div className="eael-onboard-content-wrapper eael-onboard-templately mb-4 min-h-538">
      <div className="eael-general-content-item templates flex justify-between items-center gap-5">
        <div className="templates-content">
          <h2>
            <span className="title-color-2">{__('5000+', 'essential-addons-for-elementor-lite')}</span> {__('Ready Templates', 'essential-addons-for-elementor-lite')}
          </h2>
          <p className="mb-10">
            {__('Get Access to amazing features and boost your Elementor page building experience with Templately', 'essential-addons-for-elementor-lite')}
          </p>
          <div className="eael-templately-details flex flex-col gap-4">
            <div className="eael-content-details flex gap-3 items-center">
              <span>
                <img src={templately_content?.templately_icon_1_src} alt={__('Templately Icon 1', 'essential-addons-for-elementor-lite')} />
              </span>
              {__('Stunning, Ready Website Templates', 'essential-addons-for-elementor-lite')}
            </div>
            <div className="eael-content-details flex gap-3 items-center">
              <span>
                <img src={templately_content?.templately_icon_2_src} alt={__('Templately Icon 2', 'essential-addons-for-elementor-lite')} />
              </span>
              {__('Add Team Members & Collaborate', 'essential-addons-for-elementor-lite')}
            </div>
            <div className="eael-content-details flex gap-3 items-center">
              <span>
                <img src={templately_content?.templately_icon_3_src} alt={__('Templately Icon 3', 'essential-addons-for-elementor-lite')} />
              </span>
              {__('Design With MyCloud Storage Space', 'essential-addons-for-elementor-lite')}
            </div>
            <div className="eael-content-details flex gap-4 items-center">
              <span>
                <img src={templately_content?.templately_icon_4_src} alt={__('Templately Icon 4', 'essential-addons-for-elementor-lite')} />
              </span>
              {__('Cloud With Templately WorkSpace', 'essential-addons-for-elementor-lite')}
            </div>
          </div>
        </div>
        <div className="templates-img">
          <img src={templately_content?.templately_promo_src} alt={__('Templately Promo', 'essential-addons-for-elementor-lite')} />
        </div>
      </div>
      <div className="eael-section-wrapper flex flex-end gap-4">
        <button className="previous-btn flex gap-2 items-center eael-setup-next-btn" data-next="integrations">
          <i className="ea-dash-icon ea-left-arrow-long"></i>
          {__('Skip', 'essential-addons-for-elementor-lite')}
        </button>
        <button
          className="primary-btn install-btn flex gap-2 items-center eael-setup-next-btn eael-quick-setup-next-button wpdeveloper-plugin-installer"
          data-next="integrations"
          data-action="install"
          data-slug="templately"
        >
          {__('Enable Templates', 'essential-addons-for-elementor-lite')}
        </button>
      </div>
    </div>
    </>
  );
}

export default TemplatelyContent;