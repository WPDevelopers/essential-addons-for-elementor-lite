function VideoPromo() {
    const eaData = localize.eael_dashboard.video_promo,
        reactPath = localize.eael_dashboard.reactPath;

    return (
        <>
            <div className="ea__general-content-item video-promo">
                <div className='video-promo-wrapper flex justify-between items-center gap-4'>
                    <div className="templates-content">
                        <h2>{eaData.heading}</h2>
                        <p className='mb-6'>{eaData.content}</p>
                        <a href={eaData.button.playlist} target="_blank">
                            <button className="primary-btn install-btn">{eaData.button.label}</button>
                        </a>
                    </div>
                    <div className="templates-img">
                        <a href={eaData.button.url} target="_blank">
                            <img src={reactPath + eaData.image} alt="video promo"/>
                            <span>
                                <i className='ea-dash-icon ea-play'></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </>
    );
}

export default VideoPromo;