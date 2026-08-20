/**
 * Helpers over the condition registry.
 *
 * The registry is built server side (Conditions\Rules) on Elementor's Theme
 * Builder model, and the shape mirrors it:
 *
 *   conditions[ name ] = { name, label, all_label, sub_conditions: [], has_source }
 *
 * A row is `type` / `name` / `sub_name` / `sub_id`, where `name` is one of the
 * three top level conditions and `sub_name` is anything nested under it — one or
 * two levels deep, which the second select renders as optgroups.
 */

import { settings, strings } from './api';

export const conditions = settings.conditions || {};
export const topLevel = Array.isArray( settings.topLevel ) ? settings.topLevel : [];

/**
 * One condition definition.
 *
 * @param {string} name Condition name.
 *
 * @return {Object|null} The condition, or null when it is not registered.
 */
export function getCondition( name ) {
	return name && conditions[ name ] ? conditions[ name ] : null;
}

/**
 * The options of the sub-condition select, in Elementor's shape.
 *
 * Every entry is either a plain option or an optgroup. A sub-condition that has
 * sub-conditions of its own becomes a group whose first option is the
 * sub-condition itself — "Posts" heading a group of "In Category", "In Tag",
 * "Posts by Author" — so two levels of nesting fit in one select.
 *
 * @param {string} name Top level condition name.
 *
 * @return {Array} `[ { type: 'option'|'group', … } ]`.
 */
export function subConditionOptions( name ) {
	const parent = getCondition( name );

	if ( ! parent ) {
		return [];
	}

	const options = [
		{ type: 'option', value: '', label: parent.all_label },
	];

	parent.sub_conditions.forEach( ( child ) => {
		const condition = getCondition( child );

		if ( ! condition ) {
			return;
		}

		if ( ! condition.sub_conditions.length ) {
			options.push( { type: 'option', value: child, label: condition.label } );

			return;
		}

		options.push( {
			type: 'group',
			label: condition.all_label,
			options: [
				{ value: child, label: condition.all_label },
				...condition.sub_conditions
					.filter( ( grandchild ) => getCondition( grandchild ) )
					.map( ( grandchild ) => ( {
						value: grandchild,
						label: getCondition( grandchild ).label,
					} ) ),
			],
		} );
	} );

	return options;
}

/**
 * Whether a sub-condition can be narrowed down to one object.
 *
 * @param {string} name Sub-condition name.
 *
 * @return {boolean} True when it offers an object picker.
 */
export function hasTarget( name ) {
	const condition = getCondition( name );

	return !! ( condition && condition.has_source );
}

/**
 * Whether a sub-condition is still reachable under a top level one.
 *
 * @param {string} name    Top level condition.
 * @param {string} subName Sub-condition, '' for "all of it".
 *
 * @return {boolean} True when the pair is valid.
 */
export function isValidPair( name, subName ) {
	if ( ! subName ) {
		return true;
	}

	return subConditionOptions( name ).some( ( option ) => (
		option.type === 'option'
			? option.value === subName
			: option.options.some( ( item ) => item.value === subName )
	) );
}

/**
 * The label a row reads as, used by the summary line.
 *
 * @param {Object} condition Row value.
 *
 * @return {string} Human readable label.
 */
export function labelOf( condition ) {
	const sub = getCondition( condition.sub_name );

	if ( sub ) {
		return sub.sub_conditions.length ? sub.all_label : sub.label;
	}

	const parent = getCondition( condition.name );

	return parent ? parent.all_label : '';
}

/**
 * What the rows add up to, in one line.
 *
 * Written from the rows as they stand rather than from the last save, so the
 * line answers the question the dialog asks — where will this end up — while the
 * user is still deciding. The object a row targets is deliberately left out: the
 * line is a summary, and a row that names one already reads it back in its own
 * picker.
 *
 * @param {Array} rows Condition rows.
 *
 * @return {string} Human readable summary, empty when there is nothing to say.
 */
export function summarize( rows ) {
	return rows
		.map( ( row ) => {
			const label = labelOf( row );

			if ( ! label ) {
				return '';
			}

			const template = 'exclude' === row.type
				? ( strings.conditionsHidden || 'Hidden on %s' )
				: ( strings.conditionsShown || 'Shown on %s' );

			return template.replace( '%s', label );
		} )
		.filter( Boolean )
		.join( ' · ' );
}

/**
 * A new, empty row.
 *
 * It starts on "Entire Site" — the first option of the first select — rather
 * than on nothing, because every select in the cascade always has a value.
 *
 * @return {Object} Row value.
 */
export function blankCondition() {
	return {
		type: 'include',
		name: topLevel.length ? topLevel[ 0 ].name : 'general',
		sub_name: '',
		sub_id: 0,
		sub_label: '',
	};
}

/**
 * Label for the "no specific object" option of a target picker.
 *
 * @return {string} Translated "All".
 */
export function allLabel() {
	return strings.allLabel || strings.all || 'All';
}
