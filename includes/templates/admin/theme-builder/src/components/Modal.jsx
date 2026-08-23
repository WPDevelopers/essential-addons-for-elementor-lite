import { useEffect, useRef } from 'react';
import { strings } from '../utils/api';

/**
 * Modal shell: title bar, scrollable body, action bar.
 *
 * Closes on overlay click and on Escape, restores focus to whatever opened it,
 * and keeps Tab inside the dialog while it is open.
 *
 * @param {Object}   props
 * @param {string}   props.title       Text in the title bar.
 * @param {string}   props.size        `default` or `wide`.
 * @param {Function} props.onClose     Called when the user dismisses the dialog.
 * @param {Node}     props.footer      Action bar contents.
 * @param {string}   [props.bodyClass] Extra class on the body, for dialogs that
 *                                     lay their content out themselves.
 * @param {string}   [props.className] Extra class on the dialog's root, for a
 *                                     single dialog that needs to size itself.
 * @param {boolean}  [props.bare]      Drop the title bar and float the close
 *                                     button, for a dialog whose own heading is
 *                                     the first thing in its body.
 * @param {Object}   [props.brand]     `{ icon, label }` to head the dialog with
 *                                     the product mark. With a `label` the mark
 *                                     leads an eyebrow line and the bar loses
 *                                     its rule; without one it simply sits
 *                                     beside the title.
 * @param {Node}     props.children    Body contents.
 */
export default function Modal( { title, size = 'default', onClose, footer, bodyClass = '', brand = null, bare = false, className = '', children } ) {
	const dialogRef = useRef( null );
	const openerRef = useRef( null );

	useEffect( () => {
		openerRef.current = document.activeElement;
		document.body.classList.add( 'eatb-modal-open' );

		const dialog = dialogRef.current;
		const focusable = dialog.querySelector( 'input, select, button, [tabindex]' );

		if ( focusable ) {
			focusable.focus();
		}

		const onKeyDown = ( event ) => {
			if ( event.key === 'Escape' ) {
				// A dropdown inside the dialog gets the key first: Escape closes
				// the list, not the dialog behind it. This listener is on the
				// document in the capture phase — it runs before the control the
				// key was pressed on, so the control cannot call this off, and the
				// dialog has to ask instead. The control's own handler still runs
				// afterwards and closes its list.
				if ( dialog.querySelector( '.eatb-select__list, .eatb-target__panel' ) ) {
					return;
				}

				event.stopPropagation();
				onClose();

				return;
			}

			if ( event.key !== 'Tab' ) {
				return;
			}

			const items = [ ...dialog.querySelectorAll(
				'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
			) ].filter( ( node ) => node.offsetParent !== null );

			if ( ! items.length ) {
				return;
			}

			const first = items[ 0 ];
			const last = items[ items.length - 1 ];

			if ( event.shiftKey && document.activeElement === first ) {
				event.preventDefault();
				last.focus();
			} else if ( ! event.shiftKey && document.activeElement === last ) {
				event.preventDefault();
				first.focus();
			}
		};

		document.addEventListener( 'keydown', onKeyDown, true );

		return () => {
			document.removeEventListener( 'keydown', onKeyDown, true );
			document.body.classList.remove( 'eatb-modal-open' );

			if ( openerRef.current && typeof openerRef.current.focus === 'function' ) {
				openerRef.current.focus();
			}
		};
	}, [ onClose ] );

	// Two headings, one mark: the eyebrow leads a dialog that introduces the
	// feature, the title one that is already inside it.
	const mark = brand ? ( brand.label ? 'eatb-modal--branded' : 'eatb-modal--marked' ) : ( bare ? 'eatb-modal--bare' : '' );

	const close = (
		<button
			type="button"
			className="eatb-modal__close"
			onClick={ onClose }
			aria-label={ strings.close || 'Close' }
		>
			<span aria-hidden="true">&times;</span>
		</button>
	);

	return (
		<div className={ `eatb-modal eatb-modal--${ size } ${ mark } ${ className }`.replace( /\s+/g, ' ' ).trim() }>
			<div className="eatb-modal__overlay" onClick={ onClose } />

			<div
				className="eatb-modal__dialog"
				role="dialog"
				aria-modal="true"
				aria-label={ title }
				ref={ dialogRef }
			>
				{ bare ? close : (
					<header className="eatb-modal__bar">
						{ brand ? (
							<span className="eatb-modal__brand">
								{ brand.icon ? (
									<img className="eatb-modal__brand-mark" src={ brand.icon } alt="" aria-hidden="true" />
								) : null }

								{ brand.label ? (
									<span className="eatb-modal__brand-label">{ brand.label }</span>
								) : (
									<span className="eatb-modal__bar-title">{ title }</span>
								) }
							</span>
						) : (
							<span className="eatb-modal__bar-title">{ title }</span>
						) }

						{ close }
					</header>
				) }

				<div className={ `eatb-modal__body ${ bodyClass }`.trim() }>{ children }</div>

				{ footer ? <footer className="eatb-modal__actions">{ footer }</footer> : null }
			</div>
		</div>
	);
}
