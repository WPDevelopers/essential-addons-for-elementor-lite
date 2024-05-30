function Modal(props) {
    return (
        <>
            <section className="ea__modal-wrapper">
                <div className="ea__modal-content-wrapper">
                    <div className="ea__modal-header">
                        <h5>Advanced Google Map</h5>
                    </div>
                    <div className="ea__modal-body">
                        <div className="flex items-center gap-2">
                            <img src="assets/images/map.svg" alt="mapLogo"/>
                            <h4>Google Map API Key</h4>
                        </div>
                        <div>
                            <label className="mb-2">Set API Key</label>
                            <input className="input-name" type="text" placeholder="API Key"/>
                            <a className="ea__api-link" href="#">To configure the API Keys, check out this doc</a>
                        </div>
                        <img className="ea__modal-map-img" src="assets/images/map.png" alt="mapImg"/>
                        <div>
                            <h4 className="mb-4">Google Map API Key</h4>
                            <div className="select-option-wrapper">
                                <select name="select" id="select-option" className="form-select">
                                    <option value="">Select team member</option>
                                    <option value="1">All</option>
                                    <option value="2">Post</option>
                                    <option value="3">Page</option>
                                    <option value="2">e-landing-page</option>
                                    <option value="3">Products</option>
                                    <option value="3">Docs</option>
                                </select>
                            </div>
                        </div>
                        <div className="ea__api-key-according">
                            <div className="ea__according-title">
                                <div className="flex justify-between items-center gap-2 mb-4">
                                <span className="flex gap-2 items-center">
                                    <img src="assets/images/recap.svg" alt="icon"/>
                                    <h4>reCAPTCHA v2</h4>
                                </span>
                                    <i className="ea-dash-icon ea-dropdown"></i>
                                </div>
                            </div>
                            <div className="ea__according-content flex flex-col gap-2">
                                <div className="flex gap-4 items-center">
                                    <label>Site Key:</label>
                                    <input className="input-name" type="text" placeholder="Set Api Key"/>
                                </div>
                                <div className="flex gap-4 items-center">
                                    <label>Site Secret:</label>
                                    <input className="input-name" type="text" placeholder="Set Api Key"/>
                                </div>
                                <div className="flex gap-4 items-center">
                                    <label>Language:</label>
                                    <input className="input-name" type="text" placeholder="Set Api Key"/>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div className="ea__modal-footer flex flex-end">
                        <button className="ea__modal-btn">Save API</button>
                    </div>
                    <div className="ea__modal-close-btn">
                        <i className="ea-dash-icon ea-close"></i>
                    </div>
                </div>
            </section>
        </>
    );
}

export default Modal;