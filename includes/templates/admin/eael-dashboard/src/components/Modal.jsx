import consumer from "../context/index.js";
import ModalStyleOne from "./ModalStyleOne.jsx";
import ModalStyleTwo from "./ModalStyleTwo.jsx";
import ModalStyleThree from "./ModalStyleThree.jsx";
import {useRef} from "react";

function Modal() {
    const {eaState, eaDispatch} = consumer(),
        formRef = useRef(),
        clickHandler = () => {
            eaDispatch({type: 'CLOSE_MODAL'});
        },
        submitHandler = (e) => {
            e.preventDefault();
            const formData = new FormData(formRef.current),
                inputData = {};

            formData.forEach((item, index) => {
                inputData[index] = item;
            });

            eaDispatch({type: 'SAVE_MODAL_DATA', payload: inputData});
        },
        eaData = localize.eael_dashboard.modal;

    return (
        <>
            <section className="ea__modal-wrapper">
                <form action="#" method="post" ref={formRef} onSubmit={submitHandler}
                      className="ea__modal-content-wrapper">
                    <div className="ea__modal-header">
                        <h5>{eaState.modalTitle}</h5>
                    </div>
                    <div className="ea__modal-body">
                        {eaState.modalID === 'loginRegisterSetting' && <ModalStyleThree/>}
                        {eaState.modalID === 'postDuplicatorSetting' && <ModalStyleTwo/>}
                        {['loginRegisterSetting', 'postDuplicatorSetting'].includes(eaState.modalID) ||
                            <ModalStyleOne/>}
                    </div>
                    <div className="ea__modal-footer flex items-center">
                        {eaState.modalID === 'loginRegisterSetting' &&
                            <a className="ea__api-link"
                               href={eaData[eaState.modalID].link.url}>{eaData[eaState.modalID].link.text}</a>}
                        <div className='flex flex-end flex-1'>
                            <button className="ea__modal-btn">Save</button>
                        </div>
                    </div>
                    <div className="ea__modal-close-btn" onClick={clickHandler}>
                        <i className="ea-dash-icon ea-close"></i>
                    </div>
                </form>
            </section>
        </>
    );
}

export default Modal;