import React, { useState } from "react";
import { __ } from "@wordpress/i18n";


function PluginPrormoItem ({ plugin }){
    const titles = Object.keys(plugin?.titles || {}).filter(key => !isNaN(key)).map(key => plugin?.titles[key]);
    const features = Object.keys(plugin?.features || {}).filter(key => !isNaN(key)).map(key => plugin?.features[key]);
    
    return (
        <div className={`eael-onboard-content-wrapper eael-onboard-templately mb-4 plugin-${plugin?.slug}`}>
            <div className="eael-general-content-item templates flex justify-between items-center gap-5">
            <div className="templates-content">
                <h2>{ titles.map((title, index) => <span className={`title-color-${index + 1}`} key={index}>{title}</span> )}</h2>

                <p className="mb-10">{plugin?.description} </p>

                <div className="eael-templately-details flex flex-col gap-4">
                    { features.map((feature, index) => (
                        <div className="eael-content-details flex gap-3 items-center">
                            <img src={feature?.image_url} alt={`${plugin.tab_title} Icon ${index+1}`} />
                            {feature?.content}
                        </div>
                    ))}
                </div>
            </div>
            <div className="templates-img">
                <img src={plugin?.promo_img_url} alt={`${plugin.tab_title} Promo`} />
            </div>
            </div>
        </div>
    );
}

export default PluginPrormoItem;