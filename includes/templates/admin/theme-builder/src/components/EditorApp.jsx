import { useCallback, useEffect, useRef, useState } from 'react';
import ConditionsModal from './ConditionsModal';
import PresetLibrary from './PresetLibrary';
import { settings, strings } from '../utils/api';
import { notify, registerPublishGate, resumePublish } from '../utils/elementor';
import { OPEN_CONDITIONS_EVENT, registerConditionsMenuItem } from '../utils/documentMenu';
import { insertPreset, OPEN_PRESETS_EVENT, registerPresetButton } from '../utils/presetButton';

/**
 * The condition builder as the last step of publishing.
 *
 * Publishing a header or a footer is the moment it starts replacing the theme's
 * own, so it is the moment the user is asked where that should happen. The gate
 * holds the publish command back, the modal saves the conditions, and the same
 * command is then run again — this time waved through.
 *
 * Updating an already published template is not gated — the conditions exist by
 * then. Changing them afterwards is what the save menu's "Display Conditions"
 * item is for: the same modal, opened on demand, saving on its own without
 * touching the document.
 */
export default function EditorApp() {
	const editor = settings.editor || {};

	const [ conditions, setConditions ] = useState( () => editor.conditions || [] );
	const [ held, setHeld ] = useState( null );
	const [ editing, setEditing ] = useState( false );
	const [ presetTarget, setPresetTarget ] = useState( null );

	// The gate is registered once and outlives every render, so what it reads has
	// to be a ref — a captured state value would be the one from mount forever.
	const allowRef = useRef( false );

	useEffect( () => {
		if ( ! editor.templateId ) {
			return;
		}

		registerPublishGate( editor.templateId, ( args ) => {
			if ( allowRef.current ) {
				allowRef.current = false;

				return true;
			}

			setHeld( args || {} );

			return false;
		} );
	}, [ editor.templateId ] );

	// The save menu's condition item reports the click the same way the preset
	// button does — the menu lives in Elementor's own UI, the modal lives here.
	useEffect( () => {
		if ( ! editor.templateId ) {
			return undefined;
		}

		registerConditionsMenuItem( {
			label: strings.conditionsMenu || 'Display Conditions',
		} );

		const onOpen = () => setEditing( true );

		window.addEventListener( OPEN_CONDITIONS_EVENT, onOpen );

		return () => window.removeEventListener( OPEN_CONDITIONS_EVENT, onOpen );
	}, [ editor.templateId ] );

	// The EA button in the add-element row: it reports where it was clicked, and
	// the picker opens here.
	useEffect( () => {
		if ( ! editor.templateId || ! Array.isArray( editor.presets ) || ! editor.presets.length ) {
			return undefined;
		}

		registerPresetButton( {
			icon: editor.icon,
			label: strings.presetsButton || 'Essential Addons presets',
		} );

		const onOpen = ( event ) => setPresetTarget( ( event.detail && event.detail.options ) || {} );

		window.addEventListener( OPEN_PRESETS_EVENT, onOpen );

		return () => window.removeEventListener( OPEN_PRESETS_EVENT, onOpen );
	}, [ editor.templateId, editor.icon, editor.presets ] );

	const onPresetChosen = useCallback( ( content ) => {
		const target = presetTarget || {};

		setPresetTarget( null );

		if ( ! insertPreset( content, target ) ) {
			notify( strings.presetFailed || 'The preset could not be inserted.' );
		}
	}, [ presetTarget ] );

	const onSaved = useCallback( ( payload ) => {
		setConditions( payload.conditions || [] );
		setHeld( null );

		// Let the next run of the command through, then re-run the publish the
		// user asked for in the first place.
		allowRef.current = true;
		resumePublish( held );
	}, [ held ] );

	const onClose = useCallback( () => {
		setHeld( null );
		notify( strings.publishCancelled || 'Publishing cancelled. The template is still a draft.' );
	}, [] );

	// Opened from the menu, the modal is not part of a save: the conditions are
	// already stored by the time it closes, so nothing is resumed and nothing is
	// left for the user to confirm.
	const onEditSaved = useCallback( ( payload ) => {
		setConditions( payload.conditions || [] );
		setEditing( false );
		notify( strings.conditionsSaved || 'Display conditions saved.' );
	}, [] );

	if ( presetTarget ) {
		return (
			<PresetLibrary
				onClose={ () => setPresetTarget( null ) }
				onInsert={ onPresetChosen }
			/>
		);
	}

	if ( editing ) {
		return (
			<ConditionsModal
				template={ {
					id: editor.templateId,
					type: editor.type,
					conditions,
				} }
				onClose={ () => setEditing( false ) }
				onSaved={ onEditSaved }
			/>
		);
	}

	if ( ! held ) {
		return null;
	}

	return (
		<ConditionsModal
			template={ {
				id: editor.templateId,
				type: editor.type,
				conditions,
			} }
			heading={ strings.publishTitle }
			intro={ strings.publishIntro }
			primaryLabel={ strings.saveAndPublish || 'Save & Publish' }
			busyLabel={ strings.publishing }
			onClose={ onClose }
			onSaved={ onSaved }
		/>
	);
}
