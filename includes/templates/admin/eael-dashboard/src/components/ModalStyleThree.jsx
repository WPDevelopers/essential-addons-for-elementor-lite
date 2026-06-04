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
        copyRedirectUri = (uri) => {
            if (!uri) {
                return;
            }

            const copyButton = document.querySelector('.ea__copy-redirect-uri');
            if (copyButton) {
                copyButton.classList.add('is-copied');
            }
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(uri).catch(() => {
                    const tempInput = document.createElement('input');
                    tempInput.value = uri;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);
                });
            } else {
                const tempInput = document.createElement('input');
                tempInput.value = uri;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
            }
        };

    // Single-item modals (e.g. Pinterest) drop the collapsible accordion — content shows directly.
    const isSingleItem = Object.keys(eaData.accordion).length === 1;

    return (
        <>
            {Object.keys(eaData.accordion).map((item, index) => {
                if (!localize.eael_dashboard.is_eapro_activate && eaData.accordion[item]?.isPro === true) {
                    return;
                }

                const isOpen = isSingleItem || item === eaState.modalAccordion;

                return <div className={isSingleItem ? 'ea__api-key-according ea__api-key-according--single' : 'ea__api-key-according'} key={index}>
                    {!isSingleItem && <div className="ea__according-title" onClick={() => clickHandler(item)}>
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
                    </div>}
                    <div
                        className={isOpen ? 'ea__according-content flex flex-col gap-2 accordion-show' : 'ea__according-content flex flex-col gap-2'}>
                        {eaData.accordion[item]?.info !== undefined && <div className="flex flex-col gap-2">
                            <p className="info--text">
                                {eaData.accordion[item].info}
                                
                                {eaData.accordion[item]?.redirect_uri && (
                                    <span
                                        className="ea__btn ea__btn-secondary ea__copy-redirect-uri eael-copy-to-clipboard"
                                        onClick={() => copyRedirectUri(eaData.accordion[item].redirect_uri)}
                                    >
                                        <i className="eicon-copy"></i>
                                    </span>
                                )}
                            </p>
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

                            return (<div className="flex gap-4 items-center" key={subIndex}>
                                <label>{subItem.label}</label>
                                <input name={subItem.name} value={eaState.modals[subItem.name]}
                                       onChange={(e) => changeHandler(e, subItem.name)} className="input-name"
                                       type="text" placeholder={subItem.placeholder}/>
                            </div>);
                        })}
                        {eaData.accordion[item]?.connect_intro !== undefined && <div className="ea__pf-connect">
                            <div className="ea__pf-connect-head">
                                {eaData.accordion[item].connect_intro.icon && <span className="ea__pf-notice-icon">
                                    <img src={localize.eael_dashboard.reactPath + eaData.accordion[item].connect_intro.icon} alt=""/>
                                </span>}
                                <h3 className="ea__pf-connect-title">{eaData.accordion[item].connect_intro.title}</h3>
                            </div>
                            {eaData.accordion[item].connect_intro.text && <p className="ea__pf-connect-text">{eaData.accordion[item].connect_intro.text}</p>}
                            {eaData.accordion[item].connect_intro.illustration && <div className="ea__pf-connect-art" aria-hidden="true">
                                <img className="ea__pf-connect-img" src={localize.eael_dashboard.reactPath + eaData.accordion[item].connect_intro.illustration} alt=""/>
                            </div>}
                            {eaData.accordion[item]?.auth_button !== undefined && <div className="ea__pf-connect-actions">
                                <a href={eaData.accordion[item].auth_button.url} target="_blank" rel="noopener noreferrer"
                                    className="ea__pf-connect-btn">
                                    {eaData.accordion[item].auth_button.text}
                                </a>
                            </div>}
                        </div>}
                        {eaData.accordion[item]?.connected_notice !== undefined && <div className="ea__pf-notice">
                            {eaData.accordion[item].connected_notice.icon && <span className="ea__pf-notice-icon">
                                <img src={localize.eael_dashboard.reactPath + eaData.accordion[item].connected_notice.icon} alt=""/>
                            </span>}
                            <h3 className="ea__pf-notice-text">{eaData.accordion[item].connected_notice.text}</h3>
                        </div>}
                        {eaData.accordion[item]?.profile !== undefined && <div className="ea__pf-profile-card">
                            <div className="ea__pf-profile-head">
                                <div className="ea__pf-avatar-wrap">
                                    {eaData.accordion[item].profile.avatar
                                        ? <img className="ea__pf-profile-avatar" src={eaData.accordion[item].profile.avatar} alt={eaData.accordion[item].profile.name}/>
                                        : <span className="ea__pf-profile-avatar ea__pf-profile-avatar--empty"></span>}
                                    <span className="ea__pf-online-dot"></span>
                                </div>
                                <div className="ea__pf-profile-meta">
                                    <span className="ea__pf-profile-name">
                                        {eaData.accordion[item].profile.icon && <img className="ea__pf-name-icon"
                                            src={localize.eael_dashboard.reactPath + eaData.accordion[item].profile.icon} alt=""/>}
                                        {eaData.accordion[item].profile.name}
                                    </span>
                                    {eaData.accordion[item].profile.badge && <span className="ea__pf-badge ea__pf-badge--success">{eaData.accordion[item].profile.badge}</span>}
                                </div>
                                {eaData.accordion[item]?.disconnect_button !== undefined && <a
                                    href={eaData.accordion[item].disconnect_button.url} rel="noopener noreferrer"
                                    className="ea__btn ea__btn-secondary ea__auth-link ea__pf-profile-action">
                                    {eaData.accordion[item].disconnect_button.text}
                                </a>}
                            </div>
                        </div>}
                        <div className="flex flex-col gap-2 ea__auth-action-wrapper">
                            {eaData.accordion[item]?.auth_button !== undefined && eaData.accordion[item]?.connect_intro === undefined && <div className="flex gap-4 items-center ea__auth-action">
                                <a href={eaData.accordion[item].auth_button.url} target="_blank" rel="noopener noreferrer"
                                className="ea__btn ea__btn-primary ea__auth-link">
                                    {eaData.accordion[item].auth_button.text}
                                </a>
                            </div>}
                            {eaData.accordion[item]?.auth_status !== undefined && eaData.accordion[item]?.profile === undefined && <div className="flex gap-4 items-center ea__auth-action">
                                <div className={ eaData.accordion[item].auth_status.status === 'success' ? 'ea__auth-status ea__auth-status--success' : 'ea__auth-status ea__auth-status--error' }>
                                    <strong>{eaData.accordion[item].auth_status.status === 'success' ? '✓ ' : ''}{eaData.accordion[item].auth_status.text}</strong>
                                </div>
                            </div>}
                            {eaData.accordion[item]?.reconnect_button !== undefined && <div className="flex gap-4 items-center ea__auth-action">
                                <a href={eaData.accordion[item].reconnect_button.url} target="_blank" rel="noopener noreferrer"
                                className="ea__btn ea__btn-primary ea__auth-link">
                                    {eaData.accordion[item].reconnect_button.text}
                                </a>
                            </div>}
                            {eaData.accordion[item]?.disconnect_button !== undefined && eaData.accordion[item]?.profile === undefined && <div className="flex gap-4 items-center ea__auth-action">
                                <a href={eaData.accordion[item].disconnect_button.url} rel="noopener noreferrer"
                                className="ea__btn ea__btn-secondary ea__auth-link">
                                    {eaData.accordion[item].disconnect_button.text}
                                </a>
                            </div>}
                            {eaData.accordion[item]?.refresh_button !== undefined && <div className="flex gap-4 items-center ea__auth-action">
                                <a href={eaData.accordion[item].refresh_button.url} rel="noopener noreferrer"
                                className="ea__btn ea__btn-primary ea__auth-link">
                                    {eaData.accordion[item].refresh_button.text}
                                </a>
                            </div>}
                            {eaData.accordion[item]?.status_message !== undefined && eaData.accordion[item].status_message.display !== 'toast' && <div className="flex gap-4 items-center ea__auth-action">
                                <div className={ eaData.accordion[item].status_message.type === 'success' ? 'ea__status-message ea__status-message--success' : 'ea__status-message ea__status-message--error' }>
                                    <span>{eaData.accordion[item].status_message.type === 'success' ? '✓ ' : '⚠ '}{eaData.accordion[item].status_message.text}</span>
                                </div>
                            </div>}
                        </div>
                        {eaData.accordion[item]?.locations !== undefined && <div className="ea__locations-list">
                            <h5 className="ea__locations-title">{eaData.accordion[item].locations.title} ({eaData.accordion[item].locations.count})</h5>
                            {eaData.accordion[item].locations.updated && <p className="ea__locations-updated">Last updated: {eaData.accordion[item].locations.updated}</p>}
                            <ul className="ea__locations-items">
                                {eaData.accordion[item].locations.items.map((location, locationIndex) => (
                                    <li key={locationIndex} className="ea__location-item">
                                        <span className="ea__location-name">{location.name}</span>
                                    </li>
                                ))}
                            </ul>
                        </div>}
                    </div>
                </div>
            })}
        </>
    );
}

export default ModalStyleThree;