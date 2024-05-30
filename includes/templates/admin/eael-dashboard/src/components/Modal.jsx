import consumer from "../context/index.js";

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
                        <h5>Advanced Google Map</h5>
                    </div>
                    <div className="ea__modal-body">
                        <div className="flex items-center gap-2">
                            <img src={localize.eael_dashboard.reactPath + 'images/map.svg'} alt="mapLogo"/>
                            <h4>Google Map API Key</h4>
                        </div>
                        <div>
                            <label className="mb-2">Set API Key</label>
                            <input className="input-name" type="text" placeholder="API Key"/>
                            <a className="ea__api-link" href="#">To configure the API Keys, check out this doc</a>
                        </div>
                        <img className="ea__modal-map-img"
                             src={localize.eael_dashboard.reactPath + 'images/map.png'} alt="mapImg"/>
                    </div>
                    <div className="ea__modal-footer flex flex-end">
                        <button className="ea__modal-btn">Save API</button>
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