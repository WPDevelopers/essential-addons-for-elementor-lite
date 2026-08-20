import { useState } from 'react';
import Modal from './Modal';
import Notice from './Notice';
import TypeSelect from './TypeSelect';
import { errorMessage, request, settings, strings } from '../utils/api';

/**
 * Step 1 of the creation flow: pick a template type and name it.
 *
 * Headed by the product mark rather than a title bar — this is the first thing
 * the feature shows, so it says whose it is — and led by a line about what
 * templates are for. The type is the decision the dialog exists to take, so it
 * comes first and the primary action stays disabled until it is made.
 *
 * @param {Object}   props
 * @param {Function} props.onClose   Dismiss handler.
 * @param {Function} props.onCreated Receives the created template payload.
 */
export default function CreateTemplateModal( { onClose, onCreated } ) {
	const types = Array.isArray( settings.types ) ? settings.types : [];

	const [ type, setType ] = useState( '' );
	const [ title, setTitle ] = useState( '' );
	const [ busy, setBusy ] = useState( false );
	const [ error, setError ] = useState( '' );

	const submit = async ( event ) => {
		if ( event ) {
			event.preventDefault();
		}

		if ( busy ) {
			return;
		}

		setError( '' );

		if ( ! type ) {
			setError( strings.chooseType || 'Please choose a valid template type.' );

			return;
		}

		setBusy( true );

		try {
			const response = await request( 'eael_theme_builder_create_template', {
				template_type: type,
				template_title: title,
			} );

			if ( ! response || ! response.success ) {
				setError( errorMessage( response ) );

				return;
			}

			onCreated( response.data );
		} catch ( requestError ) {
			setError( strings.genericError || 'Something went wrong. Please try again.' );
		} finally {
			setBusy( false );
		}
	};

	return (
		<Modal
			title={ strings.templatesTitle || 'Templates' }
			brand={ {
				icon: settings.icon,
				label: strings.brandLabel || 'Essential Addons · Theme Builder',
			} }
			onClose={ onClose }
			footer={
				<div className="eatb-modal__actions-split">
					<button type="button" className="eatb-button eatb-button--ghost" onClick={ onClose }>
						{ strings.cancel || 'Cancel' }
					</button>

					<button
						type="button"
						className="eatb-button eatb-button--primary"
						onClick={ submit }
						disabled={ busy || ! type }
					>
						{ busy ? ( strings.creating || 'Creating…' ) : ( strings.createButton || 'Create Template' ) }
					</button>
				</div>
			}
		>
			<div className="eatb-hero eatb-hero--start">
				<h2 className="eatb-hero__title">
					{ strings.createTitle || 'Templates Help You Build Faster' }
				</h2>

				<p className="eatb-hero__subtitle">
					{ strings.createIntro || 'Create reusable templates for different sections of your website and save time by using them whenever you need.' }
				</p>
			</div>

			{ /*
			  * Never submits itself. Enter on the type field counts as implicit
			  * submission in every browser, which used to create the template
			  * before the name field had been touched — the typed name went
			  * nowhere and the template landed as "Untitled Header". Enter is
			  * wired to the one field where it is expected instead: the name.
			  */ }
			<form className="eatb-form eatb-form--wide" onSubmit={ ( event ) => event.preventDefault() }>
				<div className="eatb-form__field">
					<label htmlFor="eatb-template-type">{ strings.typeLabel || 'Template type' }</label>
					<TypeSelect
						id="eatb-template-type"
						types={ types }
						value={ type }
						onChange={ setType }
					/>
				</div>

				<div className="eatb-form__field">
					<label htmlFor="eatb-template-title">{ strings.nameLabel || 'Template name' }</label>
					<input
						id="eatb-template-title"
						type="text"
						value={ title }
						placeholder={ strings.namePlaceholder || 'Name your template' }
						onChange={ ( event ) => setTitle( event.target.value ) }
						onKeyDown={ ( event ) => {
							if ( event.key === 'Enter' ) {
								event.preventDefault();
								submit();
							}
						} }
					/>
				</div>

				{ error ? <Notice type="error">{ error }</Notice> : null }
			</form>
		</Modal>
	);
}
