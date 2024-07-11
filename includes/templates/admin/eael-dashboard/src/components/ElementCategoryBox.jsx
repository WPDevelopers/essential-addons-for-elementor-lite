import consumer from "../context";

function ElementCategoryBox(props) {
    const eaData = localize.eael_dashboard.widgets[props.index],
        {eaState} = consumer(),
        clickHandler = (e) => {
            e.preventDefault();

            window.scrollTo({
                top: props.subCatRef.current.children['ID-' + props.index]?.offsetTop + 35 + eaState.scrollOffset,
                behavior: 'smooth'
            });
        };

    return (
        <>
            <a className={props.activateIndex === eaState.elementsActivateCatIndex ? "ea__icon-wrapper active" : "ea__icon-wrapper"}
               onClick={clickHandler}>
                <i className={"ea-dash-icon " + eaData.icon}>
                    <span className="ea__tooltip">{eaData.title}</span>
                </i>
            </a>
        </>
    );
}

export default ElementCategoryBox;