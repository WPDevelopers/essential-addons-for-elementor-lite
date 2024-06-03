import consumer from "../context/index.js";
import ModalStyleOne from "./ModalStyleOne.jsx";
import ModalStyleTwo from "./ModalStyleTwo.jsx";
import ModalStyleThree from "./ModalStyleThree.jsx";

function Modal() {
    const {eaState, eaDispatch} = consumer(),
        clickHandler = () => {
            eaDispatch({type: 'CLOSE_MODAL', payload: {key: '', value: ''}});
        };

    return (
        <>
            <section className="ea__modal-wrapper">
                <div className="ea__modal-content-wrapper">
                    <div className="ea__modal-header">
                        <h5>{eaState.modalTitle}</h5>
                    </div>
                    <form action="#" method="post" className="ea__modal-body">
                        {eaState.modalID === 'loginRegisterSetting' && <ModalStyleThree/>}
                        {eaState.modalID === 'postDuplicatorSetting' && <ModalStyleTwo/>}
                        {['loginRegisterSetting', 'postDuplicatorSetting'].includes(eaState.modalID) ||
                            <ModalStyleOne/>}
                    </form>
                    <div className="ea__modal-footer flex justify-between items-center">
                        <a className="ea__api-link" href="#">To configure the API Keys, check out this doc</a>
                        <button className="ea__modal-btn">Save</button>
                    </div>
                    <div className="ea__modal-close-btn" onClick={clickHandler}>
                        <i className="ea-dash-icon ea-close"></i>
                    </div>
                </div>
            </section>
        </>
    );
}

export default Modal;