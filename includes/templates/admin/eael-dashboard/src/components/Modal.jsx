import consumer from "../context/index.js";
import ModalStyleOne from "./ModalStyleOne.jsx";

function Modal(props) {
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
                    <div className="ea__modal-body">
                        <form action="#" method="post">
                            <ModalStyleOne/>
                        </form>
                    </div>
                    <div className="ea__modal-footer flex flex-end">
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