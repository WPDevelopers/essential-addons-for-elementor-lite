function ModalGoPremium() {
    const eaData = localize.eael_dashboard;

    return (
        <>
            <section className="ea__modal-wrapper">
                <div className="ea__modal-content-wrapper">
                    <div className="ea__modal-body">
                        <div className='go-premium-wrapper'>
                            <div className="flex items-center gap-2 mb-4">
                                <img src={eaData.reactPath + "images/go-pro-icon.svg"} alt="go-pro Logo"/>
                                <h4>Go Premium</h4>
                            </div>
                            <p>Purchase our premium version to unlock these pro components. you’ll have all the features
                                you.</p>
                            <img src={eaData.reactPath + "images/go-premium.png"} alt="go-premium Img"/>
                        </div>
                    </div>
                    <div className="ea__modal-footer flex items-center">
                        <a href="#" className='flex justify-center flex-1'>
                            <button className="upgrade-button">
                                <i className="ea-dash-icon ea-crown-1"></i>
                                Upgrade to PRO
                            </button>
                        </a>
                    </div>
                    <div className="ea__modal-close-btn">
                        <i className="ea-dash-icon ea-close"></i>
                    </div>
                </div>
            </section>
        </>
    );
}

export default ModalGoPremium;