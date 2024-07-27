import EnhanceExperience from "./EnhanceExperience.jsx";
import ExploreProFeatures from "./ExploreProFeatures.jsx";
import CommunityBox from "./CommunityBox.jsx";
import PremiumItem from "./PremiumItem.jsx";

import {Swiper, SwiperSlide} from 'swiper/react';
import {Autoplay, Pagination} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';

function Premium() {
    const eaData = localize.eael_dashboard.premium_items;

    return (
        <>
            <div className="ea__elements-nav-content go-premium-wrapper">
                <div className="ea__premium-content-wrapper">
                    <EnhanceExperience/>
                    <ExploreProFeatures/>
                    <div className="ea__slider-connect">
                        <div className="ea__connect-wrapper flex">
                            <Swiper modules={[Autoplay, Pagination]} spaceBetween={16} slidesPerView={3} loop={true}
                                    autoplay={{delay: 2500, disableOnInteraction: false}}
                                    pagination={{clickable: true}}>
                                {eaData.list.map((item, index) => {
                                    return <SwiperSlide key={index}><PremiumItem index={index}/></SwiperSlide>;
                                })}
                            </Swiper>
                        </div>
                    </div>
                    <div className="ea__connect-others-wrapper flex gap-4">
                        <CommunityBox index={4}/>
                        <CommunityBox index={5}/>
                    </div>
                </div>
            </div>
        </>
    );
}

export default Premium;