import { __ } from '@wordpress/i18n';
import {
	AdvancedCheckboxControl,
	isParentReusableBlock,
} from '@vk-filter-search/common/component';
import {
	PanelBody,
	BaseControl,
	TextControl,
	SelectControl,
} from '@wordpress/components';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { useEffect } from '@wordpress/element';
import {
	OuterColumnSetting,
	OuterColumnStyle,
	OuterColumnClasses,
	getParentBlock,
	getParentAttributes,
	useInheritLayout,
} from '@vk-filter-search-pro/common/outer-columns';

import { InnerColumnSetting } from '@vk-filter-search-pro/common/inner-columns';

export default function PostTypeSearchProEdit( props ) {
	const { attributes, setAttributes, clientId } = props;

	const {
		labelAccordionType,
		isCheckedPostType,
		BlockLabel,
		PostLabel,
		PageLabel,
		isSelectedDesign,
	} = attributes;

	useEffect( () => {
		if ( ! isParentReusableBlock( clientId ) ) {
			setAttributes( { blockId: clientId } );
		}
	}, [ clientId ] );

	// 親ブロックを取得 /////////////////////////////////////////////
	const parentBlock = getParentBlock( clientId );
	const parentAttributes = getParentAttributes( parentBlock );

	// 親ブロックのレイアウト設定を子ブロックのouterColumnWidthMethodに反映
	// ※ 新規配置の場合にデフォルトでは親の状態と異なるため、親の状態を取得して反映する必要がある
	useInheritLayout( attributes, setAttributes, parentAttributes );

	let innerColumnSetting = '';

	if ( isSelectedDesign === 'checkbox' || isSelectedDesign === 'radio' ) {
		innerColumnSetting = (
			<BaseControl
				id={ 'vsfs-pro-post-type-05' }
				label={ __(
					'Checkbox / radio button columns',
					'vk-filter-search-pro'
				) }
			>
				<InnerColumnSetting { ...props } />
			</BaseControl>
		);
	}

	let editContent;

	if ( isCheckedPostType !== '[]' ) {
		editContent = (
			<ServerSideRender
				block="vk-filter-search-pro/post-type-search-pro"
				attributes={ props.attributes }
			/>
		);
	} else {
		editContent = (
			<div className="vkfs__warning">
				<div className="vkfs__label-name">
					<div className="vkfs__label-name-inner">
						{ __( 'Post Type', 'vk-filter-search-pro' ) }
					</div>
				</div>
				<div className="vkfs__warning-text">
					{ __(
						'This block will not be displayed because the post type is not selected.',
						'vk-filter-search-pro'
					) }
				</div>
			</div>
		);
	}

	// Outer Class //////////////////////////////////////////////////////////////////
	// ここではOuterColumnClassesのみを指定
	// * ブロック識別用のクラス名はコアで自動で付与されるため（手動でつけると命名規則が一貫しなくなる）
	// * もし独自のブロック識別用のクラスが必要なら公開画面と差異がでないようにこのファイル以外で処理するべき
	const blockProps = useBlockProps( {
		className: OuterColumnClasses( props ),
	} );

	// Return /////////////////////////////////////////////////////////////////////////////////////////////////
	return (
		<>
			<InspectorControls>
				{ labelAccordionType === 'none' && (
					<OuterColumnSetting { ...props } />
				) }
				<PanelBody
					title={ __(
						'Post Type Block Options',
						'vk-filter-search-pro'
					) }
					initialOpen={ true }
				>
					<BaseControl
						id={ 'vsfs-pro-post-type-01' }
						label={ __( 'Post Types', 'vk-filter-search-pro' ) }
					>
						<AdvancedCheckboxControl
							schema={ 'isCheckedPostType' }
							rawData={
								//eslint-disable-next-line camelcase,no-undef
								vk_filter_search_pro_params.post_type_checkbox
							}
							checkedData={ JSON.parse( isCheckedPostType ) }
							{ ...props }
						/>
					</BaseControl>
					<BaseControl
						id={ 'vsfs-pro-post-type-02' }
						label={ __(
							'Label of This Block',
							'vk-filter-search-pro'
						) }
					>
						<TextControl
							value={ BlockLabel }
							onChange={ ( value ) =>
								setAttributes( { BlockLabel: value } )
							}
						/>
					</BaseControl>
					<BaseControl
						id={ 'vsfs-pro-post-type-03' }
						label={ __( 'Label of Post', 'vk-filter-search-pro' ) }
					>
						<TextControl
							value={ PostLabel }
							onChange={ ( value ) =>
								setAttributes( { PostLabel: value } )
							}
						/>
					</BaseControl>
					<BaseControl
						id={ 'vsfs-pro-post-type-04' }
						label={ __( 'Label of Page', 'vk-filter-search-pro' ) }
					>
						<TextControl
							value={ PageLabel }
							onChange={ ( value ) =>
								setAttributes( { PageLabel: value } )
							}
						/>
					</BaseControl>
					<BaseControl
						id={ 'vsfs-pro-post-type-03' }
						label={ __(
							'Selection format',
							'vk-filter-search-pro'
						) }
					>
						<SelectControl
							label={ __(
								'Choose selection format',
								'vk-filter-search-pro'
							) }
							value={ isSelectedDesign }
							onChange={ ( value ) =>
								setAttributes( { isSelectedDesign: value } )
							}
							options={ [
								{
									label: __(
										'Pulldown',
										'vk-filter-search-pro'
									),
									value: 'select',
								},
								{
									label: __(
										'Checkbox',
										'vk-filter-search-pro'
									),
									value: 'checkbox',
								},
								{
									label: __(
										'Radio Button',
										'vk-filter-search-pro'
									),
									value: 'radio',
								},
							] }
						/>
					</BaseControl>
					{ innerColumnSetting }
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				{ editContent }
				<OuterColumnStyle { ...props } />
			</div>
		</>
	);
}
