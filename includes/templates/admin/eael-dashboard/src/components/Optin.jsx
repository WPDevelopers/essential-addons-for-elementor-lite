function Optin() {
    const eaData = localize.eael_dashboard;

    return (
        <>
            <div id="eael-admin-promotion-message" className="eael-admin-promotion-message">
                <i className="e-notice__dismiss eael-admin-promotion-close" role="button" aria-label="Dismiss"
                   tabIndex="0"></i>
                <p dangerouslySetInnerHTML={{__html: eaData.admin_screen_promo.content}}></p>
            </div>
        </>
    );
}

export default Optin;