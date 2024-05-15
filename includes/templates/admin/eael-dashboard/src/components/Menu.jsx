import React from 'react';
import MenuItem from './MenuItem.jsx'

function Menu(props) {

    return (
        <>
            <div
                className={props.menu === 'elements' ? 'ea__sidebar-nav-list ea__elements-nav' : 'ea__sidebar-nav-list'}>
                <div className="nav-sticky">
                    {Object.keys(localize.eael_dashboard.menu).map((item) => {
                        return <MenuItem setmenu={props.setmenu} key={item} item={item} menu={props.menu}/>
                    })}
                </div>
                <div></div>
            </div>
        </>
    );
}

export default Menu;