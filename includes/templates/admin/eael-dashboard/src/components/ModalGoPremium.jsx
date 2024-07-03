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
                                        className={"ea__feature-list-item flex gap-2 items-center " + marginBotton}
                                        key={index}>
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5 7L6.33333 8.33333L9 5.66667M1 7C1 7.78793 1.15519 8.56815 1.45672 9.2961C1.75825 10.0241 2.20021 10.6855 2.75736 11.2426C3.31451 11.7998 3.97595 12.2417 4.7039 12.5433C5.43185 12.8448 6.21207 13 7 13C7.78793 13 8.56815 12.8448 9.2961 12.5433C10.0241 12.2417 10.6855 11.7998 11.2426 11.2426C11.7998 10.6855 12.2417 10.0241 12.5433 9.2961C12.8448 8.56815 13 7.78793 13 7C13 6.21207 12.8448 5.43185 12.5433 4.7039C12.2417 3.97595 11.7998 3.31451 11.2426 2.75736C10.6855 2.20021 10.0241 1.75825 9.2961 1.45672C8.56815 1.15519 7.78793 1 7 1C6.21207 1 5.43185 1.15519 4.7039 1.45672C3.97595 1.75825 3.31451 2.20021 2.75736 2.75736C2.20021 3.31451 1.75825 3.97595 1.45672 4.7039C1.15519 5.43185 1 6.21207 1 7Z"
                                                stroke="#750EF4" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                        </svg>
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