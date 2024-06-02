function ModalStyleThree() {
    return (
        <>
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
        </>
    );
}

export default ModalStyleThree;