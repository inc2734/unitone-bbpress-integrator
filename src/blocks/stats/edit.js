import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, Disabled } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import ServerSideRender from '@wordpress/server-side-render';

export default function ( { attributes, setAttributes } ) {
	const { title } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'General', 'unitone-bbpress-integrator' ) }
				>
					<TextControl
						label={ __( 'Title', 'unitone-bbpress-integrator' ) }
						value={ title }
						onChange={ ( newAttribute ) => {
							setAttributes( {
								title: newAttribute,
							} );
						} }
					/>
				</PanelBody>
			</InspectorControls>

			<div
				{ ...useBlockProps( {
					className: 'unitone-bbpress-integrator-stats',
				} ) }
			>
				<Disabled>
					<ServerSideRender
						block="unitone-bbpress-integrator/stats"
						attributes={ attributes }
					/>
				</Disabled>
			</div>
		</>
	);
}
