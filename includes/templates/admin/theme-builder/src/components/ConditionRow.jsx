import Select from './Select';
import TargetSelect from './TargetSelect';
import { strings } from '../utils/api';
import { hasTarget, isValidPair, subConditionOptions, topLevel } from '../utils/rules';

/**
 * One condition: Include/Exclude → Entire Site | Archives | Singular → what
 * inside it → which object.
 *
 * The cascade is Elementor's Theme Builder, select for select: users arrive
 * here already knowing that vocabulary, and the sub-condition select carries two
 * levels of nesting as optgroups — "Posts" heading "In Category", "In Tag" — so
 * the list stays navigable on a site with a dozen post types.
 *
 * Selects only appear once they have something to say: "Entire Site" needs no
 * sub-condition, and a sub-condition that targets a whole view needs no object.
 *
 * @param {Object}   props
 * @param {Object}   props.condition  Row value.
 * @param {Function} props.onChange   Receives the next row value.
 * @param {Function} props.onRemove   Removes the row.
 * @param {boolean}  props.canRemove  Whether the remove control is offered.
 */
export default function ConditionRow( { condition, onChange, onRemove, canRemove } ) {
	const options = subConditionOptions( condition.name );

	// "Entire Site" is the whole answer, so it has no second select. Anything
	// else offers at least its own "All …" option.
	const showSub = options.length > 1;
	const showTarget = hasTarget( condition.sub_name );

	const changeName = ( name ) => {
		// Keep the sub-condition when it exists under the new top level too —
		// switching Include ⇄ Exclude must never silently retarget a row, and the
		// same goes for a name change that happens to keep it valid.
		const keep = isValidPair( name, condition.sub_name );

		onChange( {
			...condition,
			name,
			sub_name: keep ? condition.sub_name : '',
			sub_id: keep ? condition.sub_id : 0,
			sub_label: keep ? condition.sub_label : '',
		} );
	};

	const changeSubName = ( subName ) => {
		onChange( {
			...condition,
			sub_name: subName,
			sub_id: 0,
			sub_label: '',
		} );
	};

	return (
		<div className={ `eatb-condition ${ showTarget ? 'eatb-condition--targeted' : '' }` }>
			{ /*
			  * One box per decision, each naming itself to a screen reader — the
			  * boxes carry no visible label, and "Include · Singular · Post" only
			  * reads as a sentence to someone who can see all three at once.
			  */ }
			<div className="eatb-condition__fields">
				<div className="eatb-field eatb-field--type">
					<Select
						label={ strings.conditionTypeLabel || 'Include or exclude' }
						options={ [
							{ value: 'include', label: strings.include || 'Include' },
							{ value: 'exclude', label: strings.exclude || 'Exclude' },
						] }
						value={ condition.type }
						onChange={ ( type ) => onChange( { ...condition, type } ) }
					/>
				</div>

				<div className="eatb-field eatb-field--group">
					<Select
						label={ strings.groupLabel || 'Condition' }
						options={ topLevel.map( ( item ) => ( { value: item.name, label: item.label } ) ) }
						value={ condition.name }
						onChange={ changeName }
					/>
				</div>

				{ showSub ? (
					<div className="eatb-field eatb-field--rule">
						<Select
							label={ strings.ruleLabel || 'Sub condition' }
							options={ options }
							value={ condition.sub_name }
							onChange={ changeSubName }
						/>
					</div>
				) : null }

				{ showTarget ? (
					<div className="eatb-field eatb-field--target">
						<TargetSelect
							condition={ condition.sub_name }
							value={ condition.sub_id }
							label={ condition.sub_label }
							onChange={ ( id, text ) => onChange( { ...condition, sub_id: id, sub_label: text } ) }
						/>
					</div>
				) : null }
			</div>

			<button
				type="button"
				className="eatb-condition__remove"
				onClick={ onRemove }
				disabled={ ! canRemove }
				aria-label={ strings.removeCondition || 'Remove condition' }
				title={ strings.removeCondition || 'Remove condition' }
			>
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	);
}
