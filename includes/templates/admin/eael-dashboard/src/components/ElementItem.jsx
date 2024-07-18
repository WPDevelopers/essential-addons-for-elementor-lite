import consumer from "../context";

function ElementItem(props) {
    const eaData = props.source[props.index],
        isProActivated = localize.eael_dashboard.is_eapro_activate,
        isDisabled = eaData.is_pro && !isProActivated,
        {eaState, eaDispatch} = consumer(),
        checked = !isDisabled && eaState.elements[props.index],
        changeHandler = (e) => {
            eaDispatch({type: 'ON_CHANGE_ELEMENT', payload: {key: props.index, value: e.target.checked}});
        },
        clickHandler = () => {
            eaDispatch({type: 'OPEN_MODAL', payload: {key: eaData.setting.id, title: eaData.title}});
        },
        goProModal = () => {
            eaDispatch({type: 'GO_PRO_MODAL'});
        };

    return (
        <>
            <div className="ea__content-items">
                <div className="ea__content-head">
                    <h5 className="eael-toggle-label">{eaData.title}</h5>
                    <label className="toggle-wrap" onClick={eaData.is_pro && !isProActivated ? goProModal : undefined}>
                        <input type="checkbox" checked={checked} disabled={isDisabled} onChange={changeHandler}/>
                        <span className={eaData.is_pro && !isProActivated ? 'slider pro' : 'slider'}></span>
                    </label>
                </div>
                <div className={eaData.promotion ? "ea__content-footer" : "ea__content-footer ea-no-label"}>
                    {eaData.promotion ?
                        <span className={"content-btn " + eaData.promotion}>{eaData.promotion}</span> : ''}
                    <div className="content-icons">
                        <a href={eaData.doc_link} target="_blank"><i className="ea-dash-icon ea-docs"></i></a>
                        <a href={eaData.demo_link} target="_blank"><i className="ea-dash-icon ea-link-2"></i></a>
                        {eaData.setting?.link !== undefined &&
                            <a href={eaData.setting?.link.replace("&#038;", "&")} target="_blank"><i
                                className="ea-dash-icon ea-settings"></i></a>}
                        {eaData.setting?.id ? <i className="ea-dash-icon ea-settings" onClick={clickHandler}></i> : ''}
                    </div>
                </div>
            </div>
        </>
    );
}

export default ElementItem;