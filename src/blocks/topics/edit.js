import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	RangeControl,
	ToggleControl,
	SelectControl,
	Disabled,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import ServerSideRender from '@wordpress/server-side-render';

export default function ( { attributes, setAttributes } ) {
	const { title, orderBy, parentForum, maxShown, showDate, showUser } =
		attributes;

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

					<RangeControl
						label={ __(
							'Maximum topics to show',
							'unitone-bbpress-integrator'
						) }
						value={ maxShown }
						onChange={ ( newAttribute ) => {
							setAttributes( {
								maxShown: newAttribute,
							} );
						} }
						allowReset={ true }
						resetFallbackValue={ 5 }
						min={ 1 }
						max={ 20 }
					/>

					<TextControl
						label={ __(
							'Parent Forum ID',
							'unitone-bbpress-integrator'
						) }
						help={ __(
							'"0" to show only root - "any" to show all',
							'unitone-bbpress-integrator'
						) }
						value={ parentForum }
						onChange={ ( newAttribute ) => {
							setAttributes( {
								parentForum: newAttribute,
							} );
						} }
					/>

					<ToggleControl
						label={ __(
							'Show post date',
							'unitone-bbpress-integrator'
						) }
						checked={ showDate }
						onChange={ ( newAttribute ) => {
							setAttributes( {
								showDate: newAttribute,
							} );
						} }
					/>

					<ToggleControl
						label={ __(
							'Show topic author',
							'unitone-bbpress-integrator'
						) }
						checked={ showUser }
						onChange={ ( newAttribute ) => {
							setAttributes( {
								showUser: newAttribute,
							} );
						} }
					/>

					<SelectControl
						label={ __( 'Order By', 'unitone-bbpress-integrator' ) }
						value={ orderBy }
						options={ [
							{
								label: __(
									'Default',
									'unitone-bbpress-integrator'
								),
								value: '',
							},
							{
								label: __(
									'Newest Topics',
									'unitone-bbpress-integrator'
								),
								value: 'newness',
							},
							{
								label: __(
									'Popular Topics',
									'unitone-bbpress-integrator'
								),
								value: 'popular',
							},
							{
								label: __(
									'Topics With Recent Replies',
									'unitone-bbpress-integrator'
								),
								value: 'freshness',
							},
						] }
						onChange={ ( newAttribute ) => {
							setAttributes( {
								orderBy: newAttribute,
							} );
						} }
					/>
				</PanelBody>
			</InspectorControls>

			<div
				{ ...useBlockProps( {
					className: 'unitone-bbpress-integrator-topics',
				} ) }
			>
				<Disabled>
					<ServerSideRender
						block="unitone-bbpress-integrator/topics"
						attributes={ attributes }
					/>
				</Disabled>
			</div>
		</>
	);
}
