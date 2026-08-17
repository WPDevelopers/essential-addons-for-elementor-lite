import { settings, strings } from '../utils/api';

/**
 * The EA loading state: the logo in a soft badge, with a caption under it.
 *
 * Deliberately the same shape as the editor's own loading screen, so a wait
 * inside Elementor looks like part of Elementor rather than like a plugin
 * stalling. It fills the space its parent gives it instead of sizing itself,
 * which keeps a dialog from resizing when the loader hands over to content.
 */
export default function Loader() {
	const logo = ( settings.editor || {} ).logo || '';

	return (
		<div className="eatb-loader" role="status" aria-live="polite">
			<span className="eatb-loader__badge">
				{ logo ? <img className="eatb-loader__logo" src={ logo } alt="" aria-hidden="true" /> : null }
			</span>

			<span className="eatb-loader__label">{ strings.loading || 'Loading' }</span>
		</div>
	);
}
