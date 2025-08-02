import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	BaseControl,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { useEffect } from '@wordpress/element';
import { select } from '@wordpress/data';
import { isParentReusableBlock } from '@vk-filter-search/common/component';
import {
	OuterColumnSetting,
	OuterColumnStyle,
	OuterColumnClasses,
	getParentBlock,
	getParentAttributes,
	useInheritLayout,
} from '@vk-filter-search-pro/common/outer-columns';

import { InnerColumnSetting } from '@vk-filter-search-pro/common/inner-columns';
import {
	contorolDropdown,
	taxonomyAccordion,
} from '@vk-filter-search-pro/common/enqueue-front-component';

export default function TaxonomySearchProEdit( props ) {
	const { attributes, setAttributes, clientId } = props;

	const {
		isSelectedTaxonomy,
		BlockLabel,
		isSelectedDesign,
		nonSelectedLabel,
		isSelectedOperator,
		accordionType,
		enableChildDropdwon,
		showCount,
		autoCount,
		hideEmpty,
	} = attributes;

	useEffect( () => {
		if ( ! isParentReusableBlock( clientId ) ) {
			setAttributes( { blockId: clientId } );
		}
	}, [ clientId ] );

	const pathString = window.location.pathname;

	let currentPostType = null;
	if (
		pathString.indexOf( 'site-editor.php' ) === -1 &&
		pathString.indexOf( 'widgets.php' ) === -1
	) {
		currentPostType = select( 'core/editor' ).getCurrentPostType();
	}

	contorolDropdown();
	taxonomyAccordion();

	// 親ブロックを取得 /////////////////////////////////////////////
	const parentBlock = getParentBlock( clientId );
	const parentAttributes = getParentAttributes( parentBlock );

	// 親ブロックのレイアウト設定を子ブロックのouterColumnWidthMethodに反映
	// ※ 新規配置の場合にデフォルトでは親の状態と異なるため、親の状態を取得して反映する必要がある
	useInheritLayout( attributes, setAttributes, parentAttributes );

	useEffect( () => {
		if ( isSelectedDesign === 'select' || isSelectedDesign === 'radio' ) {
			setAttributes( { isSelectedOperator: 'or' } );
		}
	}, [ isSelectedDesign ] );

	// 階層ドロップダウンにするか
	let enableChildDropdwonOption = '';
	if ( isSelectedDesign === 'select' ) {
		enableChildDropdwonOption = (
			<BaseControl id={ 'vsfs-pro-taxonomy--enableChildDropdwon' }>
				<ToggleControl
					label={ __( 'Step drop down', 'vk-filter-search-pro' ) }
					checked={ enableChildDropdwon }
					onChange={ ( value ) =>
						setAttributes( { enableChildDropdwon: value } )
					}
				/>
			</BaseControl>
		);
	}

	// 指定なしの場合の選択肢の表示名
	let nonSelectedLabelOption = '';
	if ( isSelectedDesign === 'select' || isSelectedDesign === 'radio' ) {
		nonSelectedLabelOption = (
			<BaseControl id={ 'vsfs-pro-taxonomy--nonSelectedLabel' }>
				<TextControl
					label={ __(
						'Option Label for Non-Selected',
						'vk-filter-search-pro'
					) }
					value={ nonSelectedLabel }
					onChange={ ( value ) =>
						setAttributes( { nonSelectedLabel: value } )
					}
					help={ __( 'Default is "Any"', 'vk-filter-search-pro' ) }
				/>
			</BaseControl>
		);
	}

	// 演算子の設定
	let OperatorOption = '';

	if ( isSelectedDesign === 'checkbox' ) {
		OperatorOption = (
			<BaseControl
				id={ 'vsfs-pro-taxonomy--operator' }
				label={ __( 'Filter setting', 'vk-filter-search-pro' ) }
			>
				<SelectControl
					label={ __(
						'Choose filter setting',
						'vk-filter-search-pro'
					) }
					value={ isSelectedOperator }
					onChange={ ( value ) =>
						setAttributes( { isSelectedOperator: value } )
					}
					options={ [
						{
							label: __( 'OR', 'vk-filter-search-pro' ),
							value: 'or',
						},
						{
							label: __( 'AND', 'vk-filter-search-pro' ),
							value: 'and',
						},
						{
							label: __(
								'Let the user choose',
								'vk-filter-search-pro'
							),
							value: 'user',
						},
					] }
				/>
			</BaseControl>
		);
	}

	// チェックボックス・ラジオボタンの innerColumn の設定 ///////////////////////////////////////////
	let innerColumnSetting = '';
	let AccordionSetting = '';
	if ( isSelectedDesign === 'checkbox' || isSelectedDesign === 'radio' ) {
		innerColumnSetting = (
			<BaseControl
				id={ 'vsfs-pro-taxonomy--innerColumns' }
				label={ __(
					'Checkbox / radio button columns',
					'vk-filter-search-pro'
				) }
			>
				<InnerColumnSetting { ...props } />
			</BaseControl>
		);
		AccordionSetting = (
			<BaseControl
				id={ 'vsfs-pro-taxonomy--accordionSetting' }
				label={ __( 'Accordion Setting', 'vk-filter-search-pro' ) }
			>
				<SelectControl
					label={ __( 'Accordion Type', 'vk-filter-search-pro' ) }
					value={ accordionType }
					options={ [
						{
							label: __( 'None', 'vk-filter-search-pro' ),
							value: 'none',
						},
						{
							label: __( 'Accordion', 'vk-filter-search-pro' ),
							value: 'accordion',
						},
						{
							label: __( 'Collapse', 'vk-filter-search-pro' ),
							value: 'collapse',
						},
					] }
					onChange={ ( value ) =>
						setAttributes( { accordionType: value } )
					}
				/>
				<p>
					{ __(
						'Please check the operation after saving and reloading this post, or on the public screen.',
						'vk-filter-search-pro'
					) }
				</p>
				<ul>
					<li>
						{ __( 'None: Nothing to do', 'vk-filter-search-pro' ) }
					</li>
					<li>
						{ __(
							'Accordion: Open or close the clicked element',
							'vk-filter-search-pro'
						) }
					</li>
					<li>
						{ __(
							'Collapse: Open or close the clicked element and close the others',
							'vk-filter-search-pro'
						) }
					</li>
				</ul>
			</BaseControl>
		);
	}

	let editContent;
	//eslint-disable-next-line camelcase,no-undef
	const taxonomyOption = vk_filter_search_pro_params.taxonomy_option;
	//eslint-disable-next-line camelcase,no-undef
	const taxonomyList = vk_filter_search_pro_params.taxonomy_list;
	const condition = ( taxonomy ) => taxonomy.value === isSelectedTaxonomy;

	const selectedTaxonomy = taxonomyList.find( condition );

	if (
		taxonomyOption.some( condition ) &&
		isSelectedTaxonomy !== '' &&
		isSelectedTaxonomy !== null &&
		isSelectedTaxonomy !== undefined
	) {
		editContent = (
			<ServerSideRender
				block="vk-filter-search-pro/taxonomy-search-pro"
				attributes={ props.attributes }
			/>
		);
	} else if (
		isSelectedTaxonomy === '' ||
		isSelectedTaxonomy === null ||
		isSelectedTaxonomy === undefined
	) {
		editContent = (
			<div>
				<div className="vkfs__warning">
					<div className="vkfs__label-name">
						{ __( 'Taxonomy', 'vk-filter-search-pro' ) }
					</div>
					<div className="vkfs__warning-text">
						{ __(
							'This block will not be displayed because no taxonomy is selected.',
							'vk-filter-search-pro'
						) }
					</div>
				</div>
			</div>
		);
	} else if (
		selectedTaxonomy !== undefined &&
		selectedTaxonomy.label !== undefined
	) {
		editContent = (
			<div className="vkfs__warning">
				<div className="vkfs__label-name">
					{ selectedTaxonomy.label }
				</div>
				<div className="vkfs__warning-text">
					{ __(
						'This block will not be displayed because this taxonomy has no term.',
						'vk-filter-search-pro'
					) }
				</div>
			</div>
		);
	} else {
		editContent = (
			<div className="vkfs__warning">
				<div className="vkfs__label-name">
					{ __(
						'Specified taxonomy does not exist',
						'vk-filter-search-pro'
					) }
				</div>
				<div className="vkfs__warning-text">
					{ __(
						'This block will not be displayed because the specified taxonomy does not exist.',
						'vk-filter-search-pro'
					) }
					{ __(
						'Please reselect the taxonomy or remove this block..',
						'vk-filter-search-pro'
					) }
				</div>
			</div>
		);
	}

	// Outer Class //////////////////////////////////////////////////////////////////
	// ブロックの識別クラスも OuterColumnClasses から edit と save に共通で付与したかったが
	// props.name が save.js に渡せないため 手動で付与
	const blockProps = useBlockProps( {
		className: `vkfs__taxonomy` + OuterColumnClasses( props ),
	} );

	// Return /////////////////////////////////////////////////////////////////////////////////////////////////
	return (
		<>
			<InspectorControls>
				<OuterColumnSetting { ...props } />
				<PanelBody
					title={ __(
						'Taxonomy Block Option',
						'vk-filter-search-pro'
					) }
					initialOpen={ true }
				>
					<BaseControl id={ 'vsfs-pro-taxonomy--taxonomy' }>
						<SelectControl
							label={ __( 'Taxonomy', 'vk-filter-search-pro' ) }
							value={ isSelectedTaxonomy }
							options={ taxonomyOption }
							onChange={ ( value ) =>
								setAttributes( {
									isSelectedTaxonomy: value,
								} )
							}
						/>
						<p
							className={ `vkfs__alert vkfs__alert--warning vkfs__alert--taxonomy` }
						>
							{ __(
								'Taxonomies that do not have any terms associated with posts are excluded from selection.',
								'vk-filter-search-pro'
							) }
						</p>
					</BaseControl>
					<BaseControl id={ 'vsfs-pro-taxonomy--label' }>
						<TextControl
							label={ __(
								'Label of This Block',
								'vk-filter-search-pro'
							) }
							value={ BlockLabel }
							onChange={ ( value ) =>
								setAttributes( { BlockLabel: value } )
							}
						/>
					</BaseControl>

					<BaseControl
						id={ 'vsfs-pro-taxonomy--isSelectedDesign' }
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
					{ nonSelectedLabelOption }
					{ enableChildDropdwonOption }
					{ OperatorOption }
					{ AccordionSetting }
					{ innerColumnSetting }
					<BaseControl
						id={ 'vsfs-pro-taxonomy--displaySetting' }
						label={ __(
							'Display Setting',
							'vk-filter-search-pro'
						) }
					>
						<ToggleControl
							label={ __(
								'Display Post Counts',
								'vk-filter-search-pro'
							) }
							checked={ showCount }
							onChange={ ( value ) => {
								setAttributes( { showCount: value } );
							} }
						/>
						{ currentPostType === 'filter-search' && showCount && (
							<ToggleControl
								label={ __(
									'Display the post count for the current submitted conditions, not for all posts associated with the terms',
									'vk-filter-search-pro'
								) }
								checked={ autoCount }
								onChange={ ( value ) => {
									setAttributes( { autoCount: value } );
								} }
							/>
						) }
						<ToggleControl
							label={ __(
								'Hide Empty Terms',
								'vk-filter-search-pro'
							) }
							checked={ hideEmpty }
							onChange={ ( value ) => {
								setAttributes( { hideEmpty: value } );
							} }
						/>
					</BaseControl>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				{ editContent }
				<OuterColumnStyle { ...props } />
			</div>
		</>
	);
}
