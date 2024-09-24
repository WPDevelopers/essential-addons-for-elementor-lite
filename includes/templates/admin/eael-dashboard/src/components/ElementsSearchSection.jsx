import ElementItem from "./ElementItem.jsx";
import consumer from "../context";
import Search404 from "./Search404.jsx";

function ElementsSearchSection(props) {
    const {eaState, eaDispatch} = consumer(),
        checked = eaState.searchAll || false,
        i18n = localize.eael_dashboard.i18n,
        changeHandler = (e) => {
            eaDispatch({type: 'ON_CHANGE_ALL', payload: {key: 'searchAll', value: e.target.checked}});
        };

    return (
        <>
            {eaState.search404 ? <Search404/> : (<div id="ID-search-section" className="ea__contents">
                <div className="flex items-center gap-2 justify-between mb-4">
                    <h3 className="ea__content-title">{i18n.search_result_for} {props.searchTerm}</h3>
                    <div className="ea__enable-elements">
                        <div className="toggle-wrapper flex items-center gap-2">
                            <h5>{checked ? i18n.disable_all : i18n.enable_all}</h5>
                            <label className="toggle-wrap">
                                <input type="checkbox" checked={checked} onChange={changeHandler}/>
                                <span className="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div className="ea__content-wrapper">
                    {Object.keys(eaState.search).map((item, index) => {
                        return <ElementItem source={eaState.search} index={item} key={index}/>
                    })}
                </div>
            </div>)}
        </>
    );
}

export default ElementsSearchSection;