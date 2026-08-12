import { useState } from 'react';
import Modal from './Modal';
import ConditionRow from './ConditionRow';
import Notice from './Notice';
import { errorMessage, request, strings } from '../utils/api';
import { blankCondition, groupOfRule } from '../utils/rules';

/**
 * Step 2 of the creation flow, and the "Edit Conditions" row action.
 *
 * @param {Object}   props
 * @param {Object}   props.template Template being edited.
 * @param {Function} props.onClose  Dismiss handler.
 * @param {Function} props.onSaved  Called with the server payload after a save.
 */
export default function ConditionsModal( { template, onClose, onSaved } ) {
	const [ conditions, setConditions ] = useState( () => {
		const stored = Array.isArray( template.conditions ) ? template.conditions : [];

		if ( ! stored.length ) {
			return [ blankCondition() ];
		}

		// Saved conditions only carry the rule name; the group is derived so the
		// cascade can preselect it.
		return stored.map( ( condition ) => ( {
			type: condition.type || 'include',
			name: condition.name,
			group: groupOfRule( condition.name ),
			sub_id: Number( condition.sub_id ) || 0,
			sub_label: condition.sub_label || '',
		} ) );
	} );

	const [ busy, setBusy ] = useState( false );
	const [ error, setError ] = useState( '' );
	const [ conflicts, setConflicts ] = useState( [] );
	const [ acknowledged, setAcknowledged ] = useState( false );
	const [ pending, setPending ] = useState( null );

	const update = ( index, next ) => {
		setConditions( ( rows ) => rows.map( ( row, i ) => ( i === index ? next : row ) ) );
	};

	const remove = ( index ) => {
		setConditions( ( rows ) => {
			const next = rows.filter( ( row, i ) => i !== index );

			return next.length ? next : [ blankCondition() ];
		} );
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

	const primaryLabel = template.isNew
		? ( strings.saveConditions || 'Save & Edit Template' )
		: ( strings.saveOnly || 'Save Conditions' );

	return (
		<Modal
			title={ strings.templatesTitle || 'Templates' }
			size="wide"
			onClose={ onClose }
			footer={
				<>
					{ pending ? (
						<button type="button" className="eatb-button eatb-button--primary" onClick={ () => finish( pending ) }>
							{ strings.continueLabel || 'Continue' }
						</button>
					) : (
						<button
							type="button"
							className="eatb-button eatb-button--primary"
							onClick={ save }
							disabled={ busy }
						>
							{ busy ? ( strings.saving || 'Saving…' ) : primaryLabel }
						</button>
					) }
				</>
			}
		>
			<div className="eatb-hero">
				<h2 className="eatb-hero__title">
					{ strings.conditionsTitle || 'Where Do You Want to Display This Template' }
				</h2>
				<p className="eatb-hero__subtitle">{ strings.conditionsIntro }</p>
			</div>

			<div className="eatb-conditions">
				{ conditions.map( ( condition, index ) => (
					<ConditionRow
						key={ index }
						condition={ condition }
						canRemove={ conditions.length > 1 }
						onChange={ ( next ) => update( index, next ) }
						onRemove={ () => remove( index ) }
					/>
				) ) }
			</div>

			<div className="eatb-conditions__add">
				<button
					type="button"
					className="eatb-button eatb-button--secondary"
					onClick={ () => setConditions( ( rows ) => [ ...rows, blankCondition() ] ) }
				>
					{ strings.addCondition || 'Add Condition' }
				</button>
			</div>

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
