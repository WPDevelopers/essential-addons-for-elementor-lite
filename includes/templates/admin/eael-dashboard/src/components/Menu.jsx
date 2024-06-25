import MenuItem from './MenuItem.jsx'
import consumer from "../context";

function Menu() {
    const eaData = localize.eael_dashboard.menu,
        isProActivated = localize.eael_dashboard.is_eapro_activate,
        {eaState} = consumer();

    return (
        <>
            <div
                className={eaState.menu === 'Elements' ? 'ea__sidebar-nav-list ea__elements-nav' : 'ea__sidebar-nav-list'}>
                <div className="nav-sticky">
                    {Object.keys(eaData).map((item, index) => {
                        if (item === 'go-premium' && isProActivated) {
                            return;
                        }

                        return <MenuItem item={item} key={index}/>
                    })}
                </div>
                <div></div>
            </div>
        </>
    );
}

export default Menu;