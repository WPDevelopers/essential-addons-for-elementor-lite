import consumer from "../context";


function MenuItem(props) {
    const eaData = localize.eael_dashboard.menu[props.item],
        label = eaData.label,
        icon = eaData.icon,
        {eaState, eaDispatch} = consumer(),
        changeHandler = () => {
            eaDispatch({type: 'SET_MENU', payload: props.item});
            window.dispatchEvent(new Event('resize'));
        };

    return (
        <>
            <div className={eaState.menu === props.item ? 'ea__sidebar-nav active' : 'ea__sidebar-nav'}
                 onClick={changeHandler}>
                        <span className="ea__nav-icon">
                            <i className={icon + ' ea-dash-icon'}></i>
                        </span>
                <span className="ea__nav-text">{label}</span>
            </div>
        </>
    );
}

export default MenuItem;