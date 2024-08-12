import consumer from "../context";
import React from "react";

function ModalGoPremium() {
    const eaData = localize.eael_dashboard.pro_modal,
        reactPath = localize.eael_dashboard.reactPath,
        {eaDispatch} = consumer(),
        clickHandler = () => {
            eaDispatch({type: 'CLOSE_MODAL'});
        };

    return (
        <>
            <section className="ea__modal-wrapper">
                <div className="ea__modal-content-wrapper go-premium-wrapper">
                    <div className="ea__modal-body">
                        <div className='go-premium-wrapper'>
                            <div className="flex flex-col items-center gap-2 mb-6">
                                <img className="mb-4" src={reactPath + "images/go-pro-icon.svg"} alt="go-pro Logo"/>
                                <h3>{eaData.heading}</h3>
                                <p className="pro--content">{eaData.content}</p>
                            </div>
                            <div className="ea__feature-list-wrap">
                                {eaData.list.map((item, index) => {
                                    let marginBotton = eaData.list.length === index + 1 ? '' : 'mb-4';

                                    return <div
                                        className={"ea__feature-list-item flex gap-2 " + marginBotton}
                                        key={index}>
                                        <span>
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg"><path
                                                d="M6 8L7.33333 9.33333L10 6.66667M2 8C2 8.78793 2.15519 9.56815 2.45672 10.2961C2.75825 11.0241 3.20021 11.6855 3.75736 12.2426C4.31451 12.7998 4.97595 13.2417 5.7039 13.5433C6.43185 13.8448 7.21207 14 8 14C8.78793 14 9.56815 13.8448 10.2961 13.5433C11.0241 13.2417 11.6855 12.7998 12.2426 12.2426C12.7998 11.6855 13.2417 11.0241 13.5433 10.2961C13.8448 9.56815 14 8.78793 14 8C14 7.21207 13.8448 6.43185 13.5433 5.7039C13.2417 4.97595 12.7998 4.31451 12.2426 3.75736C11.6855 3.20021 11.0241 2.75825 10.2961 2.45672C9.56815 2.15519 8.78793 2 8 2C7.21207 2 6.43185 2.15519 5.7039 2.45672C4.97595 2.75825 4.31451 3.20021 3.75736 3.75736C3.20021 4.31451 2.75825 4.97595 2.45672 5.7039C2.15519 6.43185 2 7.21207 2 8Z"
                                                stroke="#750EF4" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                        <p>{item}</p>
                                    </div>
                                })}
                            </div>
                        </div>
                    </div>
                    <div className="ea__modal-footer flex items-center">
                        <a href={eaData.button.url} className='flex justify-center flex-1' target="_blank">
                            <button className="upgrade-button">
                                <i className="ea-dash-icon ea-crown-1"></i>
                                {eaData.button.label}
                            </button>
                        </a>
                    </div>
                    <div className="ea__modal-close-btn" onClick={clickHandler}>
                        <i className="ea-dash-icon ea-close"></i>
                    </div>
                </div>
            </section>
        </>
    );
}

export default ModalGoPremium;