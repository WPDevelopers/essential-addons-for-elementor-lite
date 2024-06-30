import consumer from "../context/index.js";

function ModalStyleTwo() {
    const {eaState, eaDispatch} = consumer(),
        eaData = localize.eael_dashboard.modal[eaState.modalID],
        selectedVal = eaState.modals[eaData.name],
        changeHandler = (e) => {
            eaDispatch({type: 'MODAL_ON_CHANGE', payload: {key: eaData.name, value: e.target.value}});
        };

    return (
        <>
            <h4>{eaData.title}</h4>
            <div className="select-option-wrapper">
                <select name={eaData.name} onChange={changeHandler} id="select-option" className="form-select">
                    <option value="all">All</option>
                    {Object.keys(eaData.options).map((item, index) => {
                        return <option value={item} key={index}
                                       selected={selectedVal === item}>{eaData.options[item]}</option>;
                    })}
                </select>
            </div>
        </>
    );
}

export default ModalStyleTwo;