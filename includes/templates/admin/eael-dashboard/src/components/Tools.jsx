import consumer from "../context";
import {useRef} from "react";

function Tools() {
    const eaData = localize.eael_dashboard.tools,
        i18n = localize.eael_dashboard.i18n,
        selectRef = useRef(),
        {eaState, eaDispatch} = consumer(),
        saveHandler = () => {
            eaDispatch({type: 'BUTTON_LOADER', payload: 'tools'});
            setTimeout(eaDispatch, 500, {
                type: 'SAVE_TOOLS',
                payload: {key: eaData.box_3.name, value: selectRef.current.value}
            });
        },
        clickHandler = () => {
            eaDispatch({type: 'REGENERATE_ASSETS'});
        };

    return (
        <>
            <div className="ea__elements-nav-content">
                <div className='ea__tools-sticky'>
                    <div className="ea__tools-content-wrapper">
                        <div className="ea__connect-others flex gap-4 justify-between items-start">
                            <div className="ea__connect--info flex gap-4 flex-1">
                                <div className="ea__others-icon eaicon-1">
                                    <i className={"ea-dash-icon " + eaData.box_1.icon}></i>
                                </div>
                                <div>
                                    <h5>{eaData.box_1.heading}</h5>
                                    <p>{eaData.box_1.content}</p>
                                </div>
                            </div>
                            <button className="primary-btn changelog-btn"
                                    onClick={clickHandler}>{eaData.box_1.button.label}</button>
                        </div>
                        <div className="ea__connect-others flex gap-4 justify-between items-start">
                            <div className="ea__connect--info flex gap-4 flex-1">
                                <div className="ea__others-icon eaicon-1">
                                    <i className={"ea-dash-icon " + eaData.box_2.icon}></i>
                                </div>
                                <div>
                                    <h5>{eaData.box_2.heading}</h5>
                                    <p>{eaData.box_2.content}</p>
                                </div>
                            </div>
                            <a className="primary-btn changelog-btn" target="_blank"
                               href={eaData.box_2.button.url}>{eaData.box_2.button.label}</a>
                        </div>
                        <div className="ea__connect-others flex gap-6 justify-between items-start">
                            <label>{eaData.box_3.heading}</label>
                            <div className="flex-1">
                                <div className="select-option-external">
                                    <select name={eaData.box_3.name} defaultValue={eaData.box_3.value} id="select-option"
                                            className="form-select" ref={selectRef}>
                                        {Object.keys(eaData.box_3.methods).map((item, index) => {
                                            return <option value={item} key={index}>{eaData.box_3.methods[item]}</option>
                                        })}
                                    </select>
                                </div>
                                <span className="select-details">{eaData.box_3.content}</span>
                            </div>
                        </div>
                        <div className="flex flex-end">
                            <button className="primary-btn install-btn flex flex-end"
                                    onClick={saveHandler}>{i18n.save_settings} {eaState.btnLoader === 'tools' &&
                                <span className="eael_btn_loader"></span>}</button>
                        </div>
                    </div>
                </div>
                <div></div>
            </div>
        </>
    );
}

export default Tools;