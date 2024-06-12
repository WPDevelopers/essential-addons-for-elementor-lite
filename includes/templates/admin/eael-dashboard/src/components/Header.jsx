import React from 'react';

function Header() {
    return (
        <>
            <section className="ea__section-header">
                <div className="ea__section-wrapper ea__header-content">
                    <img src={localize.eael_dashboard.reactPath + '/images/EA Logo.svg'} alt="logo"/>
                    <span className="dark-icon pointer"><i className="ea-dash-icon ea-sun"></i></span>
                </div>
            </section>
        </>
    );
}

export default Header;