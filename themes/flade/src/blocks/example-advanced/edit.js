/**
 * WordPress dependencies
 */

import json from './block.json';
import { __ } from '@wordpress/i18n';
import { Button, PanelBody, ResponsiveWrapper, Spinner, TextareaControl, ToggleControl } from '@wordpress/components';
import { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } from '@wordpress/block-editor';
import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import ServerSideRender from '@wordpress/server-side-render';
import ButtonWithConfirm from '../_components/button-with-confirm';

const { name }          = json;
const allowedMediaTypes = [ 'image' ];
const instructions      = <p>{ __( 'To edit the image, you need permission to upload media.', 'flade' ) }</p>;

const Edit = ( props ) => {
	const { attributes, setAttributes, clientId, editorPostTitle, promoImageMedia } = props;

	const { promoImage, postTitle, showDate, excerptText } = attributes;

	// Update block title from the post title
	if ( postTitle !== editorPostTitle ) {
		setAttributes( { postTitle: editorPostTitle } );
	}

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Block Settings', 'flade' ) }
					initialOpen={ true }
				>
					<div className="components-base-control">
						<MediaUploadCheck fallback={ instructions }>
							<MediaUpload
								title={ __( 'Promo Image', 'flade' ) }
								onSelect={ ( image ) => setAttributes( { promoImage: image.id } ) }
								allowedTypes={ allowedMediaTypes }
								value={ promoImage }
								render={ ( { open } ) => (
									<>
										<label
											className="components-base-control__label"
											htmlFor={ `${ clientId }_promo_image` }
										>
											{ __( 'Promo Image', 'flade' ) }
										</label>
										<div className="confirm-container">
											<Button
												id={ `${ clientId }_promo_image` }
												className={ `${ ! promoImage
													? 'editor-post-featured-image__toggle'
													: 'editor-post-featured-image__preview'
												}` }
												onClick={ open }
											>
												{ ! promoImage && ( __( 'Set Promo Image', 'flade' ) ) }
												{ !! promoImage && ! promoImageMedia && <Spinner/> }
												{ !! promoImage && promoImageMedia && <ResponsiveWrapper
													naturalWidth={ promoImageMedia?.media_details.width }
													naturalHeight={ promoImageMedia?.media_details.height }
												>
													<img
														src={ promoImageMedia?.source_url }
														alt={ __( 'Promo Image', 'flade' ) }
													/>
												</ResponsiveWrapper> }
											</Button>
											{ !! promoImage &&
												<ButtonWithConfirm
													confirmCallback={ () => setAttributes( { promoImage: undefined } ) }
													isLink
													isDestructive
													className="btn-image-remove"
													icon="trash"
													label={ __( 'Remove image', 'flade' ) }
												/>
											}
										</div>
									</>
								) }
							/>
						</MediaUploadCheck>
					</div>

					<ToggleControl
						label={ __( 'Show Date', 'flade' ) }
						checked={ showDate }
						onChange={ ( value ) => setAttributes( { showDate: value } ) }
					/>

					<TextareaControl
						label={ __( 'Excerpt Text', 'flade' ) }
						value={ excerptText }
						onChange={ ( excerptText ) => setAttributes( { excerptText } ) }
						rows="8"
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps() }>
				<ServerSideRender
					block={ name }
					httpMethod="POST"
					attributes={ { ...attributes } }
					urlQueryArgs={ { edit: 1 } }
				/>
			</div>
		</>
	);
};

export default compose( withSelect( ( select, props ) => {
	const editorPostTitle = select( 'core/editor' ).getEditedPostAttribute( 'title' );
	const { getMedia }    = select( 'core' );
	const { promoImage }  = props.attributes;
	const promoImageMedia = getMedia( promoImage ) || null;

	return {
		editorPostTitle,
		promoImageMedia,
	};
} ) )( Edit );
