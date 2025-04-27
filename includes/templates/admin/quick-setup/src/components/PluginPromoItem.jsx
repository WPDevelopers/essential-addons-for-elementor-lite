import React, { useState } from "react";
import { __ } from "@wordpress/i18n";


function PluginPrormoItem ({ plugin }){
    const titles = 'object' === typeof plugin?.titles ? Object.keys(plugin?.titles || {}).filter(key => !isNaN(key)).map(key => plugin?.titles[key]) : plugin?.titles;
    const features = Object.keys(plugin?.features || {}).filter(key => !isNaN(key)).map(key => plugin?.features[key]);
    
    return (
        <div className={`eael-onboard-content-wrapper eael-qs-plugin-promo mb-4 plugin-${plugin?.slug}`}>
            <div className="eael-plugin-promo-content">
                <h2 className="eael-plugin-promo-title">{ 
                    'object' === typeof plugin?.titles ?
                    titles.map((title, index) => <span className={`title-color-${index + 1}`} key={index}>{title}</span> )
                    : titles
                }</h2>
                <div className="eael-plugin-details">
                    { features.map((feature, index) => (
                        <div className="eael-content-details flex gap-3 items-center">
                            <img src={feature?.image_url} alt={`${plugin.tab_title} Icon ${index+1}`} />
                            {feature?.content}
                        </div>
                    ))}
                </div>
            </div>
            <div className="eael-qs-plugin-promo-img">
                <img src={plugin?.promo_img_url} alt={`${plugin.tab_title} Promo`} />
            </div>
        </div>
    );
}

export default PluginPrormoItem;