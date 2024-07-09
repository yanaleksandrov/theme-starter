/**
 * Button component for remove something that should have a confirmation.
 */

import { __ } from '@wordpress/i18n';
import { useRef, useState } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { useOnClickOutside } from '../../_utils/hooks';

const ButtonWithConfirm = ( { confirmCallback, className = '', ...props } ) => {
	const [ isOpened, setIsOpened ] = useState( false );
	const tooltipRef                = useRef();

	// Merge props className with a components' className
	const toggleBtnClassName = className + ' ' + 'confirm-btn-toggle';

	// Show/hide the confirmation tooltip
	const toggleTooltip = () => {
		setIsOpened( ( state ) => ! state );
	};

	// Toggle if click is outside the tooltip element
	useOnClickOutside( tooltipRef, () => {
		setIsOpened( false );
	} );

	return (
		<div
			className="confirm-component"
			ref={ tooltipRef }
		>
			<Button
				{ ...props }
				className={ toggleBtnClassName }
				onClick={ toggleTooltip }
			/>

			{ isOpened &&
				<div className="confirm-tooltip">
					<div className="confirm-header">
						<p>{ __( 'Are you sure?', 'flade' ) }</p>
					</div>
					<div className="confirm-btns">
						<Button
							isSmall
							isSecondary
							onClick={ toggleTooltip }
						>
							{ __( 'Cancel', 'flade' ) }
						</Button>

						<Button
							isSmall
							isDestructive
							onClick={ () => {
								confirmCallback();
								toggleTooltip();
							} }
						>
							{ __( 'Confirm', 'flade' ) }
						</Button>
					</div>
				</div>
			}
		</div>
	);
};

export default ButtonWithConfirm;
