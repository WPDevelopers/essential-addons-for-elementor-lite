import consumer from "../context";

function Header() {
    const {eaState, eaDispatch} = consumer(),
        logoSrc = eaState.isDark ? 'images/EA-Logo-Dark.svg' : 'images/EA-Logo.svg',
        clickHandler = () => {
            eaDispatch({type: 'LIGHT_DARK_TOGGLE'});
        };

    return (
        <>
            <section className="ea__section-header">
                <div className="ea__section-wrapper ea__header-content">
                    <img src={localize.eael_dashboard.reactPath + logoSrc} alt="logo"/>
                    <span className="dark-icon pointer" onClick={clickHandler}><i
                        className={eaState.isDark ? 'ea-dash-icon ea-moon' : 'ea-dash-icon ea-sun'}></i></span>
                </div>
            </section>
        </>
    );
}

export default Header;