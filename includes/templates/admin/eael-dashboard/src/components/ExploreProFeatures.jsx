import React from 'react';

function ExploreProFeatures() {
    const eaData = localize.eael_dashboard.explore_pro_features,
        assetPath = localize.eael_dashboard.reactPath;

    return (
        <>
            <div className="ea__pro-features flex justify-between items-center">
                <div className="ea__features-content">
                    <h2>{eaData.heading}</h2>
                    <p className="mb-7">{eaData.content}</p>
                    <div className="ea__feature-list-wrap mb-6">
                        {eaData.list.map((item, index)=> {
                            return <div className="ea__feature-list-item flex gap-2 mb-4" key={index}>
                                <i className='ea-dash-icon ea-active'></i>
                                <p>{item}</p>
                            </div>;
                        })}
                    </div>
                    <a href={eaData.button.url} target="_blank">
                         <span className="primary-btn changelog-btn">
                           <i className="ea-dash-icon ea-link"></i>
                             {eaData.button.label}
                         </span>
                    </a>
                </div>
                <div className="features-widget-wrapper">

                    <div className="features-widget-item">
                        <a href="https://essential-addons.com/image-hotspots/" target="_blank">
                            <img src={assetPath + 'images/Image-Hotspots.svg'} alt="img"/>
                            <span className="eael-tooltip">Image Hotspots</span>
                        </a>
                    </div>
                    <div className="features-widget-item">
                        <a href="https://essential-addons.com/learndash-course-list/" target="_blank">
                            <img src={assetPath + 'images/Learn-Dash-Course-List.svg'} alt="img"/>
                            <span className="eael-tooltip">LearnDash Course List</span>
                        </a>
                    </div>
                    <div className="features-widget-item">
                        <a href="https://essential-addons.com/particle-effect/" target="_blank">
                            <img src={assetPath + 'images/Particles.svg'} alt="img"/>
                            <span className="eael-tooltip">Particles</span>
                        </a>
                    </div>
                    <div className="features-widget-item">
                        <a href="https://essential-addons.com/instagram-feed/" target="_blank">
                            <img src={assetPath + 'images/Instagram-Feed.svg'} alt="img"/>
                            <span className="eael-tooltip">Instagram Feed</span>
                        </a>
                    </div>
                    <div className="features-widget-item">
                        <a href="https://essential-addons.com/dynamic-gallery/" target="_blank">
                            <img src={assetPath + 'images/Dynamic-Gallery.svg'} alt="img"/>
                            <span className="eael-tooltip">Dynamic Gallery</span>
                        </a>
                    </div>
                    <div className="features-widget-item">
                        <a href="https://essential-addons.com/parallax-scrolling/" target="_blank">
                            <img src={assetPath + 'images/Parallax-Effect.svg'} alt="img"/>
                            <span className="eael-tooltip">Parallax Effect</span>
                        </a>
                    </div>
                    <div className="features-widget-item">
                        <a href="https://essential-addons.com/mailchimp/" target="_blank">
                            <img src={assetPath + 'images/Mailchimp.svg'} alt="img"/>
                            <span className="eael-tooltip">Mailchimp</span>
                        </a>
                    </div>
                    <div className="features-widget-item">
                        <a href="https://essential-addons.com/advanced-google-map/" target="_blank">
                            <img src={assetPath + 'images/Advanced-Google-Map.svg'} alt="img"/>
                            <span className="eael-tooltip">Advanced Google Map</span>
                        </a>
                    </div>
                    <div className="features-widget-item">
                        <a href="https://essential-addons.com/logo-carousel/" target="_blank">
                            <img src={assetPath + 'images/Logo-Carousel.svg'} alt="img"/>
                            <span className="eael-tooltip">Logo Carousel</span>
                        </a>
                    </div>
                    <div className="features-widget-item">
                        <a href="https://essential-addons.com/woo-cross-sells/" target="_blank">
                            <img src={assetPath + 'images/Woo-Cross-Sells.svg'} alt="img"/>
                            <span className="eael-tooltip">Woo Cross Sells</span>
                        </a>
                    </div>
                </div>
            </div>
        </>
    );
}

export default ExploreProFeatures;