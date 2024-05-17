import React from 'react';

function PremiumItem() {
    return (
        <>
            <div className="ea__premium-item">
                <div className="ea__premimu-item-header flex gap-2 items-center">
                    <img src={localize.eael_dashboard.reactPath + '/images/img-5.png'} alt="img"/>
                </div>
                <div className="ea__premium-item-footer">
                    <h5>Protected Content </h5>
                    <p className="mb-2">Restrict access to important data of your
                        website by setting up user
                        permissions
                    </p>
                    <a href="#">
                        <button className="underline">View Demo</button>
                    </a>
                </div>
            </div>
        </>
    );
}

export default PremiumItem;