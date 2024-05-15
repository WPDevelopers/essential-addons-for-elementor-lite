import React from 'react';

function Header() {
    return (
        <>
            <section className="ea__section-header">
                <div className="ea__section-wrapper ea__header-content">
                    <img src={localize.eael_dashboard.reactPath + '/images/logo.png'} alt="logo"/>
                    <span className="dark-icon pointer"><i className="eaicon ea-sun"></i></span>
                </div>
            </section>
        </>
    );
}

export default Header;