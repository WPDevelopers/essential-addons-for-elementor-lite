import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './components/App.jsx';
import EditorApp from './components/EditorApp.jsx';
import { settings } from './utils/api';
import './styles/app.css';

/**
 * One bundle, two hosts.
 *
 * On the dashboard it renders into the container the list table page prints. In
 * the Elementor editor there is no such container — and no markup of ours at all
 * — so it mounts into a node of its own at the end of the document. That happens
 * for every document Elementor opens, not only Theme Builder templates: the
 * preset button belongs on all of them, and `EditorApp` decides which of the
 * template-only pieces to register.
 */
const container = document.getElementById( 'eael-theme-builder-app' );

if ( container ) {
	ReactDOM.createRoot( container ).render(
		<React.StrictMode>
			<App />
		</React.StrictMode>
	);
} else if ( settings.editor && settings.editor.documentId ) {
	const mount = document.createElement( 'div' );

	mount.id = 'eael-theme-builder-editor-app';
	document.body.appendChild( mount );

	ReactDOM.createRoot( mount ).render(
		<React.StrictMode>
			<EditorApp />
		</React.StrictMode>
	);
}
