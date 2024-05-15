import React from 'react';

function Tools() {
    return (
        <>
            <div className="ea__elements-nav-content">
                <div className="ea__tools-content-wrapper">
                    <div className="ea__connect-others flex gap-4 justify-between items-start">
                        <div className="flex gap-4 flex-1">
                            <div className="ea__others-icon eaicon-1">
                                <i className="eaicon ea-regenerate"></i>
                            </div>
                            <div>
                                <h5>Regenerate Assets</h5>
                                <p>Essential Addons styles & scripts are saved in Uploads folder. This
                                    option will clear all those generated files.</p>
                            </div>
                        </div>
                        <button className="primary-btn changelog-btn">Regenerate Assets</button>
                    </div>
                    <div className="ea__connect-others flex gap-4 justify-between items-start">
                        <div className="flex gap-4 flex-1">
                            <div className="ea__others-icon eaicon-1">
                                <i className="eaicon ea-settings"></i>
                            </div>
                            <div>
                                <h5>Assets Embed Method</h5>
                                <p>Configure the Essential Addons assets embed method. Keep it as default
                                    (recommended).</p>
                            </div>
                        </div>
                        <button className="primary-btn changelog-btn">CSS Print Method</button>
                    </div>
                    <div className="ea__connect-others flex gap-6 justify-between items-start">
                        <label>JS Print Method</label>
                        <div className="flex-1">
                            <div className="select-option-external">
                                <select name="select" id="select-option" className="form-select">
                                    <option value="">External file</option>
                                    <option value="1">Employees</option>
                                    <option value="2">Templates</option>
                                    <option value="3">Employees</option>
                                </select>
                            </div>
                            <span className="select-details">CSS Print Method is handled by Elementor Settings itself.
                                    Use External CSS Files for better
                                    performance (recommended).</span>
                        </div>
                    </div>
                    <div className="flex flex-end mb-5">
                        <button className="primary-btn install-btn flex flex-end mb-6">Save Settings</button>
                    </div>
                </div>
            </div>
        </>
    );
}

export default Tools;