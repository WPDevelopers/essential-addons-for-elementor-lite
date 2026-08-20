import { settings, strings } from '../utils/api';

/**
 * Has the user asked their system for less animation.
 *
 * Read per render rather than cached: the loader is short-lived, and a value
 * captured at module load would outlive a preference changed mid-session.
 *
 * @return {boolean} True when reduced motion is preferred.
 */
function prefersReducedMotion() {
	return (
		typeof window !== 'undefined' &&
		typeof window.matchMedia === 'function' &&
		window.matchMedia( '( prefers-reduced-motion: reduce )' ).matches
	);
}

/**
 * The EA loading state: the animated EA mark, with a caption under it.
 *
 * The mark carries the wait on its own — it is a looping animation, not a still
 * logo needing a badge to breathe around it. Under reduced motion it swaps for
 * the static logo, since no CSS rule can pause a GIF.
 *
 * Deliberately the same shape as the editor's own loading screen, so a wait
 * inside Elementor looks like part of Elementor rather than like a plugin
 * stalling. It fills the space its parent gives it instead of sizing itself,
 * which keeps a dialog from resizing when the loader hands over to content.
 */
export default function Loader() {
	const animated = prefersReducedMotion() ? '' : settings.loader;
	const mark = animated || settings.logo || settings.icon || '';

	return (
		<div className="eatb-loader" role="status" aria-live="polite">
			{ mark ? <img className="eatb-loader__mark" src={ mark } alt="" aria-hidden="true" /> : null }

			<span className="eatb-loader__label">{ strings.loading || 'Loading' }</span>
		</div>
	);
}
