import { useCallback, useEffect, useRef, useState } from 'react';
import ConditionsModal from './ConditionsModal';
import { settings, strings } from '../utils/api';
import { notify, registerPublishGate, resumePublish } from '../utils/elementor';

/**
 * The condition builder as the last step of publishing.
 *
 * Publishing a header or a footer is the moment it starts replacing the theme's
 * own, so it is the moment the user is asked where that should happen. The gate
 * holds the publish command back, the modal saves the conditions, and the same
 * command is then run again — this time waved through.
 *
 * Updating an already published template is not gated: the conditions exist by
 * then, and are edited from the dashboard or by publishing a draft again.
 */
export default function EditorApp() {
	const editor = settings.editor || {};

	const [ conditions, setConditions ] = useState( () => editor.conditions || [] );
	const [ held, setHeld ] = useState( null );

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
