import Select from './Select';
import { strings } from '../utils/api';

/**
 * The glyph that stands for a template type.
 *
 * A header and a footer are the same rectangle with the weight at opposite
 * ends, which is the whole distinction — so they are drawn as one shape with a
 * band that moves, rather than as two unrelated icons.
 *
 * @param {string} slug Template type slug.
 *
 * @return {Node} An SVG, or null for a type with no glyph of its own.
 */
function typeIcon( slug ) {
	if ( 'header' !== slug && 'footer' !== slug ) {
		return null;
	}

	return (
		<svg
			className="eatb-select__icon"
			width="20"
			height="20"
			viewBox="0 0 20 20"
			fill="none"
			stroke="currentColor"
			strokeWidth="1.4"
			strokeLinecap="round"
			aria-hidden="true"
			focusable="false"
		>
			<rect x="2.5" y="3.5" width="15" height="13" rx="2.5" />
			{ 'header' === slug ? <path d="M2.5 7.5h15" /> : <path d="M2.5 12.5h15" /> }
		</svg>
	);
}

/**
 * Template type picker.
 *
 * The list itself is the shared dropdown; what belongs to this one is the glyph
 * each type carries, which is the reason it cannot be a `<select>`.
 *
 * @param {Object}   props
 * @param {Array}    props.types    `{ slug, label }` options.
 * @param {string}   props.value    Selected slug, empty for none.
 * @param {Function} props.onChange Receives the chosen slug.
 * @param {string}   props.id       ID the field's label points at.
 */
export default function TypeSelect( { types, value, onChange, id } ) {
	return (
		<Select
			id={ id }
			options={ types.map( ( item ) => ( { value: item.slug, label: item.label } ) ) }
			value={ value }
			onChange={ onChange }
			placeholder={ strings.typePlaceholder || 'Choose a template type' }
			renderIcon={ typeIcon }
		/>
	);
}
