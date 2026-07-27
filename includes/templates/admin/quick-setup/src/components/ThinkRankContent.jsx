import { React, useState } from "react";
import { __ } from "@wordpress/i18n";
import { isPluginsPromoStepVisible } from "../utils/pluginPromoUtils";

/**
 * "Boost SEO" step — installs ThinkRank (AI SEO). Its own wizard tab, shown
 * before the Templately / Essential Blocks Plugins step. No Essential Addons
 * branding: pure "configure / analyze SEO" framing. Data comes from PHP
 * (WPDeveloper_Setup_Wizard::data_thinkrank_content).
 */
function ThinkRankContent({ activeTab, handleTabChange }) {
  const data = localize?.eael_quick_setup_data?.thinkrank_content;
  const [status, setStatus] = useState("idle"); // idle | installing | done
  const [error, setError] = useState("");

  if (!data) {
    return null;
  }

  const install = async () => {
    setStatus("installing");
    setError("");
    try {
      const body = new URLSearchParams({
        action: "wpdeveloper_install_plugin",
        security: localize.nonce,
        slug: data.slug,
        promotype: "quick-setup",
      });
      const response = await fetch(localize.ajaxurl, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: body.toString(),
      });
      const result = await response.json();
      if (result && result.success) {
        setStatus("done");
        window.setTimeout(() => {
          window.location.href = data.open_url;
        }, 900);
      } else {
        setStatus("idle");
        setError(
          (result && result.data) ||
            __("Could not install automatically. Try Plugins → Add New.", "essential-addons-for-elementor-lite")
        );
      }
    } catch (e) {
      setStatus("idle");
      setError(__("Could not install automatically. Try Plugins → Add New.", "essential-addons-for-elementor-lite"));
    }
  };

  const label =
    status === "installing" ? data.installing_label : status === "done" ? data.done_label : data.install_label;

  return (
    <div className="eael-thinkrank-promo">
      <style>{`
        .eael-thinkrank-promo { max-width: 640px; margin: 0 auto; text-align: center; padding: 8px 0 4px; }
        .eael-thinkrank-promo__logo { width: 64px; height: 64px; border-radius: 14px; }
        .eael-thinkrank-promo__title { font-size: 26px; font-weight: 700; color: #1d2327; margin: 16px 0 8px; }
        .eael-thinkrank-promo__subtitle { font-size: 15px; line-height: 1.55; color: #50575e; margin: 0 auto 24px; max-width: 480px; }
        .eael-thinkrank-promo__features { list-style: none; margin: 0 auto 26px; padding: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 12px 20px; max-width: 540px; text-align: left; }
        .eael-thinkrank-promo__feature { display: flex; align-items: center; gap: 10px; font-size: 14px; color: #2c3338; }
        .eael-thinkrank-promo__feature img { width: 22px; height: 22px; border-radius: 6px; flex: none; }
        .eael-thinkrank-promo__error { color: #d63638; font-size: 13px; margin: 0 0 14px; }
        .eael-thinkrank-promo__actions { display: flex; align-items: center; justify-content: center; gap: 16px; }
        .eael-thinkrank-promo__install { background: #4451ff; color: #fff; border: none; border-radius: 6px; padding: 12px 26px; font-size: 15px; font-weight: 600; cursor: pointer; }
        .eael-thinkrank-promo__install:hover:not(:disabled) { background: #3742d6; }
        .eael-thinkrank-promo__install:disabled { opacity: .7; cursor: default; }
        .eael-thinkrank-promo__skip { background: none; border: none; color: #50575e; font-size: 14px; text-decoration: underline; cursor: pointer; }
      `}</style>
      <div className="eael-thinkrank-promo__head">
        {data.logo ? <img className="eael-thinkrank-promo__logo" src={data.logo} alt="" /> : null}
        <h2 className="eael-thinkrank-promo__title">{data.title}</h2>
        <p className="eael-thinkrank-promo__subtitle">{data.subtitle}</p>
      </div>

      <ul className="eael-thinkrank-promo__features">
        {(data.features || []).map((feature, index) => (
          <li className="eael-thinkrank-promo__feature" key={index}>
            {feature.image_url ? <img src={feature.image_url} alt="" /> : null}
            <span>{feature.content}</span>
          </li>
        ))}
      </ul>

      {error ? <p className="eael-thinkrank-promo__error">{error}</p> : null}

      <div className="eael-thinkrank-promo__actions">
        <button
          type="button"
          className="eael-thinkrank-promo__install"
          onClick={install}
          disabled={status !== "idle"}
        >
          {label}
        </button>
        <button
          type="button"
          className="eael-thinkrank-promo__skip"
          data-next={ isPluginsPromoStepVisible() ? "pluginspromo" : "integrations" }
          onClick={handleTabChange}
        >
          {data.skip_label}
        </button>
      </div>
    </div>
  );
}

export default ThinkRankContent;
