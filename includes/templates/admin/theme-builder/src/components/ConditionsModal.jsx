import { useState } from 'react';
import Modal from './Modal';
import ConditionRow from './ConditionRow';
import Notice from './Notice';
import { errorMessage, request, settings, strings } from '../utils/api';
import { blankCondition, summarize } from '../utils/rules';

/**
 * The condition builder — the "Edit Conditions" row action on the dashboard, and
 * the last step before a template is published from the Elementor editor.
 *
 * @param {Object}   props
 * @param {Object}   props.template     Template being edited.
 * @param {Function} props.onClose      Dismiss handler.
 * @param {Function} props.onSaved      Called with the server payload after a save.
 * @param {string}   [props.heading]    Headline above the rows.
 * @param {string}   [props.intro]      Sentence under the headline.
 * @param {string}   [props.primaryLabel] Label of the confirming button.
 * @param {string}   [props.busyLabel]  Label of that button while the save runs.
 */
export default function ConditionsModal( { template, onClose, onSaved, heading, intro, primaryLabel, busyLabel } ) {
	// A template with no conditions opens on the empty state — just "Add
	// Condition". Starting on a half-filled row would put a rule in front of the
	// user that they never chose, and at publish time that row decides where the
	// header goes.
	const [ conditions, setConditions ] = useState( () => {
		const stored = Array.isArray( template.conditions ) ? template.conditions : [];

		// A saved row is already in the shape the cascade uses; only the object
		// label is added by the server, for the picker to show without a lookup.
		return stored.map( ( condition ) => ( {
			type: condition.type || 'include',
			name: condition.name,
			sub_name: condition.sub_name || '',
			sub_id: Number( condition.sub_id ) || 0,
			sub_label: condition.sub_label || '',
		} ) );
	} );

	// Whether the template arrived with conditions. The confirm button is held
	// back on an empty set — a template with nowhere to appear is rarely what
	// anyone means — but not when the empty set is the change: removing every row
	// and saving is how conditions are cleared.
	const [ hadConditions ] = useState( () => (
		Array.isArray( template.conditions ) && template.conditions.length > 0
	) );

	const [ busy, setBusy ] = useState( false );
	const [ error, setError ] = useState( '' );
	const [ conflicts, setConflicts ] = useState( [] );
	const [ acknowledged, setAcknowledged ] = useState( false );
	const [ pending, setPending ] = useState( null );

	const update = ( index, next ) => {
		setConditions( ( rows ) => rows.map( ( row, i ) => ( i === index ? next : row ) ) );
	};

	const remove = ( index ) => {
		setConditions( ( rows ) => rows.filter( ( row, i ) => i !== index ) );
	};

	const finish = ( payload ) => {
		onSaved( payload );
	};

	const save = async () => {
		setError( '' );
		setBusy( true );

		try {
			const response = await request( 'eael_theme_builder_save_conditions', {
				template_id: template.id,
				conditions: conditions.map( ( row ) => ( {
					type: row.type,
					name: row.name,
					sub_name: row.sub_name || '',
					sub_id: row.sub_id || 0,
				} ) ),
			} );

			if ( ! response || ! response.success ) {
				setError( errorMessage( response ) );

				return;
			}

			const found = response.data.conflicts || [];

			// Overlapping templates are usually deliberate — surface them once and
			// let the user decide rather than blocking the save.
			if ( found.length && ! acknowledged ) {
				setConflicts( found );
				setAcknowledged( true );
				setPending( response.data );

				return;
			}

			finish( response.data );
		} catch ( requestError ) {
			setError( strings.genericError || 'Something went wrong. Please try again.' );
		} finally {
			setBusy( false );
		}
	};

	const confirmLabel = primaryLabel || strings.saveOnly || 'Save & Close';
	const workingLabel = busyLabel || strings.saving || 'Saving…';
	const summary = summarize( conditions );

	// "…where this header is used", from the type the template actually is.
	const typeLabel = ( Array.isArray( settings.types ) ? settings.types : [] )
		.find( ( item ) => item.slug === template.type );
	const introText = ( intro || strings.conditionsIntro || '' )
		.replace( '%s', typeLabel ? typeLabel.label.toLowerCase() : ( strings.templateWord || 'template' ) );

	return (
		<Modal
			title={ heading || strings.conditionsTitle || 'Where do you want to display this template?' }
			size="wide"
			bare={ true }
			onClose={ onClose }
			footer={
				<div className="eatb-modal__actions-split">
					<button type="button" className="eatb-button eatb-button--ghost" onClick={ onClose }>
						{ strings.notNow || 'Not now' }
					</button>

					{ pending ? (
						<button type="button" className="eatb-button eatb-button--primary" onClick={ () => finish( pending ) }>
							{ strings.continueLabel || 'Continue' }
						</button>
					) : (
						<button
							type="button"
							className="eatb-button eatb-button--primary"
							onClick={ save }
							disabled={ busy || ( ! conditions.length && ! hadConditions ) }
						>
							{ busy ? workingLabel : confirmLabel }
						</button>
					) }
				</div>
			}
		>
			<div className="eatb-hero eatb-hero--start">
				<h2 className="eatb-hero__title">
					{ heading || strings.conditionsTitle || 'Where do you want to display this template?' }
				</h2>
				<p className="eatb-hero__subtitle">{ introText }</p>
			</div>

			<div className="eatb-conditions">
				{ conditions.map( ( condition, index ) => (
					<ConditionRow
						key={ index }
						condition={ condition }
						canRemove={ true }
						onChange={ ( next ) => update( index, next ) }
						onRemove={ () => remove( index ) }
					/>
				) ) }
			</div>

			{ /*
			  * With no rows yet the button is the empty state — a dashed field the
			  * width of the rows that will replace it, so the first click lands
			  * where the first row appears. Once there are rows it steps back to
			  * an outline button under them: the rows are the content by then.
			  */ }
			<div className={ `eatb-conditions__add ${ conditions.length ? '' : 'eatb-conditions__add--empty' }`.trim() }>
				<button
					type="button"
					className="eatb-button eatb-button--add"
					onClick={ () => setConditions( ( rows ) => [ ...rows, blankCondition() ] ) }
				>
					<span className="eatb-button__plus" aria-hidden="true">+</span>
					{ strings.addCondition || 'Add condition' }
				</button>
			</div>

			<p className="eatb-conditions__summary">
				{ summary || strings.conditionsSummaryEmpty || 'No conditions yet — this template stays published but hidden until you add one.' }
			</p>

			{ error ? <Notice type="error">{ error }</Notice> : null }

			{ conflicts.length ? (
				<Notice type="warning">
					{ ( strings.conflictWarning || 'Heads up: some of these pages are already targeted by %s. The most specific template wins, then the lowest priority number, then the most recently created template.' )
						.replace( '%s', conflicts.join( ', ' ) ) }
				</Notice>
			) : null }
		</Modal>
	);
}
