import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './components/App.jsx';
import './styles/app.css';

const container = document.getElementById( 'eael-theme-builder-app' );

if ( container ) {
	ReactDOM.createRoot( container ).render(
		<React.StrictMode>
			<App />
		</React.StrictMode>
	);
}
