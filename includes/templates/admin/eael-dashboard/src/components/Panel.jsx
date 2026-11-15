import { useEffect } from "react";

/**
 * A tab another plugin adds through eael/admin/dashboard/menu, with its
 * content rendered on the server (menu item `html`). Links and forms in it
 * work as on any admin page.
 */
function Panel({ item }) {
	const data = localize.eael_dashboard.menu[item];

	// A redirect back to the tab may say what just happened (e.g.
	// ?eael_figwp=connected). The panel shows that once; drop it so a reload
	// doesn't repeat it.
	useEffect(() => {
		if (!data.url_flag) return;
		const url = new URL(window.location.href);
		if (url.searchParams.has(data.url_flag)) {
			url.searchParams.delete(data.url_flag);
			window.history.replaceState({}, "", url.pathname + url.search + url.hash);
		}
	}, [item]);

	return (
		<div className="ea__elements-nav-content ea__panel-content">
			<div dangerouslySetInnerHTML={{ __html: data.html }} />
		</div>
	);
}

export default Panel;
