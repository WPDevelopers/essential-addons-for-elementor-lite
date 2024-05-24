function ElementsSubSection(props) {
    const eaData = localize.eael_dashboard.widgets[props.index],
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
                    <div className="ea__content-items">
                        <div className="ea__content-head">
                            <h5 className="toggle-label">Creative Button</h5>
                            <label className="toggle-wrap">
                                <input type="checkbox" checked="checked"/>
                                <span className="slider"></span>
                            </label>
                        </div>
                        <div className="ea__content-footer">
                            <span className="content-btn"></span>
                            <div className="content-icons">
                                <i className="eaicon ea-docs"></i>
                                <i className="eaicon ea-link-2"></i>
                                <i className="eaicon ea-settings"></i>
                            </div>
                        </div>
                    </div>
                    <div className="ea__content-items">
                        <div className="ea__content-head">
                            <h5 className="toggle-label">Team Member</h5>
                            <label className="toggle-wrap">
                                <input type="checkbox" checked="checked"/>
                                <span className="slider"></span>
                            </label>
                        </div>
                        <div className="ea__content-footer">
                            <span className="content-btn update">update</span>
                            <div className="content-icons">
                                <i className="eaicon ea-docs"></i>
                                <i className="eaicon ea-link-2"></i>
                                <i className="eaicon ea-settings"></i>
                            </div>
                        </div>
                    </div>
                    <div className="ea__content-items">
                        <div className="ea__content-head">
                            <h5 className="toggle-label">Feature List</h5>
                            <label className="toggle-wrap">
                                <input type="checkbox" checked="checked"/>
                                <span className="slider"></span>
                            </label>
                        </div>
                        <div className="ea__content-footer">
                            <span className="content-btn popular">popular</span>
                            <div className="content-icons">
                                <i className="eaicon ea-docs"></i>
                                <i className="eaicon ea-link-2"></i>
                            </div>
                        </div>
                    </div>
                    <div className="ea__content-items">
                        <div className="ea__content-head">
                            <h5 className="toggle-label">Advanced Menu</h5>
                            <label className="toggle-wrap">
                                <input type="checkbox" checked="checked"/>
                                <span className="slider"></span>
                            </label>
                        </div>
                        <div className="ea__content-footer">
                            <span className="content-btn new">new</span>
                            <div className="content-icons">
                                <i className="eaicon ea-docs"></i>
                                <i className="eaicon ea-link-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

export default ElementsSubSection;