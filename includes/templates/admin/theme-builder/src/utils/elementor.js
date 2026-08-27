/**
 * Bridge to the Elementor editor.
 *
 * The publish gate is a data *dependency* hook: of the four hook types Elementor
 * exposes, it is the only one whose callback can stop the command it is attached
 * to — returning `false` makes the web-cli throw a `HookBreak`, so
 * `document/save/publish` never reaches the save request. UI hooks and `after`
 * hooks both run too late to hold a publish back.
 *
 * It is attached to `document/save/publish` rather than to the publish button:
 * Elementor's top bar, its panel footer and the keyboard shortcut all funnel
 * into the same command, and the markup around them changes between releases.
 */

const HOOK_ID = 'eael-theme-builder-conditions';

/**
 * Whether the command API is loaded and exposes the hook base we extend.
 *
 * @return {boolean} True when hooks can be registered.
 */
function canRegister() {
	return !! (
		window.$e &&
		window.$e.modules &&
		window.$e.modules.hookData &&
		window.$e.modules.hookData.Dependency
	);
}

/**
 * ID of the document a save command is acting on.
 *
 * @param {Object} args Command arguments.
 *
 * @return {number} Document ID, 0 when it cannot be resolved.
 */
function documentId( args ) {
	const document = ( args && args.document ) ||
		( window.elementor && window.elementor.documents && window.elementor.documents.getCurrent() );

	return document ? Number( document.id ) : 0;
}

/**
 * Hold back `document/save/publish` until a callback allows it through.
 *
 * @param {number}   templateId Template the gate applies to.
 * @param {Function} onPublish  Receives the command arguments; return true to let
 *                              the publish run, false to hold it back.
 */
export function registerPublishGate( templateId, onPublish ) {
	const attach = () => {
		if ( ! canRegister() ) {
			return;
		}

		class PublishGate extends window.$e.modules.hookData.Dependency {
			getCommand() {
				return 'document/save/publish';
			}

			getId() {
				return HOOK_ID;
			}

			getConditions( args ) {
				// Elementor keeps the kit and the global settings open as documents
				// of their own; only the template being edited is gated.
				return documentId( args ) === Number( templateId );
			}

			apply( args ) {
				return onPublish( args );
			}
		}

		try {
			new PublishGate().register();
		} catch ( error ) {
			// A duplicate ID is the only way this throws, and it means the gate is
			// already in place. Publishing must not break over it.
		}
	};

	if ( canRegister() ) {
		attach();

		return;
	}

	window.addEventListener( 'elementor/init', attach, { once: true } );
}

/**
 * Run the publish the gate held back.
 *
 * @param {Object} args Arguments the gate was called with.
 */
export function resumePublish( args ) {
	if ( window.$e ) {
		window.$e.run( 'document/save/publish', args || {} );
	}
}

/**
 * Show a message in the editor's own toast area.
 *
 * @param {string} message Text to show.
 */
export function notify( message ) {
	if ( window.elementor && window.elementor.notifications ) {
		window.elementor.notifications.showToast( { message } );
	}
}
