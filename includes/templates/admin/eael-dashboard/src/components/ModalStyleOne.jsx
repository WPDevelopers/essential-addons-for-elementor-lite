import consumer from "../context/index.js";

function ModalStyleOne() {
    const {eaState, eaDispatch} = consumer(),
        eaData = localize.eael_dashboard.modal[eaState.modalID],
        apiKey = eaState.modals[eaData.name],
        changeHandler = (e) => {
            eaDispatch({type: 'MODAL_ON_CHANGE', payload: {key: eaData.name, value: e.target.value}});
        };

    return (
        <>
            <div className="flex items-center gap-2">
                { eaData.title_icon ? <img src={localize.eael_dashboard.reactPath + eaData.title_icon} alt="mapLogo"/> : '' }
                { eaData.title ? <h4>{eaData.title}</h4> : '' }
            </div>
            <div>
                <label className="mb-2">{eaData.label}</label>
                <input className="input-name" type="text" placeholder={ eaData.placeholder ? eaData.placeholder : '' } name={eaData.name} value={apiKey}
                       onChange={changeHandler}/>
                {eaData.link === undefined || <a className="ea__api-link" target="_blank" href={eaData.link.url}>{eaData.link.text}</a>}
            </div>
            { eaData.image ? 
            <img className="ea__modal-map-img" src={localize.eael_dashboard.reactPath + eaData.image} alt={eaData.title}/>
            : '' }</>
    );
}

export default ModalStyleOne;