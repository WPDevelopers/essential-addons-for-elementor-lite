function ModalStyleOne() {
    return (
        <>
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
                 src={localize.eael_dashboard.reactPath + 'images/map.png'} alt="mapImg"/></>
    );
}

export default ModalStyleOne;