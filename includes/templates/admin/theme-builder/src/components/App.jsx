import { useCallback, useEffect, useState } from 'react';
import CreateTemplateModal from './CreateTemplateModal';
import ConditionsModal from './ConditionsModal';

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

	// Straight into the editor, which is what "Create Template" promised. The
	// preset picker opens there, on arrival — the canvas it fills is in front of
	// the user by then, and a preset chosen here would have to be written into a
	// document nobody has opened. Conditions come later, when it is published.
	const onCreated = useCallback( ( data ) => {
		setCreating( false );
		window.location.href = data.edit_url;
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
