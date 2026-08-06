import TargetSelect from './TargetSelect';
import { strings } from '../utils/api';
import { firstRuleOf, getRule, groupOfRule, groupsForType, rulesForGroup } from '../utils/rules';

/**
 * One condition: Include/Exclude → group → rule → optional specific target.
 *
 * The cascade exists because the rule list grows with the site — every public
 * post type and taxonomy adds entries — and a single flat select stops being
 * usable well before that.
 *
 * @param {Object}   props
 * @param {Object}   props.condition  Row value.
 * @param {Function} props.onChange   Receives the next row value.
 * @param {Function} props.onRemove   Removes the row.
 * @param {boolean}  props.canRemove  Whether the remove control is offered.
 */
export default function ConditionRow( { condition, onChange, onRemove, canRemove } ) {
	const groups = groupsForType( condition.type );
	const rules = rulesForGroup( condition.type, condition.group );
	const rule = getRule( condition.name );
	const source = rule && rule.sub_source ? rule.sub_source : '';

	// Switching Include ⇄ Exclude can invalidate the group and the rule, since
	// not every rule can be used as an exclusion.
	const changeType = ( type ) => {
		const nextGroups = groupsForType( type );
		const group = nextGroups.some( ( item ) => item.slug === condition.group )
			? condition.group
			: ( nextGroups[ 0 ] ? nextGroups[ 0 ].slug : '' );

		const stillValid = rulesForGroup( type, group ).some( ( item ) => item.name === condition.name );
		const name = stillValid ? condition.name : firstRuleOf( type, group );

		onChange( {
			...condition,
			type,
			group,
			name,
			sub_id: stillValid ? condition.sub_id : 0,
			sub_label: stillValid ? condition.sub_label : '',
		} );
	};

	const changeGroup = ( group ) => {
		onChange( {
			...condition,
			group,
			name: firstRuleOf( condition.type, group ),
			sub_id: 0,
			sub_label: '',
		} );
	};

	const changeRule = ( name ) => {
		onChange( {
			...condition,
			name,
			group: groupOfRule( name ) || condition.group,
			sub_id: 0,
			sub_label: '',
		} );
	};

	return (
		<div className={ `eatb-condition ${ source ? 'eatb-condition--targeted' : '' }` }>
			<label className="eatb-field eatb-field--type">
				<span className="screen-reader-text">{ strings.include || 'Include' }</span>
				<select value={ condition.type } onChange={ ( event ) => changeType( event.target.value ) }>
					<option value="include">{ strings.include || 'Include' }</option>
					<option value="exclude">{ strings.exclude || 'Exclude' }</option>
				</select>
			</label>

			<label className="eatb-field eatb-field--group">
				<span className="screen-reader-text">{ strings.groupLabel || 'Condition group' }</span>
				<select value={ condition.group } onChange={ ( event ) => changeGroup( event.target.value ) }>
					{ groups.map( ( group ) => (
						<option key={ group.slug } value={ group.slug }>{ group.label }</option>
					) ) }
				</select>
			</label>

			<label className="eatb-field eatb-field--rule">
				<span className="screen-reader-text">{ strings.ruleLabel || 'Condition' }</span>
				<select value={ condition.name } onChange={ ( event ) => changeRule( event.target.value ) }>
					{ rules.map( ( item ) => (
						<option key={ item.name } value={ item.name }>{ item.label }</option>
					) ) }
				</select>
			</label>

			{ source ? (
				<div className="eatb-field eatb-field--target">
					<TargetSelect
						source={ source }
						value={ condition.sub_id }
						label={ condition.sub_label }
						onChange={ ( id, text ) => onChange( { ...condition, sub_id: id, sub_label: text } ) }
					/>
				</div>
			) : null }

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
