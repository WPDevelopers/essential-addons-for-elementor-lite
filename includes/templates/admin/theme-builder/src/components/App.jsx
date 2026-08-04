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
					isNew: false,
					editUrl: '',
				} );
			}
		};

		document.addEventListener( 'click', onClick );

		return () => document.removeEventListener( 'click', onClick );
	}, [] );

	const onCreated = useCallback( ( data ) => {
		setCreating( false );
		setEditing( {
			id: data.id,
			type: data.type,
			conditions: data.conditions || [],
			isNew: true,
			editUrl: data.edit_url,
		} );
	}, [] );

	const onSaved = useCallback( ( payload ) => {
		// A brand new template goes straight into the editor; an edit stays put
		// and just refreshes the row it changed.
		if ( editing && editing.isNew ) {
			window.location.href = editing.editUrl || payload.edit_url;

			return;
		}

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
