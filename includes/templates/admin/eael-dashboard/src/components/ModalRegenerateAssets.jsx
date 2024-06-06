import {useEffect} from "react";
import consumer from "../context";

function ModalRegenerateAssets() {
    const eaData = localize.eael_dashboard,
        {eaState, eaDispatch} = consumer();

    useEffect(() => {
        if (eaState.modalRegenerateAssets === 'open') {
            setTimeout(() => {
                eaDispatch({type: 'CLOSE_MODAL'});
            }, 2000);
        }
    }, [eaState.modalRegenerateAssets]);

    return (
        <>
            <section className="ea__modal-wrapper">
                <div className="ea__modal-content-wrapper">
                    <div className="ea__modal-body">
                        <div className='regenerated-wrapper'>
                            <h4>Assets Regenerated!</h4>
                            <p>Essential Addons styles & scripts are saved in Uploads folder. This option will clear all
                                those generated files.</p>
                            <img src={eaData.reactPath + "images/regenerate-modal.png"} alt="Regenerated Img"/>
                        </div>
                    </div>
                </div>
            </section>
        </>
    );
}

export default ModalRegenerateAssets;