import consumer from "../context";


function MenuItem(props) {
    const label = props.item,
        icon = localize.eael_dashboard.menu[label],
        {eaState, eaDispatch} = consumer(),
        changeHandler = () => {
            eaDispatch({type: 'SET_MENU', payload: label});
        };

    return (
        <>
            <div className={eaState.menu === label ? 'ea__sidebar-nav active' : 'ea__sidebar-nav'}
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