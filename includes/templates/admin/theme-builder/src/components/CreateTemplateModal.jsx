import { useRef, useState } from 'react';
import Modal from './Modal';
import Notice from './Notice';
import { errorMessage, request, settings, strings } from '../utils/api';

/**
 * Step 1 of the creation flow: pick a template type and name it.
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
	const titleRef = useRef( null );

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
			onClose={ onClose }
			footer={
				<button
					type="button"
					className="eatb-button eatb-button--primary"
					onClick={ submit }
					disabled={ busy }
				>
					{ busy ? ( strings.creating || 'Creating…' ) : ( strings.createButton || 'Create Template' ) }
				</button>
			}
		>
			<div className="eatb-hero">
				<h2 className="eatb-hero__title">
					{ strings.createTitle || 'Choose the type of template you want to work on' }
				</h2>
			</div>

			{ /*
			  * Never submits itself. Enter on the type <select> counts as implicit
			  * submission in every browser, which used to create the template
			  * before the name field had been touched — the typed name went
			  * nowhere and the template landed as "Untitled Header". Enter is
			  * wired to the one field where it is expected instead: the name.
			  */ }
			<form className="eatb-form" onSubmit={ ( event ) => event.preventDefault() }>
				<div className="eatb-form__field">
					<label htmlFor="eatb-template-type">{ strings.typeLabel || 'Template Type' }</label>
					<select
						id="eatb-template-type"
						value={ type }
						onChange={ ( event ) => setType( event.target.value ) }
						onKeyDown={ ( event ) => {
							if ( event.key !== 'Enter' ) {
								return;
							}

							// Confirming the dropdown moves on to naming the
							// template, it does not finish the form.
							event.preventDefault();

							if ( titleRef.current ) {
								titleRef.current.focus();
							}
						} }
					>
						<option value="">{ strings.typePlaceholder || 'Select' }</option>
						{ types.map( ( item ) => (
							<option key={ item.slug } value={ item.slug }>{ item.label }</option>
						) ) }
					</select>
				</div>

				<div className="eatb-form__field">
					<label htmlFor="eatb-template-title">{ strings.nameLabel || 'Template Name' }</label>
					<input
						id="eatb-template-title"
						type="text"
						ref={ titleRef }
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
