import { useState } from 'react';
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

	const submit = async ( event ) => {
		event.preventDefault();
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

			<form className="eatb-form" onSubmit={ submit }>
				<div className="eatb-form__field">
					<label htmlFor="eatb-template-type">{ strings.typeLabel || 'Template Type' }</label>
					<select
						id="eatb-template-type"
						value={ type }
						onChange={ ( event ) => setType( event.target.value ) }
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
						value={ title }
						placeholder={ strings.namePlaceholder || 'Name your template' }
						onChange={ ( event ) => setTitle( event.target.value ) }
					/>
				</div>

				{ error ? <Notice type="error">{ error }</Notice> : null }

				{ /* Lets Enter submit without nesting a second form in the page. */ }
				<button type="submit" className="screen-reader-text" tabIndex={ -1 }>
					{ strings.createButton || 'Create Template' }
				</button>
			</form>
		</Modal>
	);
}
