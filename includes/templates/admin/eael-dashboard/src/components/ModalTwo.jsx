function ModalTwo() {

    return (
        <>
            <section className="ea__modal-wrapper">
                <div className="ea__modal-content-wrapper">
                    <div className="ea__modal-body">
                        <div className='regenerated-wrapper'>
                            <h4>Assets Regenerated!</h4>
                            <p>Essential Addons styles & scripts are saved in Uploads folder. This option will clear all those generated files.</p>
                            <img src="../../public/images/regenerate-modal.png" alt="Regenerated Img"/>
                        </div>
                    </div>
                    <div className="ea__modal-close-btn">
                        <i className="ea-dash-icon ea-close"></i>
                    </div>
                </div>
                <div className='go-premium-wrapper'>
                    <div className="flex items-center gap-2 mb-4">
                        <img src="../../public/images/go-pro-icon.svg" alt="go-pro Logo" />
                        <h4>Go Premium</h4>
                    </div>
                    <p>Purchase our premium version to unlock these pro components. you’ll have all the features you.</p>
                    <img src="../../public/images/go-premium.png.png" alt="go-premium Img"/>
                    <a href="#" className='flex justify-center flex-1'>
                        <button className="upgrade-button">
                            <i className="ea-dash-icon ea-crown-1"></i>
                            Upgrade to PRO
                        </button>
                    </a>
                </div>
            </section>
        </>
    );
}

export default ModalTwo;