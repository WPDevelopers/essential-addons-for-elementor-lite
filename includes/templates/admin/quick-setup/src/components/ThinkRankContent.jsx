import { React, useState } from "react";
import { __ } from "@wordpress/i18n";
import { isPluginsPromoStepVisible } from "../utils/pluginPromoUtils";

/**
 * "Boost SEO & Speed" step — installs ThinkRank (AI SEO) and xSpeed
 * (caching / performance). Its own wizard tab, shown before the Templately /
 * Essential Blocks Plugins step. No Essential Addons branding: pure
 * "configure SEO & performance" framing. Data comes from PHP
 * (WPDeveloper_Setup_Wizard::data_thinkrank_content).
 */
function ThinkRankContent({ activeTab, handleTabChange }) {
  const data = localize?.eael_quick_setup_data?.thinkrank_content;
  const [status, setStatus] = useState("idle"); // idle | installing | done
  const [error, setError] = useState("");

  if (!data) {
    return null;
  }

  // Both plugins of this step: ThinkRank (SEO) + xSpeed (performance).
  // Falls back to the single-plugin shape for older localized data.
  const plugins =
    Array.isArray(data.plugins) && data.plugins.length
      ? data.plugins
      : [{ slug: data.slug, basename: data.basename }];

  const installOne = async (plugin) => {
    const body = new URLSearchParams({
      action: "wpdeveloper_install_plugin",
      security: localize.nonce,
      slug: plugin.slug,
      promotype: "quick-setup",
    });
    const response = await fetch(localize.ajaxurl, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: body.toString(),
    });
    const result = await response.json();

    if (!result || !result.success) {
      throw new Error((result && result.data) || "");
    }

    // The Integrations step rendered its plugin list from data localized
    // before this install — tell it the plugin is now active.
    if (plugin.slug === data.slug && localize?.eael_quick_setup_data?.thinkrank_content) {
      localize.eael_quick_setup_data.thinkrank_content.is_active = true;
    }
    window.dispatchEvent(
      new CustomEvent("eael-quick-setup:plugin-activated", {
        detail: { slug: plugin.slug, basename: plugin.basename },
      })
    );
  };

  const install = async () => {
    setStatus("installing");
    setError("");
    try {
      // Sequential: two Plugin_Upgrader runs in parallel fight over the same
      // filesystem lock and the plugins cache.
      for (const plugin of plugins) {
        await installOne(plugin);
      }

      setStatus("done");
      // Stay inside the wizard: advance to the next step instead of
      // leaving for the ThinkRank dashboard.
      window.setTimeout(() => {
        const nextButton = document.createElement("button");
        nextButton.setAttribute("data-next", isPluginsPromoStepVisible() ? "pluginspromo" : "integrations");
        handleTabChange({ currentTarget: nextButton });
      }, 900);
    } catch (e) {
      setStatus("idle");
      setError(
        (e && e.message) ||
          __("Could not install automatically. Try Plugins → Add New.", "essential-addons-for-elementor-lite")
      );
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
          disabled={status === "installing"}
        >
          {status === "done" ? __("Next", "essential-addons-for-elementor-lite") : data.skip_label}
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
