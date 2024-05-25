import ElementItem from "./ElementItem.jsx";
import consumer from "../context";

function ElementsSubSection(props) {
    const eaData = localize.eael_dashboard.widgets[props.index],
        {eaState, eaDispatch} = consumer(),
        i18n = localize.eael_dashboard.i18n;

    return (
        <>
            <div id={"ID-" + props.index} className="ea__contents">
                <div className="flex items-center gap-2 justify-between mb-4">
                    <h3 className="ea__content-title">{eaData.title}</h3>
                    <div className="ea__enable-elements">
                        <div className="toggle-wrapper flex items-center gap-2">
                            <h5>{i18n.enable_all}</h5>
                            <label className="toggle-wrap">
                                <input type="checkbox" checked="checked"/>
                                <span className="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div className="ea__content-wrapper">
                    {Object.keys(eaData.elements).map((item, index)=> {
                        return <ElementItem source={eaData.elements} index={item} key={index}/>
                    })}
                </div>
            </div>
        </>
    );
}

export default ElementsSubSection;