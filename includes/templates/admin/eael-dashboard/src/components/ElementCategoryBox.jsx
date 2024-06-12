import consumer from "../context";

function ElementCategoryBox(props) {
    const eaData = localize.eael_dashboard.widgets[props.index],
        {eaState} = consumer();

    return (
        <>
            <a className={props.activateIndex === eaState.elementsActivateCatIndex ? "ea__icon-wrapper active" : "ea__icon-wrapper"}
               href={'#ID-' + props.index}>
                <i className={"ea-dash-icon " + eaData.icon}>
                    <span className="ea__tooltip">{eaData.title}</span>
                </i>
            </a>
        </>
    );
}

export default ElementCategoryBox;