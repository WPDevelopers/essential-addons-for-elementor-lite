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
    <>
      <div className="eael-onboard-content-wrapper eael-thinkrank-promo mb-4">
        <div className="eael-thinkrank-promo-content">
          <div className="eael-thinkrank-promo__head">
            {data.logo ? <img className="eael-thinkrank-promo__logo" src={data.logo} alt="" /> : null}
            <h2 className="eael-thinkrank-promo__title">{data.title}</h2>
            <p className="eael-thinkrank-promo__subtitle">{data.subtitle}</p>
          </div>

          <ul className="eael-thinkrank-promo__features">
            {(data.features || []).map((feature, index) => (
              <li className="eael-thinkrank-promo__feature" key={index}>
                <span>{feature.content}</span>
              </li>
            ))}
          </ul>

          {error ? <p className="eael-thinkrank-promo__error">{error}</p> : null}
        </div>

        {data.promo_img_url ? (
          <div className="eael-thinkrank-promo-img">
            <img src={data.promo_img_url} alt={data.title} />
          </div>
        ) : null}
      </div>

      <div className="eael-section-wrapper flex flex-end gap-4">
        <button
          className="previous-btn flex gap-2 items-center eael-setup-next-btn"
          type="button"
          data-next={ isPluginsPromoStepVisible() ? "pluginspromo" : "integrations" }
          onClick={handleTabChange}
          disabled={status !== "idle"}
        >
          {data.skip_label}
        </button>
        <button
          className="primary-btn install-btn flex gap-2 items-center eael-setup-next-btn"
          type="button"
          onClick={install}
          disabled={status !== "idle"}
        >
          {label}
        </button>
      </div>
    </>
  );
}

export default ThinkRankContent;
