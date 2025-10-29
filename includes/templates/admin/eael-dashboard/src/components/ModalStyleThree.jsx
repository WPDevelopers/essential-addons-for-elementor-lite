import consumer from "../context/index.js";

function ModalStyleThree() {
    const {eaState, eaDispatch} = consumer(),
        eaData = localize.eael_dashboard.modal[eaState.modalID],
        clickHandler = (param) => {
            eaDispatch({type: 'MODAL_ACCORDION', payload: {key: param}});
        },
        changeHandler = (e, key) => {
            const value = ['lr_custom_profile_fields', 'lr_recaptcha_badge_hide'].includes(key) ? (e.target.checked ? 'on' : '') : e.target.value;
            eaDispatch({type: 'MODAL_ON_CHANGE', payload: {key, value}});
        },
        buttonClickHandler = (functionName) => {
            if (functionName === 'eaConnectGoogleBusiness') {
                handleGoogleBusinessConnect();
            }
        },
        handleGoogleBusinessConnect = async () => {
            try {
                // Prepare form data for WordPress AJAX
                const formData = new FormData();
                formData.append('action', 'eael_google_business_connect');
                formData.append('nonce', localize.nonce);
                formData.append('type', 'google_business');
                formData.append('appId', eaState.modals.br_google_my_business_app_id || '');
                formData.append('redirectURI', window.location.origin + '/wp-admin/admin.php?page=eael-settings');

                // Show loading state
                const button = document.querySelector('.ea__modal-btn');
                if (button) {
                    button.disabled = true;
                    button.textContent = 'Connecting...';
                }

                // Make AJAX request using modern fetch API
                const response = await fetch(localize.ajaxurl, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Redirect to OAuth URL
                    window.location.href = result.data;
                } else {
                    alert('Connection failed: ' + (result.data || 'Unknown error'));
                    if (button) {
                        button.disabled = false;
                        button.textContent = 'Connect Your Account';
                    }
                }
            } catch (error) {
                alert('Connection request failed. Please try again.');
                const button = document.querySelector('.ea__modal-btn');
                if (button) {
                    button.disabled = false;
                    button.textContent = 'Connect Your Account';
                }
            }
        };

    return (
        <>
            {Object.keys(eaData.accordion).map((item, index) => {
                if (!localize.eael_dashboard.is_eapro_activate && eaData.accordion[item]?.isPro === true) {
                    return;
                }

                return <div className="ea__api-key-according" key={index}>
                    <div className="ea__according-title" onClick={() => clickHandler(item)}>
                        <div className="flex justify-between items-center gap-2 pointer">
                        <span className="flex gap-2 items-center">
                            <img src={localize.eael_dashboard.reactPath + eaData.accordion[item].icon} alt="icon"/>
                            <h4 className="flex items-center gap-2">{eaData.accordion[item].title}
                                {eaData.accordion[item]?.status != undefined &&
                                    <label className="toggle-wrap">
                                        <input type="checkbox" value="on" name={eaData.accordion[item].status.name}
                                               checked={eaState.modals[eaData.accordion[item].status.name] === 'on'}
                                               onChange={(e) => changeHandler(e, eaData.accordion[item].status.name)}/>
                                        <span className="slider"></span>
                                    </label>}
                            </h4>
                        </span>
                            <i className={item === eaState.modalAccordion ? 'ea-dash-icon ea-dropdown rotate-180' : 'ea-dash-icon ea-dropdown'}></i>
                        </div>
                    </div>
                    <div
                        className={item === eaState.modalAccordion ? 'ea__according-content flex flex-col gap-2 accordion-show' : 'ea__according-content flex flex-col gap-2'}>
                        {eaData.accordion[item]?.info !== undefined && <div className="flex gap-4 items-center">
                            <p className="info--text">{eaData.accordion[item].info}</p>
                        </div>}
                        {eaData.accordion[item].fields.map((subItem, subIndex) => {
                            if (subItem?.type === 'checkbox') {
                                return (<div className="ea__hide-badge flex gap-2 items-center" key={subIndex}>
                                    <input type="checkbox" name={subItem.name}
                                           checked={eaState.modals[subItem.name] === 'on'}
                                           onChange={(e) => changeHandler(e, subItem.name)}/>
                                    <label>{subItem.label} {subItem?.info && <i className="ea-dash-icon ea-info"><span
                                        className='ea__tooltip'>{subItem.info}</span></i>}</label>
                                </div>);
                            }

                            if (subItem?.type === 'button') {
                                return (<div className="flex flex-col gap-2" key={subIndex}>
                                    <button
                                        type="button"
                                        className="primary-btn ea__modal-btn"
                                        onClick={() => buttonClickHandler(subItem.onClick)}
                                    >
                                        {subItem.icon && <i className={`ea-dash-icon ${subItem.icon}`}></i>}
                                        {subItem.label}
                                    </button>
                                    {subItem?.info && <p className="info--text">{subItem.info}</p>}
                                </div>);
                            }

                            return (<div className="flex gap-4 items-center" key={subIndex}>
                                <label>{subItem.label}</label>
                                <input name={subItem.name} value={eaState.modals[subItem.name]}
                                       onChange={(e) => changeHandler(e, subItem.name)} className="input-name"
                                       type="text" placeholder={subItem.placeholder}/>
                            </div>);
                        })}
                    </div>
                </div>
            })}
        </>
    );
}

export default ModalStyleThree;