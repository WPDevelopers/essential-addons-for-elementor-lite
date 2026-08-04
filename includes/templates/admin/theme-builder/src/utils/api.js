/**
 * Bridge to the data localized by Admin::enqueue_assets() and to admin-ajax.
 */

const config = window.eaelThemeBuilder || {};

export const settings = config;
export const strings = config.i18n || {};

/**
 * Serialize a nested object the way PHP expects it in $_POST.
 *
 * `{ conditions: [ { type: 'include' } ] }` becomes
 * `conditions[0][type]=include`, matching what the AJAX endpoints read.
 *
 * @param {URLSearchParams} params Target.
 * @param {Object}          data   Source.
 * @param {string}          prefix Parent key, empty at the top level.
 */
function appendParams( params, data, prefix = '' ) {
	Object.entries( data ).forEach( ( [ key, value ] ) => {
		if ( value === null || value === undefined ) {
			return;
		}

		const name = prefix ? `${ prefix }[${ key }]` : key;

		if ( typeof value === 'object' ) {
			appendParams( params, value, name );

			return;
		}

		params.append( name, value );
	} );
}

/**
 * POST to admin-ajax with the shared nonce.
 *
 * @param {string} action WordPress AJAX action, without the wp_ajax_ prefix.
 * @param {Object} data   Payload.
 *
 * @return {Promise<Object>} Resolves with `{ success, data }`.
 */
export async function request( action, data = {} ) {
	const params = new URLSearchParams();

	params.append( 'action', action );
	params.append( 'nonce', config.nonce || '' );
	appendParams( params, data );

	const response = await fetch( config.ajaxUrl, {
		method: 'POST',
		credentials: 'same-origin',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
		body: params.toString(),
	} );

	if ( ! response.ok ) {
		throw new Error( `HTTP ${ response.status }` );
	}

	return response.json();
}

/**
 * Message from a failed AJAX response, with a readable fallback.
 *
 * @param {Object} response Parsed response.
 *
 * @return {string} Message.
 */
export function errorMessage( response ) {
	return response && response.data && response.data.message
		? response.data.message
		: strings.genericError || 'Something went wrong. Please try again.';
}
