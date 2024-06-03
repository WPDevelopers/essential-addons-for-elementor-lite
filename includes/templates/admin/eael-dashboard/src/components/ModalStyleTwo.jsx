import consumer from "../context/index.js";

function ModalStyleTwo() {
    const {eaState} = consumer(),
        eaData = localize.eael_dashboard.modal[eaState.modalID],
        selectedVal = eaState.modals[eaData.name];

    return (
        <>
            <h4 className="mb-4">{eaData.title}</h4>
            <div className="select-option-wrapper">
                <select name={eaData.name} id="select-option" className="form-select">
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