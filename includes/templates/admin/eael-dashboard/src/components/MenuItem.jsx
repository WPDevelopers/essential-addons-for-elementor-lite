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
                            {eaData.icon_url
                                ? <img className="ea__nav-logo" src={eaData.icon_url} alt="" width="18" height="18"/>
                                : <i className={icon + ' ea-dash-icon'}></i>}
                        </span>
                <span className="ea__nav-text">{label}</span>
                {eaData.badge && <span className={"content-btn ea__nav-badge " + eaData.badge.toLowerCase()}>{eaData.badge}</span>}
            </div>
        </>
    );
}

export default MenuItem;