import { useState } from "react";
import { __ } from "@wordpress/i18n";

function ModalContent() {
  let eaelQuickSetup = localize?.eael_quick_setup_data;
  let modal_content = eaelQuickSetup?.modal_content;
  let success_2_src = modal_content?.success_2_src;

  return (
    <>
		<section className="eael-modal-wrapper">
            <div className="eael-modal-content-wrapper eael-onboard-modal">
                <div className="">
                    <div className="congrats--wrapper">
                        <h6>{__( 'You are done!', 'essential-addons-for-elementor-lite' )}</h6>
                        <h4 className="congrats--title">{__( 'Congratulations!', 'essential-addons-for-elementor-lite' )}</h4>
						            <img className="eael-modal-map-img" src={success_2_src} alt={__( 'Success Image', 'essential-addons-for-elementor-lite' )} />
                    </div>
                </div>
                <div className="eael-modal-close-btn">
                    <i className="ea-dash-icon ea-close"></i>
                </div>
            </div>
        </section>
    </>
  );
}

export default ModalContent;