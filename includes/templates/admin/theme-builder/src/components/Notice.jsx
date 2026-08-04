/**
 * Inline message inside a modal body.
 *
 * @param {Object} props
 * @param {string} props.type     `error` or `warning`.
 * @param {Node}   props.children Message.
 */
export default function Notice( { type = 'error', children } ) {
	return (
		<p className={ `eatb-notice eatb-notice--${ type }` } role={ type === 'error' ? 'alert' : 'status' }>
			{ children }
		</p>
	);
}
