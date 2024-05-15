import React from 'react';

function Elements() {
    return (
        <>
            <div className="ea__elements-nav-content">
                    <div className="ea__content-header sticky">
                        <div className="ea__content-info flex justify-between items-center gap-2">
                            <div className="ea__widget-elements flex items-center">
                                <h4>Elements</h4>
                                <div className="search--widget flex">
                                    <input className="input-name" type="search" placeholder="Search by name "/>
                                    <div className="select-option-wrapper">
                                        <select name="select" id="select-option" className="form-select">
                                            <option value="">All Widgets</option>
                                            <option value="1">Employees</option>
                                            <option value="2">Templates</option>
                                            <option value="3">Employees</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div className="ea__enable-elements">
                                <div className="toggle-wrapper flex items-center gap-2">
                                    <h5>Enable all Elements</h5>
                                    <label className="toggle-wrap">
                                        <input type="checkbox" checked="checked"/>
                                        <span className="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div className="ea__content-icon flex">
                            <a className="ea__icon-wrapper" href="#Content">
                                <i className="eaicon ea-content">
                                    <span className="ea__tooltip">Content Elements</span>
                                </i>
                            </a>
                            <a className="ea__icon-wrapper" href="#Dynamic">
                                <i className="eaicon ea-notes-2">
                                    <span className="ea__tooltip">Dynamic Content Elements</span>
                                </i>
                            </a>
                            <a className="ea__icon-wrapper active" href="#Creative">
                                <i className="eaicon ea-light">
                                    <span className="ea__tooltip">Creative Elements</span>
                                </i>
                            </a>
                            <a className="ea__icon-wrapper" href="#Marketing">
                                <i className="eaicon ea-marketing">
                                    <span className="ea__tooltip">Marketing Elements</span>
                                </i>
                            </a>
                            <a className="ea__icon-wrapper" href="#Form">
                                <i className="eaicon ea-notes">
                                    <span className="ea__tooltip">Form Styler Elements</span>
                                </i>
                            </a>
                            <a className="ea__icon-wrapper" href="#Social">
                                <i className="eaicon ea-share-fill">
                                    <span className="ea__tooltip">Social Feed Elements</span>
                                </i>
                            </a>
                            <a className="ea__icon-wrapper" href="#LearnDash">
                                <i className="eaicon ea-leardash">
                                    <span className="ea__tooltip">LearnDash Elements</span>
                                </i>
                            </a>
                            <a className="ea__icon-wrapper" href="#Documentation">
                                <i className="eaicon ea-docs-fill">
                                    <span className="ea__tooltip">Documentation Elements</span>
                                </i>
                            </a>
                            <a className="ea__icon-wrapper" href="#WooCommerce">
                                <i className="eaicon ea-cart">
                                    <span className="ea__tooltip">WooCommerce Elements</span>
                                </i>
                            </a>
                        </div>
                    </div>
                    <div className="ea__content-elements-wrapper">
                        <div id="Content" className="ea__contents">
                            <div className="flex items-center gap-2 justify-between mb-4">
                                <h3 className="ea__content-title">Content Elements</h3>
                                <div className="ea__enable-elements">
                                    <div className="toggle-wrapper flex items-center gap-2">
                                        <h5>Enable all</h5>
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
                        <div id="Dynamic" className="ea__contents">
                            <div className="flex items-center gap-2 justify-between mb-4">
                                <h3 className="ea__content-title">Dynamic Content Elements</h3>
                                <div className="ea__enable-elements">
                                    <div className="toggle-wrapper flex items-center gap-2">
                                        <h5>Enable all</h5>
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
                        <div id="Creative" className="ea__contents">
                            <div className="flex items-center gap-2 justify-between mb-4">
                                <h3 className="ea__content-title">Creative Elements</h3>
                                <div className="ea__enable-elements">
                                    <div className="toggle-wrapper flex items-center gap-2">
                                        <h5>Enable all</h5>
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
                        <div id="Marketing" className="ea__contents">
                            <div className="flex items-center gap-2 justify-between mb-4">
                                <h3 className="ea__content-title">Marketing Elements</h3>
                                <div className="ea__enable-elements">
                                    <div className="toggle-wrapper flex items-center gap-2">
                                        <h5>Enable all</h5>
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
                        <div id="Form" className="ea__contents">
                            <div className="flex items-center gap-2 justify-between mb-4">
                                <h3 className="ea__content-title">Form Styler Elements</h3>
                                <div className="ea__enable-elements">
                                    <div className="toggle-wrapper flex items-center gap-2">
                                        <h5>Enable all</h5>
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
                        <div id="Social" className="ea__contents">
                            <div className="flex items-center gap-2 justify-between mb-4">
                                <h3 className="ea__content-title">Social Feed Elements</h3>
                                <div className="ea__enable-elements">
                                    <div className="toggle-wrapper flex items-center gap-2">
                                        <h5>Enable all</h5>
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
                        <div id="LearnDash" className="ea__contents">
                            <div className="flex items-center gap-2 justify-between mb-4">
                                <h3 className="ea__content-title">LearnDash Elements</h3>
                                <div className="ea__enable-elements">
                                    <div className="toggle-wrapper flex items-center gap-2">
                                        <h5>Enable all</h5>
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
                        <div id="Documentation" className="ea__contents">
                            <div className="flex items-center gap-2 justify-between mb-4">
                                <h3 className="ea__content-title">Documentation Elements</h3>
                                <div className="ea__enable-elements">
                                    <div className="toggle-wrapper flex items-center gap-2">
                                        <h5>Enable all</h5>
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
                        <div id="WooCommerce" className="ea__contents">
                            <div className="flex items-center gap-2 justify-between mb-4">
                                <h3 className="ea__content-title">WooCommerce Elements</h3>
                                <div className="ea__enable-elements">
                                    <div className="toggle-wrapper flex items-center gap-2">
                                        <h5>Enable all</h5>
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
                        <div className="flex flex-end mb-6">
                            <button className="primary-btn install-btn">Save Settings</button>
                        </div>
                    </div>
                </div>
        </>
    );
}

export default Elements;