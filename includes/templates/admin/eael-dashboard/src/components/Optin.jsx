import consumer from "../context";

function Optin() {
    const eaData = localize.eael_dashboard,
        {eaDispatch} = consumer(),
        clickHandler = () => {
            eaDispatch({type: 'CLOSE_ADMIN_PROMOTION'});
        };

    return (
        <>
            <div id="eael-admin-promotion-message" className="eael-admin-promotion-message">
                <i className="e-notice__dismiss eael-admin-promotion-close" role="button" aria-label="Dismiss"
                   tabIndex="0" onClick={clickHandler}></i>
                <p dangerouslySetInnerHTML={{__html: eaData.admin_screen_promo.content}}></p>
            </div>
        </>
    );
}

export default Optin;