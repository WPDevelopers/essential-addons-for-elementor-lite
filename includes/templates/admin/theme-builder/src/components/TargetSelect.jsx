import { useEffect, useRef, useState } from 'react';
import { Check } from './Select';
import { request, strings } from '../utils/api';

/**
 * Searchable picker for a condition's "specific target".
 *
 * The list is served by `eael_theme_builder_search_objects`, which resolves what
 * may be searched from the condition itself — so this one component covers
 * posts, pages, products, terms and authors alike, and the client never names a
 * post type or taxonomy to query.
 *
 * @param {Object}   props
 * @param {string}   props.condition Condition the picker belongs to.
 * @param {number}   props.value    Selected object ID, 0 for "All".
 * @param {string}   props.label    Label of the selected object.
 * @param {Function} props.onChange Receives `( id, label )`.
 */
export default function TargetSelect( { condition, value, label, onChange } ) {
	const [ open, setOpen ] = useState( false );
	const [ search, setSearch ] = useState( '' );
	const [ results, setResults ] = useState( [] );
	const [ loading, setLoading ] = useState( false );
	const wrapRef = useRef( null );
	const inputRef = useRef( null );
	const requestId = useRef( 0 );

	// Re-query on open and on every keystroke, debounced.
	useEffect( () => {
		if ( ! open ) {
			return undefined;
		}

		const ticket = ++requestId.current;
		const timer = window.setTimeout( async () => {
			setLoading( true );

			try {
				const response = await request( 'eael_theme_builder_search_objects', { condition, search } );

				// A slower earlier request must not overwrite a newer result.
				if ( ticket !== requestId.current ) {
					return;
				}

				setResults( response && response.success ? response.data.results : [] );
			} catch ( error ) {
				if ( ticket === requestId.current ) {
					setResults( [] );
				}
			} finally {
				if ( ticket === requestId.current ) {
					setLoading( false );
				}
			}
		}, search ? 300 : 0 );

		return () => window.clearTimeout( timer );
	}, [ open, search, condition ] );

	// Close when the focus or the pointer leaves the control.
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

	const allLabel = strings.allLabel || strings.all || 'All';
	const selectedLabel = value && label ? label : allLabel;

	const choose = ( id, text ) => {
		onChange( id, text );
		setOpen( false );
		setSearch( '' );
	};

	return (
		<div className={ `eatb-target ${ open ? 'is-open' : '' }` } ref={ wrapRef }>
			<button
				type="button"
				className="eatb-target__toggle"
				onClick={ () => {
					setOpen( ( wasOpen ) => ! wasOpen );
					window.setTimeout( () => inputRef.current && inputRef.current.focus(), 0 );
				} }
				onKeyDown={ ( event ) => {
					// Escape closes the list, not the dialog around it. The modal
					// stands aside while a panel is open; closing it is this
					// control's job.
					if ( 'Escape' === event.key && open ) {
						event.stopPropagation();
						setOpen( false );
					}
				} }
				aria-expanded={ open }
			>
				<span className={ `eatb-target__value ${ value ? '' : 'is-placeholder' }` }>
					{ selectedLabel }
				</span>

				{ value ? (
					<span
						role="button"
						tabIndex={ 0 }
						className="eatb-target__clear"
						aria-label={ strings.clearSelection || 'Clear selection' }
						onClick={ ( event ) => {
							event.stopPropagation();
							choose( 0, '' );
						} }
						onKeyDown={ ( event ) => {
							if ( event.key === 'Enter' || event.key === ' ' ) {
								event.stopPropagation();
								event.preventDefault();
								choose( 0, '' );
							}
						} }
					>
						&times;
					</span>
				) : null }

				<span className="eatb-target__arrow" aria-hidden="true" />
			</button>

			{ open ? (
				<div className="eatb-target__panel">
					<input
						type="search"
						className="eatb-target__search"
						ref={ inputRef }
						value={ search }
						placeholder={ strings.searchPlaceholder || 'Search…' }
						onChange={ ( event ) => setSearch( event.target.value ) }
						onKeyDown={ ( event ) => {
							// Focus is in here, not on the toggle, so the same
							// escape hatch has to be offered from the field.
							if ( 'Escape' === event.key ) {
								event.stopPropagation();
								setOpen( false );
							}
						} }
					/>

					<ul className="eatb-target__list" role="listbox">
						<li>
							<button
								type="button"
								className={ `eatb-target__option ${ value ? '' : 'is-selected' }` }
								onClick={ () => choose( 0, '' ) }
							>
								<span className="eatb-select__option-label">{ allLabel }</span>
								{ value ? null : <Check /> }
							</button>
						</li>

						{ results.map( ( item ) => (
							<li key={ item.id }>
								<button
									type="button"
									className={ `eatb-target__option ${ value === item.id ? 'is-selected' : '' }` }
									onClick={ () => choose( item.id, item.text ) }
								>
									<span className="eatb-select__option-label">{ item.text }</span>
									{ value === item.id ? <Check /> : null }
								</button>
							</li>
						) ) }

						{ ! loading && ! results.length ? (
							<li className="eatb-target__empty">{ strings.noResults || 'No results found' }</li>
						) : null }

						{ loading ? <li className="eatb-target__empty">{ strings.loading || 'Loading…' }</li> : null }
					</ul>
				</div>
			) : null }
		</div>
	);
}
