import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, Disabled } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import ServerSideRender from '@wordpress/server-side-render';

export default function ( { attributes, setAttributes } ) {
	const { title, register, lostpass } = attributes;

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

					<TextControl
						label={ __(
							'Register URI',
							'unitone-bbpress-integrator'
						) }
						value={ register }
						onChange={ ( newAttribute ) => {
							setAttributes( {
								register: newAttribute,
							} );
						} }
					/>

					<TextControl
						label={ __(
							'Lost Password URI',
							'unitone-bbpress-integrator'
						) }
						value={ lostpass }
						onChange={ ( newAttribute ) => {
							setAttributes( {
								lostpass: newAttribute,
							} );
						} }
					/>
				</PanelBody>
			</InspectorControls>

			<div
				{ ...useBlockProps( {
					className: 'unitone-bbpress-integrator-login',
				} ) }
			>
				<Disabled>
					<ServerSideRender
						block="unitone-bbpress-integrator/login"
						attributes={ attributes }
					/>
				</Disabled>
			</div>
		</>
	);
}
