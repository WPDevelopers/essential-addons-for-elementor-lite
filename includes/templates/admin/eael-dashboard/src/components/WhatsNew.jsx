import React from 'react';

function WhatsNew() {
    const eaData = localize.eael_dashboard.whats_new;

    return (
        <>
            <div className="ea__general-content-item relative">
                <h3>{eaData.heading}</h3>
                <div className="mb-6 flex flex-col gap-4">
                    {eaData.list.map((item, index) => {
                        return <div className="ea__content-details flex gap-2" key={index}>
                                    <span>
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3.00229 8.32568C2.5963 8.18129 2.5963 7.60714 3.00229 7.46275L6.08799 6.36538C6.21776 6.31923 6.31986 6.21713 6.36601 6.08736L7.46338 3.00166C7.60777 2.59567 8.18192 2.59567 8.32631 3.00166L9.42368 6.08736C9.46983 6.21713 9.57194 6.31923 9.7017 6.36538L12.7874 7.46275C13.1934 7.60714 13.1934 8.18129 12.7874 8.32568L9.7017 9.42305C9.57194 9.4692 9.46983 9.5713 9.42368 9.70107L8.32631 12.7868C8.18193 13.1928 7.60777 13.1928 7.46339 12.7868L6.36601 9.70107C6.31986 9.57131 6.21776 9.4692 6.08799 9.42305L3.00229 8.32568Z"
                                                fill="#9189E1"/>
                                            <path
                                                d="M3.19451 5.74733C3.1436 5.87596 2.96155 5.87596 2.91064 5.74733L2.21125 3.98016C2.19573 3.94095 2.16469 3.90991 2.12549 3.8944L0.358309 3.195C0.22968 3.14409 0.22968 2.96204 0.358309 2.91113L2.12549 2.21174C2.16469 2.19622 2.19573 2.16518 2.21125 2.12598L2.91064 0.358798C2.96155 0.230169 3.1436 0.230169 3.19451 0.358798L3.89391 2.12598C3.90942 2.16518 3.94046 2.19622 3.97967 2.21174L5.74684 2.91113C5.87547 2.96204 5.87547 3.14409 5.74684 3.195L3.97967 3.8944C3.94046 3.90991 3.90942 3.94095 3.89391 3.98016L3.19451 5.74733Z"
                                                fill="#EBE9FE"/>
                                        </svg>
                                    </span>
                            <div><span className='title--ex'>{item.label}</span>{item.content}</div>
                        </div>;
                    })}
                </div>
                <a href={eaData.button.url} target="_blank">
                    <button className="primary-btn changelog-btn">
                        {eaData.button.label}
                        <i className="ea-dash-icon ea-link"></i>
                    </button>
                </a>
            </div>
        </>
    );
}

export default WhatsNew;