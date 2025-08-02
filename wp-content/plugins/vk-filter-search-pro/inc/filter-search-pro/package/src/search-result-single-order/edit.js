/**
 * Custom Field Block Edit.
 */

// wordpress のコンポーネントをインポート
import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import {
	PanelBody,
	BaseControl,
	TextControl,
	SelectControl,
	Button,
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import {
	chevronUp,
	chevronDown,
	plusCircle,
	arrowUp,
	close,
} from '@wordpress/icons';
import parse from 'html-react-parser';

import {
	isParentReusableBlock,
	isBlockDuplicate,
	sanitizeIconHTML,
} from '@vk-filter-search/common/component';

import {
	OuterColumnSetting,
	OuterColumnClasses,
	OuterColumnStyle,
	getParentBlock,
	getParentAttributes,
	useInheritLayout,
} from '@vk-filter-search-pro/common/outer-columns';

export default function SearchResultSingleOrderEdit( props ) {
	const { attributes, setAttributes, clientId } = props;
	const {
		blockLabel,
		selectOption,
		outerColumnXs,
		outerColumnSm,
		outerColumnMd,
		outerColumnLg,
		outerColumnXl,
		outerColumnXxl,
		blockId,
	} = attributes;

	useEffect( () => {
		if ( ! isParentReusableBlock( clientId ) ) {
			setAttributes( { blockId: clientId } );
		}
	}, [ clientId ] );

	const isDuplicate = isBlockDuplicate(
		'vk-filter-search-pro/search-result-single-order',
		clientId
	);
	useEffect( () => {
		if ( isDuplicate ) {
			setAttributes( { blockDisplay: false } );
		} else {
			setAttributes( { blockDisplay: true } );
		}
	}, [ isDuplicate ] );

	// 親ブロックを取得 /////////////////////////////////////////////
	const parentBlock = getParentBlock( clientId );
	const parentAttributes = getParentAttributes( parentBlock );

	// 親ブロックのレイアウト設定を子ブロックのouterColumnWidthMethodに反映
	// ※ 新規配置の場合にデフォルトでは親の状態と異なるため、親の状態を取得して反映する必要がある
	useInheritLayout( attributes, setAttributes, parentAttributes );

	useEffect( () => {
		// @since 1.13.0
		// 1.12.1 までは outerColumn が存在しなかったため互換処理を追加
		if ( outerColumnXs === undefined ) {
			setAttributes( { outerColumnXs: 12 } );
		}
		if ( outerColumnSm === undefined ) {
			setAttributes( { outerColumnSm: 12 } );
		}
		if ( outerColumnMd === undefined ) {
			setAttributes( { outerColumnMd: 6 } );
		}
		if ( outerColumnLg === undefined ) {
			setAttributes( { outerColumnLg: 6 } );
		}
		if ( outerColumnXl === undefined ) {
			setAttributes( { outerColumnXl: 6 } );
		}
		if ( outerColumnXxl === undefined ) {
			setAttributes( { outerColumnXxl: 6 } );
		}
	}, [ clientId ] );

	// Outer Class //////////////////////////////////////////////////////////////////
	// ブロックの識別クラスも OuterColumnClasses から edit と save に共通で付与したかったが
	// props.name が save.js に渡せないため 手動で付与
	const blockProps = useBlockProps( {
		className:
			`vkfs__orderby` +
			OuterColumnClasses( props ) +
			` vkfs__block-id--${ blockId }`,
	} );

	// レンダリングするか否か
	const [ update, setUpdata ] = useState( false );

	// オーダータイプ
	const [ currentOption, setCurrentOption ] = useState(
		JSON.parse( selectOption )
	);

	// オーダータイプ
	const [ orderType, setOrderType ] = useState( '' );

	// カスタムフィールド名
	const [ fieldName, setFieldName ] = useState( '' );

	// カスタムフィールドの型
	const [ fieldType, setFieldType ] = useState( 'CHAR' );

	// オプション名
	const [ optionName, setOptionName ] = useState( '' );

	// カスタムフィールド名
	const [ textDesc, setTextDesc ] = useState( '' );

	// オーダータイプ
	const [ textAsc, setTextAsc ] = useState( '' );

	// 新規追加モードか編集モードか
	const [ mode, setMode ] = useState( 'hidden' );

	// 編集中のインデックス番号
	const [ selectedIndex, setSelectedIndex ] = useState( 0 );

	// カスタムフィールドのリストから選択肢を作成
	// eslint-disable-next-line no-undef, camelcase
	const customFieldList = vk_filter_search_pro_params.customFieldList;
	const customFieldSelectOption = [
		{
			label: '- ' + __( 'Please select', 'vk-filter-search-pro' ) + ' -',
			value: '',
		},
	];
	customFieldList.forEach( ( field ) => {
		customFieldSelectOption.push( { label: field, value: field } );
	} );

	// 新規追加 or 選択編集
	const itemTitleLabel =
		mode === 'new'
			? __( 'Add new option item', 'vk-filter-search-pro' )
			: __( 'Edit selected option item', 'vk-filter-search-pro' );

	// ボタンのラベル
	const itemSubmitContent =
		mode === 'new'
			? __( 'Add new option', 'vk-filter-search-pro' )
			: __( 'Reflect edits', 'vk-filter-search-pro' );

	// 並び替えタイプの選択肢
	const orderOption = [
		{
			label: '- ' + __( 'Please select', 'vk-filter-search-pro' ) + ' -',
			value: '',
		},
		{
			label: __( 'Published Date', 'vk-filter-search-pro' ),
			value: 'date',
		},
		{
			label: __( 'Modefied Date', 'vk-filter-search-pro' ),
			value: 'modified',
		},
		{
			label: __( 'Title', 'vk-filter-search-pro' ),
			value: 'title',
		},
		{
			label: __( 'Custom Field', 'vk-filter-search-pro' ),
			value: 'custom-field',
		},
	];

	// リストコントロール
	const listControlInner = currentOption.map( ( select, index ) => {
		let selectLabel = '';
		let returnContent = '';

		if ( select.orderType === 'custom-field' && select.fieldName !== '' ) {
			if ( select.optionName ) {
				selectLabel = select.optionName;
			} else {
				selectLabel = select.fieldName;
			}
		} else if (
			select.orderType !== 'custom-field' &&
			select.orderType !== ''
		) {
			if ( select.optionName ) {
				selectLabel = select.optionName;
			} else {
				let tempLabel = '';
				orderOption.forEach( ( option ) => {
					if ( select.orderType === option.value ) {
						tempLabel = option.label;
					}
				} );
				selectLabel = tempLabel !== '' ? tempLabel : select.orderType;
			}
		}

		if ( selectLabel !== '' ) {
			// console.log(selectLabel);
			returnContent = (
				<li key={ index }>
					<span className="vkfs_single-order-option-list-name">
						{ selectLabel }
					</span>
					<Button
						icon={ chevronUp }
						className="vkfs_single-order-option-list-arrow vkfs_single-order-option-list-arrow-up"
						isSmall
						onClick={ () => {
							const tempArray = currentOption;
							const tempItem = currentOption[ index ];
							tempArray[ index ] = tempArray[ index - 1 ];
							tempArray[ index - 1 ] = tempItem;
							setCurrentOption( tempArray );
							setAttributes( {
								selectOption: JSON.stringify( currentOption ),
							} );
							setUpdata( update ? false : true );
						} }
						disabled={ index === 0 }
						variant="tertiary"
					/>
					<Button
						icon={ chevronDown }
						className="vkfs_single-order-option-list-arrow vkfs_single-order-option-list-arrow-down"
						isSmall
						onClick={ () => {
							const tempArray = currentOption;
							const tempItem = currentOption[ index ];
							tempArray[ index ] = tempArray[ index + 1 ];
							tempArray[ index + 1 ] = tempItem;
							setCurrentOption( tempArray );
							setAttributes( {
								selectOption: JSON.stringify( currentOption ),
							} );
							setUpdata( update ? false : true );
						} }
						variant="tertiary"
						disabled={ index === currentOption.length - 1 }
					/>
					<Button
						className="vkfs_single-order-option-list-edit"
						variant="primary"
						isSmall
						onClick={ () => {
							setSelectedIndex( index );
							setMode( 'edit' );
							setOrderType( currentOption[ index ].orderType );
							setFieldName( currentOption[ index ].fieldName );
							setFieldType( currentOption[ index ].fieldType );
							setOptionName( currentOption[ index ].optionName );
							setTextDesc( currentOption[ index ].textDesc );
							setTextAsc( currentOption[ index ].textAsc );
							setUpdata( update ? false : true );
						} }
					>
						{ __( 'Edit', 'vk-filter-search-pro' ) }
					</Button>
					<Button
						className="vkfs_single-order-option-list-delete"
						variant="primary"
						isSmall
						isDestructive
						onClick={ () => {
							const tempArray = currentOption;
							tempArray.splice( index, 1 );
							setCurrentOption( tempArray );
							setAttributes( {
								selectOption: JSON.stringify( currentOption ),
							} );
							setUpdata( update ? false : true );
						} }
					>
						{ __( 'Delete', 'vk-filter-search-pro' ) }
					</Button>
				</li>
			);
		}
		return returnContent;
	} );

	let listControl = '';
	if ( listControlInner !== '' && listControlInner.length !== 0 ) {
		listControl = (
			<ul className="vkfs_single-order-option-list">
				{ listControlInner }
			</ul>
		);
	}

	// 追加されるドロップダウンの中身
	const InnerContent = ( value ) => {
		if ( value.optionType === 'custom-field' ) {
			return (
				<>
					<option
						value={
							'custom-field.' +
							value.optionValue +
							'.desc.' +
							value.sortType
						}
					>
						{ value.labelDesc }
					</option>
					<option
						value={
							'custom-field.' +
							value.optionValue +
							'.asc.' +
							value.sortType
						}
					>
						{ value.labelAsc }
					</option>
				</>
			);
		}
		return (
			<>
				<option value={ value.optionValue + '.desc' }>
					{ value.labelDesc }
				</option>
				<option value={ value.optionValue + '.asc' }>
					{ value.labelAsc }
				</option>
			</>
		);
	};

	// ブロックを描画
	let blockContent = '';
	const blockContentLabel =
		blockLabel !== ''
			? parse( sanitizeIconHTML( blockLabel ) )
			: __( 'Sort order', 'vk-filter-search-pro' );
	let blockContentInner = '';

	blockContentInner = currentOption.map( ( select, index ) => {
		// 昇順 / 降順 のテキスト指定がない場合
		if ( ! select.textDesc ) {
			select.textDesc = __( 'Descending', 'vk-filter-search-pro' );
		}
		if ( ! select.textAsc ) {
			select.textAsc = __( 'Ascending', 'vk-filter-search-pro' );
		}

		let contentInner = '';
		if ( select.orderType === 'custom-field' && select.fieldName !== '' ) {
			const optionLabel = !! select.optionName
				? select.optionName
				: select.fieldName;
			contentInner = (
				<InnerContent
					optionType={ select.orderType }
					sortType={ select.fieldType }
					optionValue={ select.fieldName }
					labelDesc={ optionLabel + ' ' + select.textDesc }
					labelAsc={ optionLabel + ' ' + select.textAsc }
					key={ index }
				/>
			);
		} else if (
			select.orderType !== 'custom-field' &&
			select.orderType !== ''
		) {
			let defaultLabel = '';
			orderOption.forEach( ( order ) => {
				if ( order.value === select.orderType ) {
					defaultLabel = order.label;
				}
			} );
			const optionLabel = !! select.optionName
				? select.optionName
				: defaultLabel;
			contentInner = (
				<InnerContent
					optionType={ select.orderType }
					sortType={ null }
					optionValue={ select.orderType }
					labelDesc={ optionLabel + ' ' + select.textDesc }
					labelAsc={ optionLabel + ' ' + select.textAsc }
					key={ index }
				/>
			);
		}
		return contentInner;
	} );

	blockContent = (
		<>
			<div className="vkfs__label-name">{ blockContentLabel }</div>
			<select
				className="vkfs__input-wrap vkfs__input-wrap--select vkfs__input-wrap--orderby"
				name="vkfs_orderby"
				id="orderby"
			>
				<option value={ '' }>
					{ __( 'Any', 'vk-filter-search-pro' ) }
				</option>
				{ blockContentInner }
			</select>
		</>
	);

	if ( isDuplicate ) {
		return (
			<div { ...blockProps }>
				<div className="vkfs__warning">
					<div className="vkfs__label-name">
						{ __(
							'Search Result Single Order',
							'vk-filter-search-pro'
						) }
					</div>
					<div className="vkfs__warning-text">
						{ __(
							'Only one ‘Search Result Single Order’ block can be placed within the same form. Please delete it immediately.',
							'vk-filter-search-pro'
						) }
					</div>
				</div>
				<OuterColumnStyle { ...props } />
			</div>
		);
	}

	return (
		<>
			<InspectorControls>
				<OuterColumnSetting { ...props } />
				<PanelBody
					title={ __(
						'Display Order Setting',
						'vk-filter-search-pro'
					) }
					initialOpen={ true }
				>
					<BaseControl
						id={ 'vsfs_pro_searchResultSingleOrder-blockLabel' }
						className={ 'mb-0' }
					>
						<TextControl
							label={ __(
								'Label of This Block',
								'vk-filter-search-pro'
							) }
							value={ blockLabel }
							onChange={ ( value ) =>
								setAttributes( { blockLabel: value } )
							}
						/>
					</BaseControl>

					<BaseControl
						className={ `vkfs_single-order-option` }
						label={ __(
							'Select(Drop down) option setting',
							'vk-filter-search-pro'
						) }
						id={ `vkfs_current-setting--${ clientId }` }
					>
						{ listControl }

						{ ( () => {
							if ( mode === 'hidden' ) {
								return (
									<>
										<Button
											icon={ plusCircle }
											iconSize={ 18 }
											className="vkfs_single-order-option__add-order"
											variant="primary"
											onClick={ () => setMode( 'new' ) }
										>
											{ __(
												'Add new select option item',
												'vk-filter-search-pro'
											) }
										</Button>
									</>
								);
							} else if ( mode === 'new' || mode === 'edit' ) {
								return (
									<>
										<BaseControl
											label={ itemTitleLabel }
											id={ `vkfs_${ itemTitleLabel }--${ clientId }` }
											className={ `vkfs_option-edit` }
										>
											<SelectControl
												label={ __(
													'Sort target',
													'vk-filter-search-pro'
												) }
												value={ orderType }
												options={ orderOption }
												onChange={ ( value ) =>
													setOrderType( value )
												}
											/>
											{ orderType === 'custom-field' && (
												<>
													<SelectControl
														label={ __(
															'Custom Field Name',
															'vk-filter-search-pro'
														) }
														value={ fieldName }
														options={
															customFieldSelectOption
														}
														onChange={ ( value ) =>
															setFieldName(
																value
															)
														}
													/>
													<SelectControl
														label={ __(
															'Custom Field Type',
															'vk-filter-search-pro'
														) }
														value={ fieldType }
														options={ [
															{
																label: __(
																	'String',
																	'vk-filter-search-pro'
																),
																value: 'CHAR',
															},
															{
																label: __(
																	'Numeric',
																	'vk-filter-search-pro'
																),
																value: 'NUMERIC',
															},
															{
																label: __(
																	'Date',
																	'vk-filter-search-pro'
																),
																value: 'DATE',
															},
															{
																label: __(
																	'Date Time',
																	'vk-filter-search-pro'
																),
																value: 'DATETIME',
															},
															{
																label: __(
																	'Time',
																	'vk-filter-search-pro'
																),
																value: 'TIME',
															},
														] }
														onChange={ ( value ) =>
															setFieldType(
																value
															)
														}
													/>
												</>
											) }
											<TextControl
												label={ __(
													'Select option item name (Option)',
													'vk-filter-search-pro'
												) }
												help={ __(
													'If you are operating in another sense, such as using the publish date as the "event date", change it as necessary.',
													'vk-filter-search-pro'
												) }
												value={ optionName }
												onChange={ ( value ) =>
													setOptionName( value )
												}
											/>
											<TextControl
												label={ __(
													'Descending text (Option)',
													'vk-filter-search-pro'
												) }
												value={ textDesc }
												onChange={ ( value ) =>
													setTextDesc( value )
												}
												className={ `mb-0` }
											/>
											<p>
												{ __(
													'Ex : Descending / Z to A / 9 to 0',
													'vk-filter-search-pro'
												) }
											</p>
											<TextControl
												label={ __(
													'Ascending text (Option)',
													'vk-filter-search-pro'
												) }
												value={ textAsc }
												onChange={ ( value ) =>
													setTextAsc( value )
												}
												className={ `mb-0` }
											/>
											<p>
												{ __(
													'Ex : Ascending / A to Z / 0 to 9',
													'vk-filter-search-pro'
												) }
											</p>
											<div
												className={ `vkfs_edit-button-outer` }
											>
												<Button
													icon={ arrowUp }
													iconSize={ 18 }
													className={ `vkfs_edit-button` }
													onClick={ () => {
														if ( mode === 'new' ) {
															const optionTemp =
																currentOption;
															optionTemp.push( {
																orderType,
																fieldName,
																fieldType,
																optionName,
																textDesc,
																textAsc,
															} );
															setCurrentOption(
																optionTemp
															);
															setAttributes( {
																selectOption:
																	JSON.stringify(
																		currentOption
																	),
															} );
															setOrderType( '' );
															setFieldName( '' );
															setFieldType(
																'CHAR'
															);
															setOptionName( '' );
															setTextDesc( '' );
															setTextAsc( '' );
															setUpdata(
																update
																	? false
																	: true
															);
														} else {
															const optionTemp =
																currentOption;
															optionTemp[
																selectedIndex
															] = {
																orderType,
																fieldName,
																fieldType,
																optionName,
																textDesc,
																textAsc,
															};
															setCurrentOption(
																optionTemp
															);
															setAttributes( {
																selectOption:
																	JSON.stringify(
																		currentOption
																	),
															} );
															setOrderType( '' );
															setFieldName( '' );
															setFieldType(
																'CHAR'
															);
															setOptionName( '' );
															setTextDesc( '' );
															setTextAsc( '' );
															setMode( 'new' );
															setUpdata(
																update
																	? false
																	: true
															);
														}
														setMode( 'hidden' );
													} }
													variant="primary"
													disabled={
														( orderType ===
															'custom-field' &&
															fieldName ===
																'' ) ||
														orderType === ''
													}
												>
													{ itemSubmitContent }
												</Button>
												<Button
													icon={ close }
													iconSize={ 18 }
													className={ `vkfs_edit-button vkfs_edit-button-cancel` }
													variant="secondary"
													onClick={ () =>
														setMode( 'hidden' )
													}
												>
													{ __(
														'Cancel',
														'vk-filter-search-pro'
													) }
												</Button>
											</div>
										</BaseControl>
									</>
								);
							}
						} )() }
					</BaseControl>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				{ blockContent }
				<OuterColumnStyle { ...props } />
			</div>
		</>
	);
}
