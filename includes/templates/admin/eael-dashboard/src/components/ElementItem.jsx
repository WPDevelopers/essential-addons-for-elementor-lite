import React from 'react';

function ElementItem(props) {
    return (
        <>
            <div className="ea__content-items">
                <div className="ea__content-head">
                    <h5 className="toggle-label">Particles</h5>
                    <label className="toggle-wrap">
                        <input type="checkbox" checked="checked"/>
                        <span className="slider pro"></span>
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
        </>
    );
}

export default ElementItem;