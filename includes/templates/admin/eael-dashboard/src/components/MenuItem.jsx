import consumer from "../context";
import {useNavigate} from "react-router-dom";


function MenuItem(props) {
    const eaData = localize.eael_dashboard.menu[props.item],
        label = eaData.label,
        icon = eaData.icon,
        {eaState} = consumer(),
        navigate = useNavigate(),
        changeHandler = () => {
            navigate(`/${props.item}`);
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