import React from "react";

function VideoPromo(props) {
    const eaData = localize.eael_dashboard;

    return (
        <>
            <div className="ea__general-content-item video-promo">
                <div className='video-promo-wrapper flex justify-between items-center gap-4'>
                    <div className="templates-content">
                        <h2>Get Started with Essential Addons for Elementor</h2>
                        <p className='mb-6'>Thank you for choosing Essential Addons. Get ready to enhance your Elementor site-building experience with 100+ powerful elements & extensions from Essential Addons.</p>
                        <a href="https://www.youtube.com/playlist?list=PLWHp1xKHCfxC7JeWSg31vtVbLHGzfxDvh">
                            <button className="primary-btn install-btn">Watch & Learn</button>
                        </a>
                    </div>
                    <div className="templates-img">
                        <a href="https://www.youtube.com/watch?v=ZISSbnHo0rE" target="_blank">
                            <img src={eaData.reactPath + 'images/video-promo.png'} alt="video promo"/>
                        </a>
                    </div>
                </div>
            </div>
        </>
    );
}

export default VideoPromo;