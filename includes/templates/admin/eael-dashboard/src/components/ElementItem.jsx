import consumer from "../context";

function ElementItem(props) {
    const eaData = props.source[props.index],
        isProActivated = localize.eael_dashboard.is_eapro_activate,
        isDisabled = eaData.is_pro && !isProActivated,
        {eaState, eaDispatch} = consumer(),
        checked = !isDisabled && eaState.elements[props.index],
        changeHandler = (e) => {
            eaDispatch({type: 'ON_CHANGE_ELEMENT', payload: {key: props.index, value: e.target.checked}});
        };

    return (
        <>
            <div className="ea__content-items">
                <div className="ea__content-head">
                    <h5 className="toggle-label">{eaData.title}</h5>
                    <label className="toggle-wrap">
                        <input type="checkbox" checked={checked} disabled={isDisabled}
                               onChange={changeHandler}/>
                        <span className={eaData.is_pro && !isProActivated ? 'slider pro' : 'slider'}></span>
                    </label>
                </div>
                <div className="ea__content-footer">
                    {eaData.promotion ?
                        <span className={"content-btn " + eaData.promotion}>{eaData.promotion}</span> : ''}
                    <div className="content-icons">
                        <i className="ea-dash-icon ea-docs"></i>
                        <i className="ea-dash-icon ea-link-2"></i>
                    </div>
                </div>
            </div>
        </>
    );
}

export default ElementItem;