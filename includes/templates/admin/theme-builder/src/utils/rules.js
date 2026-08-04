/**
 * Helpers over the condition rule registry.
 *
 * The registry is built server side (Conditions\Rules) from three layers: core
 * rules, rules derived from the site's post types and taxonomies, and anything
 * a third party added. This module only reads it — the shape is:
 *
 *   { name, label, group, sub_source, sub_source_type, supports: [] }
 */

import { settings } from './api';

export const allRules = Array.isArray( settings.rules ) ? settings.rules : [];
export const groupLabels = settings.groups || {};

/**
 * Rules offering a given condition type.
 *
 * @param {string} type `include` or `exclude`.
 *
 * @return {Array} Matching rules.
 */
export function rulesForType( type ) {
	return allRules.filter( ( rule ) => rule.supports.includes( type ) );
}

/**
 * Groups that still have at least one rule for a condition type.
 *
 * Not every rule can be used as an exclusion, so switching a row to "Exclude"
 * can empty a whole group — it must then disappear from the group select.
 *
 * @param {string} type `include` or `exclude`.
 *
 * @return {Array} `[ { slug, label } ]` in registry order.
 */
export function groupsForType( type ) {
	const available = rulesForType( type );
	const seen = [];

	// Ordered by the server's group list first, then anything a third-party rule
	// introduced under an unregistered group.
	Object.keys( groupLabels ).forEach( ( slug ) => {
		if ( available.some( ( rule ) => rule.group === slug ) ) {
			seen.push( { slug, label: groupLabels[ slug ] } );
		}
	} );

	available.forEach( ( rule ) => {
		if ( ! groupLabels[ rule.group ] && ! seen.some( ( g ) => g.slug === rule.group ) ) {
			seen.push( { slug: rule.group, label: rule.group } );
		}
	} );

	return seen;
}

/**
 * Rules inside one group for a condition type.
 *
 * @param {string} type  `include` or `exclude`.
 * @param {string} group Group slug.
 *
 * @return {Array} Matching rules.
 */
export function rulesForGroup( type, group ) {
	return rulesForType( type ).filter( ( rule ) => rule.group === group );
}

/**
 * A rule by name.
 *
 * @param {string} name Rule name.
 *
 * @return {Object|null} The rule, or null when it is no longer registered.
 */
export function getRule( name ) {
	return allRules.find( ( rule ) => rule.name === name ) || null;
}

/**
 * The group a rule belongs to.
 *
 * @param {string} name Rule name.
 *
 * @return {string} Group slug, empty when the rule is unknown.
 */
export function groupOfRule( name ) {
	const rule = getRule( name );

	return rule ? rule.group : '';
}

/**
 * Whether a rule can be narrowed to a single object.
 *
 * @param {string} name Rule name.
 *
 * @return {boolean} True when the rule declares a sub-source.
 */
export function hasTarget( name ) {
	const rule = getRule( name );

	return !! ( rule && rule.sub_source );
}

/**
 * The first rule of a group, used when a row's group changes.
 *
 * @param {string} type  `include` or `exclude`.
 * @param {string} group Group slug.
 *
 * @return {string} Rule name, empty when the group has none.
 */
export function firstRuleOf( type, group ) {
	const rules = rulesForGroup( type, group );

	return rules.length ? rules[ 0 ].name : '';
}

/**
 * Build a blank condition row.
 *
 * @return {Object} Row seeded with the catch-all rule when it is available.
 */
export function blankCondition() {
	const fallback = rulesForType( 'include' )[ 0 ];
	const name = getRule( 'entire_site' ) ? 'entire_site' : ( fallback ? fallback.name : '' );

	return {
		type: 'include',
		group: groupOfRule( name ),
		name,
		sub_id: 0,
		sub_label: '',
	};
}
