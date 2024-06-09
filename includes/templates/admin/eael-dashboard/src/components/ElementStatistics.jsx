function ElementStatistics(props) {
    return (
        <>
            <div className="ea__connect-others">
                <div className="ea__elements-wrapper elements-1">
                    <i className="ea-elements ea-dash-icon"></i>
                    <h4>57</h4>
                    <span>Total Elements</span>
                </div>
                <div className="ea__elements-wrapper elements-2">
                    <i className="ea-active ea-dash-icon"></i>
                    <h4>28</h4>
                    <span>Active</span>
                </div>
                <div className="ea__elements-wrapper elements-3">
                    <i className="ea-inactive ea-dash-icon"></i>
                    <h4>21</h4>
                    <span>Inactive</span>
                </div>
            </div>
        </>
    );
}

export default ElementStatistics;