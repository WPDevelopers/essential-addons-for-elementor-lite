import {useEffect} from "react";

/**
 * A box added to the Tools tab through the eael/admin/tools filter.
 *
 * box: {
 *   heading, content, icon,
 *   status?:  {type: 'success'|'warning'|'error', text},   badge beside the heading
 *   rows?:    [{label, value}],                              details under the content
 *   notices?: [{type: 'success'|'warning'|'error', text, items?: []}],
 *   buttons?: [{label, url, primary?, target?}],            links; `button` (one) also works
 *   url_flag?: query parameter to drop from the address bar once shown
 * }
 */
function ToolBox({box}) {
    const buttons = box.buttons || (box.button ? [box.button] : []),
        rows = box.rows || [],
        notices = box.notices || [];

    // A notice set by a redirect (e.g. ?eael_figwp=connected) is shown once; a reload shouldn't repeat it.
    useEffect(() => {
        if (!box.url_flag) {
            return;
        }
        const url = new URL(window.location.href);
        if (url.searchParams.has(box.url_flag)) {
            url.searchParams.delete(box.url_flag);
            window.history.replaceState({}, "", url.pathname + url.search + url.hash);
        }
    }, []);

    return (
        <div className="ea__connect-others ea__tool-box">
            <div className="flex gap-4 justify-between items-start">
                <div className="ea__connect--info flex gap-4 flex-1">
                    <div className="ea__others-icon eaicon-1">
                        <i className={"ea-dash-icon " + box.icon}></i>
                    </div>
                    <div>
                        <h5>
                            {box.heading}
                            {box.status &&
                                <span className={"ea__tool-box-status ea__tool-box-status--" + box.status.type}>{box.status.text}</span>}
                        </h5>
                        <p>{box.content}</p>
                    </div>
                </div>
                {buttons.length > 0 &&
                    <div className="flex gap-2 ea__tool-box-buttons">
                        {buttons.map((button, index) => (
                            <a key={index} href={button.url} target={button.target}
                               rel={button.target ? "noopener noreferrer" : undefined}
                               className={"primary-btn " + (button.primary ? "install-btn" : "changelog-btn")}>{button.label}</a>
                        ))}
                    </div>}
            </div>
            {rows.length > 0 &&
                <dl className="ea__tool-box-rows">
                    {rows.map((row, index) => (
                        <div key={index}>
                            <dt>{row.label}</dt>
                            <dd>{row.value}</dd>
                        </div>
                    ))}
                </dl>}
            {notices.map((notice, index) => (
                <div key={index} className={"ea__tool-box-notice ea__tool-box-notice--" + notice.type}>
                    <span>{notice.text}</span>
                    {notice.items && notice.items.length > 0 &&
                        <ul>{notice.items.map((item, i) => <li key={i}>{item}</li>)}</ul>}
                </div>
            ))}
        </div>
    );
}

export default ToolBox;
