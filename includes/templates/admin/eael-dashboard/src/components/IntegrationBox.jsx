import React, {useState} from 'react';

function IntegrationBox(props) {
    const eaData = localize.eael_dashboard.integration_box.list[props.index],
        enableTxt = localize.eael_dashboard.integration_box.enable,
        disableTxt = localize.eael_dashboard.integration_box.disable,
        [checked, setChecked] = useState(eaData.status),
        integrationHandler = (e) => {
            setChecked(() => {
                return e.target.checked;
            });
        };

    return (
        <>
            <div className="ea__integration-item">
                <div className="ea__integration-header flex gap-2 items-center">
                    <img src={localize.eael_dashboard.reactPath + eaData.icon}/>
                    <h5>{eaData.heading}</h5>
                </div>
                <div className="ea__integration-footer">
                    <p>{eaData.content}</p>
                    <div className="integration-settings flex justify-between items-center">
                        <h5 className="toggle-label">{checked ? disableTxt : enableTxt}</h5>
                        <label className=" toggle-wrap">
                            <input type="checkbox" checked={checked} onChange={integrationHandler}/>
                            <span className="slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </>
    );
}

export default IntegrationBox;