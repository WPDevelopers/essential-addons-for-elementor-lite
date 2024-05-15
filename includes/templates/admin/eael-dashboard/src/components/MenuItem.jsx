import React from 'react';

function MenuItem(props) {
    const setMenu = props.setmenu,
        label = props.item,
        icon = localize.eael_dashboard.menu[props.item];

    return (
        <>
            <div className={props.menu === label ? 'ea__sidebar-nav active' : 'ea__sidebar-nav'} onClick={() => setMenu(label)}>
                        <span className="ea__nav-icon">
                            <i className={icon + 'eaicon'}></i>
                        </span>
                <span className="ea__nav-text">{label}</span>
            </div>
        </>
    );
}

export default MenuItem;