import { useCallback, useEffect, useState } from 'react';
import CreateTemplateModal from './CreateTemplateModal';
import ConditionsModal from './ConditionsModal';
import PresetLibrary from './PresetLibrary';

/**
 * Parse the conditions payload a row action carries.
 *
 * @param {string} raw JSON from the `data-conditions` attribute.
 *
 * @return {Array} Conditions, empty on malformed input.
 */
function parseConditions( raw ) {
	if ( ! raw ) {
		return [];
	}

	try {
		const parsed = JSON.parse( raw );

		return Array.isArray( parsed ) ? parsed : [];
	} catch ( error ) {
		return [];
	}
}

/**
 * Repaint the list row a save affected, instead of reloading the screen.
 *
 * @param {number} templateId Template ID.
 * @param {Object} payload    Server response data.
 */
function syncRow( templateId, payload ) {
	const link = document.querySelector( `.eael-tb-edit-conditions[data-template-id="${ templateId }"]` );

	if ( ! link ) {
		return;
	}

	link.setAttribute( 'data-conditions', JSON.stringify( payload.conditions || [] ) );

	const row = link.closest( 'tr' );
	const cell = row ? row.querySelector( 'td.conditions' ) : null;

	if ( cell ) {
		cell.textContent = payload.summary || '';
	}
}

/**
 * Owns which modal is open.
 *
 * The templates list stays server-rendered — it is a `WP_List_Table`, i.e.
 * native admin chrome — so the app hooks the two buttons that open custom UI
 * through one delegated listener rather than taking over the page.
 */
export default function App() {
	const [ creating, setCreating ] = useState( false );
	const [ created, setCreated ] = useState( null );
	const [ editing, setEditing ] = useState( null );

	useEffect( () => {
		const onClick = ( event ) => {
			const addNew = event.target.closest( '.eael-tb-add-new' );

			if ( addNew ) {
				event.preventDefault();
				setCreating( true );

				return;
			}

			const conditionsLink = event.target.closest( '.eael-tb-edit-conditions' );

			if ( conditionsLink ) {
				event.preventDefault();
				setEditing( {
					id: Number( conditionsLink.dataset.templateId ),
					type: conditionsLink.dataset.templateType || '',
					conditions: parseConditions( conditionsLink.dataset.conditions ),
				} );
			}
		};

		document.addEventListener( 'click', onClick );

		return () => document.removeEventListener( 'click', onClick );
	}, [] );

	// The template exists from here on — it is a draft with an empty canvas — so
	// the picker is offered rather than asked: pick a preset and land in the
	// editor with it already laid out, or dismiss and land on the blank canvas
	// that was always going to be there. Either way the next stop is the editor,
	// which is what "Create Template" promised. Conditions come later, when it
	// is published.
	const onCreated = useCallback( ( data ) => {
		setCreating( false );
		setCreated( data );
	}, [] );

	const openEditor = useCallback( ( url ) => {
		window.location.href = url;
	}, [] );

	const onSaved = useCallback( ( payload ) => {
		if ( editing ) {
			syncRow( editing.id, payload );
		}

		setEditing( null );
	}, [ editing ] );

	return (
		<>
			{ creating ? (
				<CreateTemplateModal onClose={ () => setCreating( false ) } onCreated={ onCreated } />
			) : null }

			{ created ? (
				<PresetLibrary
					type={ created.type }
					templateId={ created.id }
					onClose={ () => openEditor( created.edit_url ) }
					onApplied={ ( url ) => openEditor( url || created.edit_url ) }
				/>
			) : null }

			{ editing ? (
				<ConditionsModal
					template={ editing }
					onClose={ () => setEditing( null ) }
					onSaved={ onSaved }
				/>
			) : null }
		</>
	);
}
