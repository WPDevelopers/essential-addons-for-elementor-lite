import consumer from "../context/index.js";

function ModalStyleTwo() {
    const {eaState} = consumer(),
        eaData = localize.eael_dashboard.modal[eaState.modalID];

    return (
        <>
            <h4 className="mb-4">{eaData.title}</h4>
            <div className="select-option-wrapper">
                <select name={eaData.name} id="select-option" className="form-select">
                    <option value="">Select team member</option>
                    <option value="1">All</option>
                    <option value="2">Post</option>
                    <option value="3">Page</option>
                    <option value="2">e-landing-page</option>
                    <option value="3">Products</option>
                    <option value="3">Docs</option>
                </select>
            </div>
        </>
    );
}

export default ModalStyleTwo;