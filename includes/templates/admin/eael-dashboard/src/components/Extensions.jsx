import ElementItem from "./ElementItem.jsx";
import consumer from "../context";

function Extensions() {
    const eaData = localize.eael_dashboard.extensions,
        {eaState, eaDispatch} = consumer(),
        checked = eaState.extensionAll,
        i18n = localize.eael_dashboard.i18n,
        changeHandler = (e) => {
            eaDispatch({type: 'ON_CHANGE_ALL', payload: {key: 'extensionAll', value: e.target.checked}});
        };

    return (
        <>
            <div className="ea__elements-nav-content">
                <div className="ea__premimu-extensions-wrapper">
                    <div className="ea__contents mb-4">
                        <div className="flex items-center gap-2 justify-between mb-4">
                            <h3 className="ea__content-title title">{eaData.heading}</h3>
                            <div className="ea__enable-elements">
                                <div className="toggle-wrapper flex items-center gap-2">
                                    <h5>{i18n.enable_all}</h5>
                                    <label className="toggle-wrap">
                                        <input type="checkbox" checked={checked} onChange={changeHandler}/>
                                        <span className="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div className="ea__content-wrapper">
                            {Object.keys(eaData.list).map((item, index) => {
                                return <ElementItem index={item} key={index}/>
                            })}
                        </div>
                    </div>
                    <div className="ea__section-wrapper flex flex-end mb-5">
                        <button className="primary-btn install-btn flex flex-end mb-6">{i18n.save_settings}</button>
                    </div>
                </div>
            </div>
        </>
    );
}

export default Extensions;