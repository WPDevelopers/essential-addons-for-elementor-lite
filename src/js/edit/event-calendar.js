/**
 * Event Calendar — editor only.
 *
 * Every colour picker Elementor builds clicks its own colour-format button while
 * it initialises. That synthetic click reaches Elementor's panel-wide
 * background-click handler, which queries the whole panel once per registered
 * listener. It happens once per picker and the panel grows with every picker, so
 * opening a Manual calendar with hundreds of events (three pickers per event)
 * froze the editor for minutes.
 *
 * While such a synthetic click inside a picker is being dispatched, the
 * background-click listeners are set aside and put back right after. Real user
 * clicks are never touched.
 */
eael.hooks.addAction("editMode.init", "ea", () => {
	const editorWindow = window.parent;

	if (!editorWindow || editorWindow === window || !editorWindow.elementor) {
		return;
	}

	// The preview reloads (and this runs again) without the editor reloading.
	const guard = editorWindow.eaelPickerClickGuard || (editorWindow.eaelPickerClickGuard = {});
	if (guard.capture) {
		editorWindow.removeEventListener("click", guard.capture, true);
		editorWindow.removeEventListener("click", guard.restore, false);
	}

	let setAside = null;

	guard.restore = () => {
		if (!setAside) {
			return;
		}

		const editor = editorWindow.elementor;
		// Keep anything registered while the listeners were set aside.
		editor.backgroundClickListeners = Object.assign(setAside, editor.backgroundClickListeners);
		setAside = null;
	};

	guard.capture = (event) => {
		const editor = editorWindow.elementor;
		const target = event.target;

		if (event.isTrusted || setAside || !editor || !editor.backgroundClickListeners) {
			return;
		}

		if (!target || !target.closest || !target.closest(".pcr-app")) {
			return;
		}

		setAside = editor.backgroundClickListeners;
		editor.backgroundClickListeners = {};

		// Normally restored by the bubbling listener below; this covers a handler
		// that stops propagation.
		editorWindow.Promise.resolve().then(guard.restore);
	};

	editorWindow.addEventListener("click", guard.capture, true);
	editorWindow.addEventListener("click", guard.restore, false);
});
