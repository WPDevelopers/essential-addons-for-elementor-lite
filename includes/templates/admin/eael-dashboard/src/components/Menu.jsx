import React from 'react';

function Menu(props) {
    const setMenu = props.setmenu;

    return (
        <>
            <div
                className={props.menu === 'elements' ? 'ea__sidebar-nav-list ea__elements-nav' : 'ea__sidebar-nav-list'}>
                <div className="nav-sticky">
                    <div className="ea__sidebar-nav active" onClick={() => setMenu('general')}>
                        <span className="ea__nav-icon">
                            <i className="eaicon ea-home"></i>
                        </span>
                        <span className="ea__nav-text">General</span>
                    </div>
                    <div className="ea__sidebar-nav" onClick={() => setMenu('elements')}>
                        <span className="ea__nav-icon">
                            <i className="eaicon ea-elements"></i>
                        </span>
                        <span className="ea__nav-text">Elements</span>
                    </div>
                    <div className="ea__sidebar-nav" onClick={() => setMenu('extensions')}>
                        <span className="ea__nav-icon">
                            <i className="eaicon ea-extensions"></i>
                        </span>
                        <span className="ea__nav-text">Extensions</span>
                    </div>
                    <div className="ea__sidebar-nav" onClick={() => setMenu('tools')}>
                        <span className="ea__nav-icon">
                            <i className="eaicon ea-tool"></i>
                        </span>
                        <span className="ea__nav-text">Tools</span>
                    </div>
                    <div className="ea__sidebar-nav" onClick={() => setMenu('integration')}>
                        <span className="ea__nav-icon">
                            <i className="eaicon ea-plug"></i>
                        </span>
                        <span className="ea__nav-text">Integration</span>
                    </div>
                    <div className="ea__sidebar-nav" onClick={() => setMenu('go-premium')}>
                        <span className="ea__nav-icon">
                            <i className="eaicon ea-lock"></i>
                        </span>
                        <span className="ea__nav-text">Go Premium</span>
                    </div>
                </div>
                <div></div>
            </div>
        </>
    );
}

export default Menu;