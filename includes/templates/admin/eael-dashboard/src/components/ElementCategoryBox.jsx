function ElementCategoryBox(props) {
    const eaData = localize.eael_dashboard.widgets[props.index];

    return (
        <>
            <a className={props.index === 'content-elements' ? "ea__icon-wrapper active" : "ea__icon-wrapper"}
               href={'#ID-' + props.index}>
                <i className={"eaicon " + eaData.icon}>
                    <span className="ea__tooltip">{eaData.title}</span>
                </i>
            </a>
        </>
    );
}

export default ElementCategoryBox;