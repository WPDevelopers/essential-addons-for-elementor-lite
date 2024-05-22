import React, {useState} from 'react';

function ElementItem(props) {
    const eaData = localize.eael_dashboard.extensions.list[props.index],
        isProActivated = localize.eael_dashboard.is_eapro_activate,
        isDisabled = eaData.is_pro && !isProActivated,
        [checked, setChecked] = useState(!isDisabled && eaData.is_activate),
        changeHandler = (e) => {
            setChecked(() => {
                return e.target.checked;
            });
        };

    return (
        <>
            <div className="ea__content-items">
                <div className="ea__content-head">
                    <h5 className="toggle-label">{eaData.title}</h5>
                    <label className="toggle-wrap">
                        <input type="checkbox" checked={checked} disabled={isDisabled} onChange={changeHandler}/>
                        <span className={eaData.is_pro ? 'slider pro' : 'slider'}></span>
                    </label>
                </div>
                <div className="ea__content-footer">
                    {eaData.promotion ?
                        <span className={"content-btn " + eaData.promotion}>{eaData.promotion}</span> : ''}
                    <div className="content-icons">
                        <i className="eaicon ea-docs"></i>
                        <i className="eaicon ea-link-2"></i>
                    </div>
                </div>
            </div>
        </>
    );
}

export default ElementItem;