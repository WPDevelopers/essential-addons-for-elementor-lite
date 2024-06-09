import consumer from "../context";

function ElementStatistics() {
    const eaData = localize.eael_dashboard,
        {eaState} = consumer(),
        statistics = {activated: 0, deactivated: eaData.is_eapro_activate ? 0 : -eaState.proElements.length};

    Object.keys(eaState.elements).map((item) => {
        if (eaState.elements[item]) {
            statistics.activated++;
        } else {
            statistics.deactivated++;
        }
    });

    return (
        <>
            <div className="ea__connect-others">
                <div className="ea__elements-wrapper elements-1">
                    <i className="ea-elements ea-dash-icon"></i>
                    <h4>{statistics.activated + statistics.deactivated}</h4>
                    <span>Total Elements</span>
                </div>
                <div className="ea__elements-wrapper elements-2">
                    <i className="ea-active ea-dash-icon"></i>
                    <h4>{statistics.activated}</h4>
                    <span>Active</span>
                </div>
                <div className="ea__elements-wrapper elements-3">
                    <i className="ea-inactive ea-dash-icon"></i>
                    <h4>{statistics.deactivated}</h4>
                    <span>Inactive</span>
                </div>
            </div>
        </>
    );
}

export default ElementStatistics;