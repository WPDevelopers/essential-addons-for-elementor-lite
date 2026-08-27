import { useEffect, useRef, useState } from 'react';

/**
 * Flatten an option list to the entries a keyboard can land on.
 *
 * Group headings are labels, not choices, so they are dropped here — arrowing
 * through the list moves between things that can actually be picked.
 *
 * @param {Array} options Option list, plain or grouped.
 *
 * @return {Array} `{ value, label }` in the order they are rendered.
 */
function flatten( options ) {
	return options.flatMap( ( option ) => (
		'group' === option.type ? option.options : [ option ]
	) );
}

/**
 * The tick beside the option that is currently chosen.
 *
 * Exported so the searchable picker marks its choice the same way.
 *
 * @return {Node} An SVG.
 */
export function Check() {
	return (
		<svg
			className="eatb-select__check"
			width="16"
			height="16"
			viewBox="0 0 16 16"
			fill="none"
			stroke="currentColor"
			strokeWidth="2"
			strokeLinecap="round"
			strokeLinejoin="round"
			aria-hidden="true"
			focusable="false"
		>
			<path d="M3 8.5 6.5 12 13 4.5" />
		</svg>
	);
}

/**
 * A dropdown, in place of a `<select>`.
 *
 * The native control cannot be styled where it matters — the option list is
 * painted by the browser in its own layer — and it has nowhere to put the two
 * things this list needs: a glyph beside an option, and a tick against the one
 * already chosen. What a `<select>` gives away for free is the keyboard, so that
 * is spelled out: arrows move, Enter and Space choose, Escape closes without
 * choosing and without reaching the dialog behind it.
 *
 * Options are either `{ value, label }` or `{ type: 'group', label, options }`,
 * which is the shape the condition registry already produces.
 *
 * @param {Object}   props
 * @param {Array}    props.options      Option list, plain or grouped.
 * @param {string}   props.value        Selected value.
 * @param {Function} props.onChange     Receives the chosen value.
 * @param {string}   [props.id]         ID the field's label points at.
 * @param {string}   [props.label]      Accessible name, when no visible label.
 * @param {string}   [props.placeholder] Shown until something is chosen.
 * @param {Function} [props.renderIcon] Receives a value, returns its glyph.
 */
export default function Select( { options, value, onChange, id, label, placeholder = '', renderIcon } ) {
	const [ open, setOpen ] = useState( false );
	const wrapRef = useRef( null );
	const toggleRef = useRef( null );

	const items = flatten( options );
	const selected = items.find( ( item ) => item.value === value ) || null;

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

	const choose = ( next ) => {
		onChange( next );
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
	 * @param {number} offset -1 for the previous option, 1 for the next.
	 */
	const step = ( offset ) => {
		if ( ! open ) {
			setOpen( true );

			return;
		}

		if ( ! items.length ) {
			return;
		}

		// With nothing chosen yet, `current` is -1 and both directions clamp to
		// the first option — which is what a native select does too.
		const current = items.findIndex( ( item ) => item.value === value );
		const next = Math.min( Math.max( current + offset, 0 ), items.length - 1 );

		onChange( items[ next ].value );
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

	const option = ( item ) => (
		<li key={ item.value || 'all' }>
			<button
				type="button"
				role="option"
				aria-selected={ item.value === value }
				className={ `eatb-select__option ${ item.value === value ? 'is-selected' : '' }`.trim() }
				onClick={ () => choose( item.value ) }
			>
				{ renderIcon ? renderIcon( item.value ) : null }
				<span className="eatb-select__option-label">{ item.label }</span>
				{ item.value === value ? <Check /> : null }
			</button>
		</li>
	);

	return (
		<div className={ `eatb-select ${ open ? 'is-open' : '' }`.trim() } ref={ wrapRef }>
			<button
				type="button"
				id={ id }
				className="eatb-select__toggle"
				ref={ toggleRef }
				onClick={ () => setOpen( ( wasOpen ) => ! wasOpen ) }
				onKeyDown={ onKeyDown }
				role="combobox"
				aria-expanded={ open }
				aria-haspopup="listbox"
				aria-label={ label }
			>
				{ renderIcon && selected ? renderIcon( selected.value ) : null }

				<span className={ `eatb-select__value ${ selected ? '' : 'is-placeholder' }`.trim() }>
					{ selected ? selected.label : placeholder }
				</span>

				<span className="eatb-select__arrow" aria-hidden="true" />
			</button>

			{ open ? (
				<ul className="eatb-select__list" role="listbox" aria-label={ label }>
					{ options.map( ( entry, index ) => (
						'group' === entry.type ? (
							<li key={ `g${ index }` }>
								<p className="eatb-select__group">{ entry.label }</p>
								<ul className="eatb-select__sublist" role="group" aria-label={ entry.label }>
									{ entry.options.map( option ) }
								</ul>
							</li>
						) : option( entry )
					) ) }
				</ul>
			) : null }
		</div>
	);
}
