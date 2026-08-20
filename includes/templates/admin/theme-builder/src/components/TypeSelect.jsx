import { useEffect, useRef, useState } from 'react';
import { strings } from '../utils/api';

/**
 * The glyph that stands for a template type.
 *
 * A header and a footer are the same rectangle with the weight at opposite
 * ends, which is the whole distinction — so they are drawn as one shape with a
 * band that moves, rather than as two unrelated icons.
 *
 * @param {Object} props
 * @param {string} props.slug Template type slug.
 *
 * @return {Node} An SVG, or null for a type with no glyph of its own.
 */
function TypeIcon( { slug } ) {
	if ( 'header' !== slug && 'footer' !== slug ) {
		return null;
	}

	const band = 'header' === slug
		? <path d="M2.5 7.5h15" />
		: <path d="M2.5 12.5h15" />;

	return (
		<svg
			className="eatb-type__icon"
			width="20"
			height="20"
			viewBox="0 0 20 20"
			fill="none"
			stroke="currentColor"
			strokeWidth="1.4"
			strokeLinecap="round"
			aria-hidden="true"
			focusable="false"
		>
			<rect x="2.5" y="3.5" width="15" height="13" rx="2.5" />
			{ band }
		</svg>
	);
}

/**
 * Template type picker.
 *
 * A listbox rather than a `<select>`: each type carries a glyph, and the native
 * control has nowhere to put one. Keyboard behaviour is the part a `<select>`
 * gives away for free, so it is spelled out here — arrows move through the
 * options, Enter and Space choose, Escape closes without choosing.
 *
 * @param {Object}   props
 * @param {Array}    props.types    `{ slug, label }` options.
 * @param {string}   props.value    Selected slug, empty for none.
 * @param {Function} props.onChange Receives the chosen slug.
 * @param {string}   props.id       ID the field's label points at.
 */
export default function TypeSelect( { types, value, onChange, id } ) {
	const [ open, setOpen ] = useState( false );
	const wrapRef = useRef( null );
	const toggleRef = useRef( null );

	const selected = types.find( ( item ) => item.slug === value ) || null;

	// Close when the pointer lands outside, the way a native dropdown does.
	useEffect( () => {
		if ( ! open ) {
			return undefined;
		}

		const onDocumentDown = ( event ) => {
			if ( wrapRef.current && ! wrapRef.current.contains( event.target ) ) {
				setOpen( false );
			}
		};

		document.addEventListener( 'mousedown', onDocumentDown );

		return () => document.removeEventListener( 'mousedown', onDocumentDown );
	}, [ open ] );

	const choose = ( slug ) => {
		onChange( slug );
		setOpen( false );

		// Focus goes back to the control the user was on, not to whatever the
		// browser picks once the option they clicked stops existing.
		if ( toggleRef.current ) {
			toggleRef.current.focus();
		}
	};

	/**
	 * Move the selection by one, opening the list if it is closed.
	 *
	 * @param {number} offset -1 for the previous type, 1 for the next.
	 */
	const step = ( offset ) => {
		if ( ! open ) {
			setOpen( true );

			return;
		}

		if ( ! types.length ) {
			return;
		}

		// With nothing chosen yet, `current` is -1 and both directions clamp to
		// the first type — which is what a native select does too.
		const current = types.findIndex( ( item ) => item.slug === value );
		const next = Math.min( Math.max( current + offset, 0 ), types.length - 1 );

		onChange( types[ next ].slug );
	};

	const onKeyDown = ( event ) => {
		if ( 'ArrowDown' === event.key || 'ArrowUp' === event.key ) {
			event.preventDefault();
			step( 'ArrowDown' === event.key ? 1 : -1 );

			return;
		}

		if ( 'Escape' === event.key && open ) {
			// Stops the modal from reading this as a dismissal: closing the list
			// is what Escape means while the list is open.
			event.stopPropagation();
			setOpen( false );

			return;
		}

		if ( 'Enter' === event.key || ' ' === event.key ) {
			event.preventDefault();
			setOpen( ( wasOpen ) => ! wasOpen );
		}
	};

	return (
		<div className={ `eatb-type ${ open ? 'is-open' : '' }`.trim() } ref={ wrapRef }>
			<button
				type="button"
				id={ id }
				className="eatb-type__toggle"
				ref={ toggleRef }
				onClick={ () => setOpen( ( wasOpen ) => ! wasOpen ) }
				onKeyDown={ onKeyDown }
				role="combobox"
				aria-expanded={ open }
				aria-haspopup="listbox"
			>
				{ selected ? <TypeIcon slug={ selected.slug } /> : null }

				<span className={ `eatb-type__value ${ selected ? '' : 'is-placeholder' }`.trim() }>
					{ selected ? selected.label : ( strings.typePlaceholder || 'Choose a template type' ) }
				</span>

				<span className="eatb-type__arrow" aria-hidden="true" />
			</button>

			{ open ? (
				<ul className="eatb-type__list" role="listbox" aria-labelledby={ id }>
					{ types.map( ( item ) => (
						<li key={ item.slug }>
							<button
								type="button"
								role="option"
								aria-selected={ item.slug === value }
								className={ `eatb-type__option ${ item.slug === value ? 'is-selected' : '' }`.trim() }
								onClick={ () => choose( item.slug ) }
							>
								<TypeIcon slug={ item.slug } />
								<span>{ item.label }</span>
							</button>
						</li>
					) ) }
				</ul>
			) : null }
		</div>
	);
}
