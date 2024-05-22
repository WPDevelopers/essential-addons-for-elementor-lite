import MenuItem from './MenuItem.jsx'
import consumer from "../context";

function Menu() {
    const eaData = localize.eael_dashboard.menu,
        {eaState} = consumer();

    return (
        <>
            <div
                className={eaState.menu === 'Elements' ? 'ea__sidebar-nav-list ea__elements-nav' : 'ea__sidebar-nav-list'}>
                <div className="nav-sticky">
                    {Object.keys(eaData).map((item, index) => {
                        return <MenuItem key={index} item={item}/>
                    })}
                </div>
                <div></div>
            </div>
        </>
    );
}

export default Menu;