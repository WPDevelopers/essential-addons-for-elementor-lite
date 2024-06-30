import consumer from "../context";

function Header() {
    const {eaState, eaDispatch} = consumer(),
        clickHandler = () => {
            eaDispatch({type: 'LIGHT_DARK_TOGGLE'});
        };

    return (
        <>
            <section className="ea__section-header">
                <div className="ea__section-wrapper ea__header-content">
                    <img src={localize.eael_dashboard.reactPath + 'images/EA Logo.svg'} alt="logo"/>
                    <span className="dark-icon pointer" onClick={clickHandler}><i
                        className={eaState.isDark ? 'ea-dash-icon ea-moon' : 'ea-dash-icon ea-sun'}></i></span>
                </div>
            </section>
        </>
    );
}

export default Header;