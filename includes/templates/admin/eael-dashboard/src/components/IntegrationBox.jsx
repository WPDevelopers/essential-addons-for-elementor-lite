import consumer from "../context";

function IntegrationBox(props) {
    const eaData = localize.eael_dashboard.integration_box.list[props.index],
        enableTxt = localize.eael_dashboard.integration_box.enable,
        disableTxt = localize.eael_dashboard.integration_box.disable,
        {eaState, eaDispatch} = consumer(),
        checked = eaState.integrations[props.index],
        isLoading = eaState[props.index] === true,
        changeHandler = (e) => {
            eaDispatch({type: 'INTEGRATION_LOADER', payload: props.index});
            setTimeout(eaDispatch, 300, {
                type: 'ON_CHANGE_INTEGRATION',
                payload: {key: props.index, value: e.target.checked}
            })
        };

    return (
        <>
            <div className="ea__integration-item">
                <div className="ea__integration-header flex gap-2 items-center">
                    <img src={localize.eael_dashboard.reactPath + eaData.logo}/>
                    <h5>{eaData.title}</h5>
                </div>
                <div className="ea__integration-footer">
                    <p>{eaData.desc}</p>
                    <div className="integration-settings flex justify-between items-center">
                        <h5 className="toggle-label">{isLoading ? 'Processing...' : (checked ? disableTxt : enableTxt)}</h5>
                        <label className=" toggle-wrap">
                            <input type="checkbox" checked={checked} onChange={changeHandler}/>
                            <span className={isLoading ? 'slider ea-loader' : 'slider'}></span>
                        </label>
                    </div>
                </div>
            </div>
        </>
    );
}

export default IntegrationBox;