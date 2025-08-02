import { __ } from '@wordpress/i18n';
import {
	AdvancedColorPalette,
	colorSlugToColorCode,
} from '@vk-filter-search/common/component';
import {
	PanelBody,
	BaseControl,
	TextControl,
	ToggleControl,
	FontSizePicker,
	__experimentalBoxControl as OldBoxControl,
	BoxControl as NewBoxControl,
	__experimentalUnitControl as UnitControl,
	ColorIndicator,
} from '@wordpress/components';
import {
	ContrastChecker,
	__experimentalBorderRadiusControl as BorderRadiusControl,
} from '@wordpress/block-editor';
import { select } from '@wordpress/data';

const BoxControl = OldBoxControl || NewBoxControl;

const SubmitButtonControl = ( props ) => {
	const { attributes, setAttributes } = props;

	const {
		SubmitText,
		SubmitFontSize,
		SubmitLetterSpacing,
		SubmitPadding,
		SubmitBorderRadius,
		submitBackgroundColor,
		submitTextColor,
		submitBorderColor,
		submitChangeColorHover,
	} = attributes;

	const fontSizes = select( 'core/editor' ).getEditorSettings().fontSizes;

	const units = [
		{ value: 'px', label: 'px', default: null },
		{ value: '%', label: '%', default: null },
		{ value: 'em', label: 'em', default: null },
		{ value: 'rem', label: 'rem', default: null },
		{ value: 'vw', label: 'vw', default: null },
		{ value: 'vh', label: 'vh', default: null },
	];

	return (
		<PanelBody
			title={ __( 'Submit Button Setting', 'vk-filter-search-pro' ) }
			initialOpen={ true }
		>
			<BaseControl id={ 'vkfs-search-form-pro-SubmitText' }>
				<TextControl
					label={ __( 'Submit Button Text', 'vk-filter-search-pro' ) }
					value={ SubmitText }
					className="mt-1rem"
					onChange={ ( value ) =>
						setAttributes( { SubmitText: value } )
					}
				/>
			</BaseControl>
			<BaseControl
				id={ 'vkfs-pro-SubmitFontSize' }
				label={ __( 'Font Size', 'vk-filter-search-pro' ) }
			>
				<FontSizePicker
					fontSizes={ fontSizes }
					value={ SubmitFontSize }
					onChange={ ( value ) =>
						setAttributes( { SubmitFontSize: value } )
					}
					__nextHasNoMarginBottom={ true }
				/>
			</BaseControl>
			<BaseControl id={ 'vkfs-pro-SubmitLetterSpacing' }>
				<UnitControl
					label={ __( 'Letter spacing', 'vk-filter-search-pro' ) }
					value={ SubmitLetterSpacing }
					onChange={ ( value ) =>
						setAttributes( {
							SubmitLetterSpacing: value,
						} )
					}
					__unstableInputWidth="auto"
				/>
			</BaseControl>
			<BaseControl id={ 'vkfs-pro-SubmitPadding' }>
				<BoxControl
					label={ __( 'Padding', 'vk-filter-search-pro' ) }
					values={ SubmitPadding }
					onChange={ ( value ) =>
						setAttributes( { SubmitPadding: value } )
					}
					sides={ [ 'vertical', 'horizontal' ] }
					units={ units }
					splitOnAxis={ true }
					allowReset={ true }
					resetValues={ {
						top: null,
						right: null,
						bottom: null,
						left: null,
					} }
				/>
			</BaseControl>
			<BaseControl id={ 'vkfs-pro-SubmitBorderRadius' }>
				<BorderRadiusControl
					values={ SubmitBorderRadius }
					allowReset={ true }
					resetValues={ {
						top: null,
						right: null,
						bottom: null,
						left: null,
					} }
					onChange={ ( value ) => {
						if ( typeof value === 'string' ) {
							setAttributes( {
								SubmitBorderRadius: {
									topLeft: value,
									topRight: value,
									bottomRight: value,
									bottomLeft: value,
								},
							} );
						} else {
							setAttributes( {
								SubmitBorderRadius: value,
							} );
						}
					} }
				/>
			</BaseControl>
			<BaseControl
				id={ 'vkfs-pro-ButtonColor' }
				label={ __( 'Botton Color', 'vk-filter-search-pro' ) }
			>
				<p>
					<ColorIndicator
						colorValue={ colorSlugToColorCode( submitTextColor ) }
					/>{ ' ' }
					<ColorIndicator
						colorValue={ colorSlugToColorCode(
							submitBackgroundColor
						) }
					/>{ ' ' }
					<ColorIndicator
						colorValue={ colorSlugToColorCode( submitBorderColor ) }
					/>{ ' ' }
				</p>
				<ContrastChecker
					backgroundColor={ colorSlugToColorCode(
						submitBackgroundColor
					) }
					textColor={ colorSlugToColorCode( submitTextColor ) }
					borderColor={ colorSlugToColorCode( submitBorderColor ) }
				/>

				<div className={ `mb-1` }>
					{ __( 'Text Color', 'vk-filter-search-pro' ) }
				</div>
				<AdvancedColorPalette
					schema={ 'submitTextColor' }
					{ ...props }
				/>

				<div className={ `mb-1` }>
					{ __( 'Background Color', 'vk-filter-search-pro' ) }
				</div>
				<AdvancedColorPalette
					schema={ 'submitBackgroundColor' }
					{ ...props }
				/>

				<div className={ `mb-1` }>
					{ __( 'Border Color', 'vk-filter-search-pro' ) }
				</div>
				<AdvancedColorPalette
					schema={ 'submitBorderColor' }
					{ ...props }
				/>

				<ToggleControl
					label={ __(
						'Automatically change button color on hover',
						'vk-filter-search-pro'
					) }
					checked={ submitChangeColorHover }
					onChange={ ( value ) => {
						setAttributes( {
							submitChangeColorHover: value,
						} );
					} }
				/>
			</BaseControl>
		</PanelBody>
	);
};
export default SubmitButtonControl;
