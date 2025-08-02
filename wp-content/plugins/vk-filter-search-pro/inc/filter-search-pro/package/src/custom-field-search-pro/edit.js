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
	CheckboxControl,
} from '@wordpress/components';
import parse from 'html-react-parser';
import { useEffect } from '@wordpress/element';

// 独自のコンポーネントをインポート
import {
	OuterColumnSetting,
	OuterColumnClasses,
	OuterColumnStyle,
	getParentBlock,
	getParentAttributes,
	useInheritLayout,
} from '@vk-filter-search-pro/common/outer-columns';

// 独自のコンポーネントをインポート
import {
	isParentReusableBlock,
	sanitizeIconHTML,
} from '@vk-filter-search/common/component';

export default function CustomFieldSearchProEdit( props ) {
	const { attributes, setAttributes, clientId } = props;
	const {
		fieldName,
		fieldType,
		fieldMin,
		fieldMax,
		fieldStep,
		fieldCompare,
		blockLabel,
		fieldBefore,
		fieldAfter,
		fieldBefore2,
		fieldAfter2,
		rangeBetween,
		alertCheck,
		blockId,
	} = attributes;

	useEffect( () => {
		if ( ! isParentReusableBlock( clientId ) ) {
			setAttributes( { blockId: clientId } );
		}
		if ( fieldType === undefined ) {
			setAttributes( { fieldType: 'numeric' } );
		}
	}, [ clientId ] );

	// 親ブロックを取得 /////////////////////////////////////////////
	const parentBlock = getParentBlock( clientId );
	const parentAttributes = getParentAttributes( parentBlock );

	// 親ブロックのレイアウト設定を子ブロックのouterColumnWidthMethodに反映
	// ※ 新規配置の場合にデフォルトでは親の状態と異なるため、親の状態を取得して反映する必要がある
	useInheritLayout( attributes, setAttributes, parentAttributes );

	// Outer Class //////////////////////////////////////////////////////////////////
	// ブロックの識別クラスも OuterColumnClasses から edit と save に共通で付与したかったが
	// props.name が save.js に渡せないため 手動で付与
	const blockProps = useBlockProps( {
		className:
			`vkfs__custom-field` +
			OuterColumnClasses( props ) +
			` vkfs__block-id--${ blockId }`,
	} );

	// eslint-disable-next-line no-undef, camelcase
	const customFieldList = vk_filter_search_pro_params.customFieldList;

	const customFieldSelectOption = [
		{
			label: __( 'Please select', 'vk-filter-search-pro' ),
			value: undefined,
		},
	];
	customFieldList.forEach( ( field ) => {
		customFieldSelectOption.push( { label: field, value: field } );
	} );

	// 配列からフィールド名に対応した名前を取得
	let currentDefaultLabel = '';
	customFieldSelectOption.forEach( ( field ) => {
		if ( field.value === fieldName ) {
			currentDefaultLabel = field.label;
		} else if ( field.value === undefined ) {
			currentDefaultLabel = __( 'Not selected', 'vk-filter-search-pro' );
		}
	} );

	// 表示内容
	let blockContent = '';
	const BlockLabel =
		blockLabel !== undefined && blockLabel !== ''
			? parse( sanitizeIconHTML( blockLabel ) )
			: currentDefaultLabel;
	const beforeField =
		fieldBefore !== undefined && fieldBefore !== '' ? (
			<span className="vkfs__input-text-addition vkfs__input-text-addition--before">
				{ parse( sanitizeIconHTML( fieldBefore ) ) }
			</span>
		) : (
			''
		);
	const afterField =
		fieldAfter !== undefined && fieldAfter !== '' ? (
			<span className="vkfs__input-text-addition vkfs__input-text-addition--after">
				{ parse( sanitizeIconHTML( fieldAfter ) ) }
			</span>
		) : (
			''
		);
	const beforeField2 =
		fieldBefore2 !== undefined && fieldBefore2 !== '' ? (
			<span className="vkfs__input-text-addition vkfs__input-text-addition--before">
				{ parse( sanitizeIconHTML( fieldBefore2 ) ) }
			</span>
		) : (
			''
		);
	const afterField2 =
		fieldAfter2 !== undefined && fieldAfter2 !== '' ? (
			<span className="vkfs__input-text-addition vkfs__input-text-addition--after">
				{ parse( sanitizeIconHTML( fieldAfter2 ) ) }
			</span>
		) : (
			''
		);
	const betweenRange =
		rangeBetween !== undefined && rangeBetween !== '' ? (
			<span className="vkfs__input-text-between">
				{ parse( sanitizeIconHTML( rangeBetween ) ) }
			</span>
		) : (
			''
		);

	let fieldTypeAlert = '';
	if ( fieldType === 'date' ) {
		fieldTypeAlert = (
			<p>
				{ __(
					'Please save the date in the format "YYYY–MM–DD". (e.g. 2020–01–01)',
					'vk-filter-search-pro'
				) }
			</p>
		);
	} else if ( fieldType === 'datetime' ) {
		fieldTypeAlert = (
			<p>
				{ __(
					'Please save the date and time in the format "YYYY–MM–DD hh:mm:ss". (e.g. 2020–01–01 00:00:00)',
					'vk-filter-search-pro'
				) }
			</p>
		);
	} else if ( fieldType === 'time' ) {
		fieldTypeAlert = (
			<p>
				{ __(
					'Please save the time in the format "hh:mm:ss". (e.g. 00:00:00)',
					'vk-filter-search-pro'
				) }
			</p>
		);
	}

	/* フォームの描画 */
	// 必要事項が揃っている場合
	if ( fieldName !== undefined && fieldCompare !== undefined ) {
		// フィールドタイプが numeric の場合
		if ( fieldType === 'numeric' ) {
			// 比較演算子が equal の場合
			if ( fieldCompare === 'equal' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--equal">
							<div className="vkfs__input-numeric-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-numeric vkfs__input-numeric--${ fieldName }_numeric_equal` }
									type="number"
									name={ `vkfs_custom-field_${ fieldName }_numeric_equal` }
									min={
										fieldMin !== undefined &&
										fieldMin !== '' &&
										! Number.isNaN(
											Number.parseFloat( fieldMin )
										)
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax !== '' &&
										! Number.isNaN(
											Number.parseFloat( fieldMax )
										)
											? fieldMax
											: undefined
									}
									step={
										fieldStep !== undefined &&
										fieldStep !== '' &&
										( fieldStep === 'any' ||
											! Number.isNaN(
												Number.parseFloat( fieldStep )
											) )
											? fieldStep
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が higher の場合
			if ( fieldCompare === 'higher' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--higher">
							<div className="vkfs__input-numeric-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-numeric vkfs__input-numeric--${ fieldName }_numeric_min` }
									type="number"
									name={ `vkfs_custom-field_${ fieldName }_numeric_min` }
									min={
										fieldMin !== undefined &&
										fieldMin !== '' &&
										! Number.isNaN(
											Number.parseFloat( fieldMin )
										)
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax !== '' &&
										! Number.isNaN(
											Number.parseFloat( fieldMax )
										)
											? fieldMax
											: undefined
									}
									step={
										fieldStep !== undefined &&
										fieldStep !== '' &&
										( fieldStep === 'any' ||
											! Number.isNaN(
												Number.parseFloat( fieldStep )
											) )
											? fieldStep
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が lower の場合
			if ( fieldCompare === 'lower' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--lower">
							<div className="vkfs__input-numeric-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-numeric vkfs__input-numeric--${ fieldName }_numeric_max` }
									type="number"
									name={ `vkfs_custom-field_${ fieldName }_numeric_max` }
									min={
										fieldMin !== undefined &&
										fieldMin !== '' &&
										! Number.isNaN(
											Number.parseFloat( fieldMin )
										)
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax !== '' &&
										! Number.isNaN(
											Number.parseFloat( fieldMax )
										)
											? fieldMax
											: undefined
									}
									step={
										fieldStep !== undefined &&
										fieldStep !== '' &&
										( fieldStep === 'any' ||
											! Number.isNaN(
												Number.parseFloat( fieldStep )
											) )
											? fieldStep
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が range の場合
			if ( fieldCompare === 'range' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--range">
							<div className="vkfs__input-numeric-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-numeric vkfs__input-numeric--${ fieldName }_numeric_min` }
									type="number"
									name={ `vkfs_custom-field_${ fieldName }_numeric_min` }
									min={
										fieldMin !== undefined &&
										fieldMin !== '' &&
										! Number.isNaN(
											Number.parseFloat( fieldMin )
										)
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax !== '' &&
										! Number.isNaN(
											Number.parseFloat( fieldMax )
										)
											? fieldMax
											: undefined
									}
									step={
										fieldStep !== undefined &&
										fieldStep !== '' &&
										( fieldStep === 'any' ||
											! Number.isNaN(
												Number.parseFloat( fieldStep )
											) )
											? fieldStep
											: undefined
									}
								/>
								{ afterField }
							</div>
							{ betweenRange }
							<div className="vkfs__input-numeric-wrap">
								{ beforeField2 }
								<input
									className={ `vkfs__input-numeric vkfs__input-numeric--${ fieldName }_numeric_max` }
									type="number"
									name={ `vkfs_custom-field_${ fieldName }_numeric_max` }
									min={
										fieldMin !== undefined &&
										fieldMin !== '' &&
										! Number.isNaN(
											Number.parseFloat( fieldMin )
										)
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax !== '' &&
										! Number.isNaN(
											Number.parseFloat( fieldMax )
										)
											? fieldMax
											: undefined
									}
									step={
										fieldStep !== undefined &&
										fieldStep !== '' &&
										( fieldStep === 'any' ||
											! Number.isNaN(
												Number.parseFloat( fieldStep )
											) )
											? fieldStep
											: undefined
									}
								/>
								{ afterField2 }
							</div>
						</div>
					</>
				);
			}
		} else if ( fieldType === 'date' ) {
			// 比較演算子が equal の場合
			if ( fieldCompare === 'equal' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--equal">
							<div className="vkfs__input-date-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-date vkfs__input-date--${ fieldName }_date_equal` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_date_equal` }
									min={
										fieldMin !== undefined &&
										fieldMin.match( /\d{4}-\d{2}-\d{2}/ )
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match( /\d{4}-\d{2}-\d{2}/ )
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が higher の場合
			if ( fieldCompare === 'higher' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--higher">
							<div className="vkfs__input-date-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-date vkfs__input-date--${ fieldName }_date_after` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_date_after` }
									min={
										fieldMin !== undefined &&
										fieldMin.match( /\d{4}-\d{2}-\d{2}/ )
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match( /\d{4}-\d{2}-\d{2}/ )
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が lower の場合
			if ( fieldCompare === 'lower' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--lower">
							<div className="vkfs__input-date-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-date vkfs__input-date--${ fieldName }_date_before` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_date_before` }
									min={
										fieldMin !== undefined &&
										fieldMin.match( /\d{4}-\d{2}-\d{2}/ )
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match( /\d{4}-\d{2}-\d{2}/ )
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が range の場合
			if ( fieldCompare === 'range' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--range">
							<div className="vkfs__input-date-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-date vkfs__input-date--${ fieldName }_date_after` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_date_after` }
									min={
										fieldMin !== undefined &&
										fieldMin.match( /\d{4}-\d{2}-\d{2}/ )
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match( /\d{4}-\d{2}-\d{2}/ )
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
							{ betweenRange }
							<div className="vkfs__input-date-wrap">
								{ beforeField2 }
								<input
									className={ `vkfs__input-date vkfs__input-date--${ fieldName }_date_before` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_date_before` }
									min={
										fieldMin !== undefined &&
										fieldMin.match( /\d{4}-\d{2}-\d{2}/ )
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match( /\d{4}-\d{2}-\d{2}/ )
											? fieldMax
											: undefined
									}
								/>
								{ afterField2 }
							</div>
						</div>
					</>
				);
			}
		} else if ( fieldType === 'datetime' ) {
			// 比較演算子が equal の場合
			if ( fieldCompare === 'equal' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--equal">
							<div className="vkfs__input-datetime-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-datetime vkfs__input-datetime--${ fieldName }_datetime_equal` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_datetime_equal` }
									min={
										fieldMin !== undefined &&
										fieldMin.match(
											/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/
										)
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match(
											/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/
										)
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が higher の場合
			if ( fieldCompare === 'higher' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--higher">
							<div className="vkfs__input-datetime-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-datetime vkfs__input-datetime--${ fieldName }_datetime_after` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_datetime_after` }
									min={
										fieldMin !== undefined &&
										fieldMin.match(
											/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/
										)
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match(
											/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/
										)
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が lower の場合
			if ( fieldCompare === 'lower' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--lower">
							<div className="vkfs__input-datetime-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-datetime vkfs__input-datetime--${ fieldName }_datetime_before` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_datetime_before` }
									min={
										fieldMin !== undefined &&
										fieldMin.match(
											/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/
										)
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match(
											/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/
										)
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が range の場合
			if ( fieldCompare === 'range' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--range">
							<div className="vkfs__input-datetime-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-datetime vkfs__input-datetime--${ fieldName }_datetime_after` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_datetime_after` }
									min={
										fieldMin !== undefined &&
										fieldMin.match(
											/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/
										)
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match(
											/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/
										)
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
							{ betweenRange }
							<div className="vkfs__input-datetime-wrap">
								{ beforeField2 }
								<input
									className={ `vkfs__input-datetime vkfs__input-datetime--${ fieldName }_datetime_before` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_datetime_before` }
									min={
										fieldMin !== undefined &&
										fieldMin.match(
											/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/
										)
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match(
											/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/
										)
											? fieldMax
											: undefined
									}
								/>
								{ afterField2 }
							</div>
						</div>
					</>
				);
			}
		} else if ( fieldType === 'time' ) {
			// 比較演算子が equal の場合
			if ( fieldCompare === 'equal' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--equal">
							<div className="vkfs__input-time-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-time vkfs__input-time--${ fieldName }_time_equal` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_time_equal` }
									min={
										fieldMin !== undefined &&
										fieldMin.match( /\d{2}:\d{2}:\d{2}/ )
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match( /\d{2}:\d{2}:\d{2}/ )
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が higher の場合
			if ( fieldCompare === 'higher' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--higher">
							<div className="vkfs__input-time-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-time vkfs__input-time--${ fieldName }_time_after` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_time_after` }
									min={
										fieldMin !== undefined &&
										fieldMin.match( /\d{2}:\d{2}:\d{2}/ )
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match( /\d{2}:\d{2}:\d{2}/ )
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が lower の場合
			if ( fieldCompare === 'lower' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--lower">
							<div className="vkfs__input-time-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-time vkfs__input-time--${ fieldName }_time_before` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_time_before` }
									min={
										fieldMin !== undefined &&
										fieldMin.match( /\d{2}:\d{2}:\d{2}/ )
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match( /\d{2}:\d{2}:\d{2}/ )
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
						</div>
					</>
				);
			}
			// 比較演算子が range の場合
			if ( fieldCompare === 'range' ) {
				blockContent = (
					<>
						<div className="vkfs__label-name">{ BlockLabel }</div>
						<div className="vkfs__input-wrap vkfs__input-wrap--range">
							<div className="vkfs__input-time-wrap">
								{ beforeField }
								<input
									className={ `vkfs__input-time vkfs__input-time--${ fieldName }_time_after` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_time_after` }
									min={
										fieldMin !== undefined &&
										fieldMin.match( /\d{2}:\d{2}:\d{2}/ )
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match( /\d{2}:\d{2}:\d{2}/ )
											? fieldMax
											: undefined
									}
								/>
								{ afterField }
							</div>
							{ betweenRange }
							<div className="vkfs__input-time-wrap">
								{ beforeField2 }
								<input
									className={ `vkfs__input-time vkfs__input-time--${ fieldName }_time_before` }
									type="text"
									name={ `vkfs_custom-field_${ fieldName }_time_before` }
									min={
										fieldMin !== undefined &&
										fieldMin.match( /\d{2}:\d{2}:\d{2}/ )
											? fieldMin
											: undefined
									}
									max={
										fieldMax !== undefined &&
										fieldMax.match( /\d{2}:\d{2}:\d{2}/ )
											? fieldMax
											: undefined
									}
								/>
								{ afterField2 }
							</div>
						</div>
					</>
				);
			}
		}
	} else {
		blockContent = (
			<>
				<div className="vkfs__label-name">{ BlockLabel }</div>
				<div className="vkfs__input-wrap--text vkfs__alert vkfs__alert--warning">
					{ __(
						'This block will active when Name of Custom Field, Compare Operator, Label of This Block is set.',
						'vk-filter-search-pro'
					) }
				</div>
			</>
		);
	}

	// 警告
	if ( alertCheck === false || alertCheck === undefined ) {
		blockProps.className +=
			' vkfs__alert vkfs__alert--danger vkfs__alert--customField ';
		return (
			<div { ...blockProps }>
				<div className={ `vkfs__alert-title` }>
					{ __( 'Caution', 'vk-filter-search-pro' ) }
				</div>
				<ul className={ `vkfs__alert-list` }>
					<li>
						{ __(
							'Searching with custom fields is very server-intensive, so using this block is inherently deprecated.',
							'vk-filter-search-pro'
						) }
						{ __(
							'For example, if you have a custom field called "Price", use this block to filter by the value of that field is deprecated.',
							'vk-filter-search-pro'
						) }
						<br />
						{ __(
							'Instead, create a "price range" by custom taxonomy, register terms such as "$0 to $99", "$100 to $499", and "$500 to $999", and search for them. Since the server load is lighter, please consider that method as much as possible.',
							'vk-filter-search-pro'
						) }
					</li>
					<li>
						{ __(
							'This block is intended for use on small sites only.',
							'vk-filter-search-pro'
						) }
						{ __(
							'Although it depends on the server specifications and the number of custom fields, the expected number of posts for the entire site is about 3000 or less.',
							'vk-filter-search-pro'
						) }
					</li>
					<li>
						{ __(
							'The response will vary greatly depending on the server specifications and the number of custom fields.',
							'vk-filter-search-pro'
						) }
						{ __(
							'Therefore, we cannot answer questions such as "How many posts is the guaranteed?"',
							'vk-filter-search-pro'
						) }
					</li>
					<li>
						{ __(
							'Only single-byte alphanumerical characters, single-byte hyphens ( - ), and single-byte underscores ( _ ) can be used in custom field names. If it contains any other characters, it will be excluded from the dropdown list.',
							'vk-filter-search-pro'
						) }
					</li>
					{ /* <li>
						{__(
							'サーバーに高負荷をかけないように1000件を超えると、カスタムフィールドの検索は自動的に無効化されます。',
							'vk-filter-search-pro'
						)}
					</li> */ }
				</ul>
				<CheckboxControl
					label={ __(
						'Use this block after understanding the above contents.',
						'vk-filter-search-pro'
					) }
					className={ `vkfs__alert-search-comfirm` }
					checked={ alertCheck }
					onChange={ ( checked ) =>
						setAttributes( { alertCheck: checked } )
					}
				/>
			</div>
		);
	}

	// 最小値と最大値の比較警告
	let minMaxAlert = '';
	if ( fieldMin !== undefined && fieldMax !== undefined ) {
		if ( fieldType === 'numeric' ) {
			if (
				Number.parseFloat( fieldMin ) > Number.parseFloat( fieldMax )
			) {
				minMaxAlert = (
					<div className="vkfs__alert vkfs__alert--warning">
						{ __(
							'Please set the minimum value to a value less than the maximum value.',
							'vk-filter-search-pro'
						) }
					</div>
				);
			}
		} else {
		}
	}

	// フォーマットの指定
	let formatMinMaxHelp = '';
	if ( fieldType === 'numeric' ) {
		formatMinMaxHelp = __(
			'Please enter a number.',
			'vk-filter-search-pro'
		);
	} else if ( fieldType === 'date' ) {
		formatMinMaxHelp = __(
			'Please enter a date in the format "YYYY–MM–DD". (e.g. 2020–01–01)',
			'vk-filter-search-pro'
		);
	} else if ( fieldType === 'datetime' ) {
		formatMinMaxHelp = __(
			'Please enter a date and time in the format "YYYY–MM–DD hh:mm:ss". (e.g. 2020–01–01 00:00:00)',
			'vk-filter-search-pro'
		);
	} else if ( fieldType === 'time' ) {
		formatMinMaxHelp = __(
			'Please enter a time in the format "hh:mm:ss". (e.g. 00:00:00)',
			'vk-filter-search-pro'
		);
	}

	// 数値のみ step の設定を追加
	let fieldStepSetting = '';
	if ( fieldType === 'numeric' ) {
		fieldStepSetting = (
			<BaseControl id={ 'vsfs_pro_cudtomField-fieldStep' }>
				<TextControl
					label={ __( 'Step', 'vk-filter-search-pro' ) }
					value={ fieldStep }
					onChange={ ( value ) =>
						setAttributes( { fieldStep: value } )
					}
					help={ __(
						'Please enter "any" or a number.',
						'vk-filter-search-pro'
					) }
				/>
			</BaseControl>
		);
	}

	return (
		<>
			<InspectorControls>
				<OuterColumnSetting { ...props } />
				<PanelBody
					title={ __(
						'Custom Field Block Options',
						'vk-filter-search-pro'
					) }
					initialOpen={ true }
				>
					<BaseControl id={ 'vsfs_pro_cudtomField-fieldName' }>
						<SelectControl
							label={ __(
								'Custom Field',
								'vk-filter-search-pro'
							) }
							value={ fieldName }
							options={ customFieldSelectOption }
							onChange={ ( value ) =>
								setAttributes( { fieldName: value } )
							}
						/>
					</BaseControl>
					<BaseControl id={ 'vsfs_pro_cudtomField-fieldType' }>
						<SelectControl
							label={ __( 'Field Type', 'vk-filter-search-pro' ) }
							value={ fieldType }
							options={ [
								{
									label: __(
										'Numeric',
										'vk-filter-search-pro'
									),
									value: 'numeric',
								},
								{
									label: __( 'Date', 'vk-filter-search-pro' ),
									value: 'date',
								},
								{
									label: __(
										'Date Time',
										'vk-filter-search-pro'
									),
									value: 'datetime',
								},
								{
									label: __( 'Time', 'vk-filter-search-pro' ),
									value: 'time',
								},
							] }
							onChange={ ( value ) =>
								setAttributes( { fieldType: value } )
							}
						/>
						{ fieldTypeAlert }
					</BaseControl>
					<BaseControl id={ 'vsfs_pro_cudtomField-fieldCompare' }>
						<SelectControl
							label={ __(
								'Compare Operator',
								'vk-filter-search-pro'
							) }
							value={ fieldCompare }
							options={ [
								{
									label: __(
										'Equal',
										'vk-filter-search-pro'
									),
									value: 'equal',
								},
								{
									label: __(
										'Higher / After',
										'vk-filter-search-pro'
									),
									value: 'higher',
								},
								{
									label: __(
										'Lower / Before',
										'vk-filter-search-pro'
									),
									value: 'lower',
								},
								{
									label: __(
										'Range',
										'vk-filter-search-pro'
									),
									value: 'range',
								},
							] }
							onChange={ ( value ) =>
								setAttributes( { fieldCompare: value } )
							}
						/>
					</BaseControl>
					<BaseControl id={ 'vsfs_pro_cudtomField-blockLabel' }>
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
						id={ 'vsfs_pro_cudtomField-fieldAround' }
						label={ __(
							'Around Text of First Field',
							'vk-filter-search-pro'
						) }
					>
						<TextControl
							label={ __(
								'Before Text of First Field',
								'vk-filter-search-pro'
							) }
							help={ __(
								'It will be displayed at the beginning of the first input field',
								'vk-filter-search-pro'
							) }
							value={ fieldBefore }
							onChange={ ( value ) =>
								setAttributes( { fieldBefore: value } )
							}
						/>
						<TextControl
							label={ __(
								'After Text of First Field',
								'vk-filter-search-pro'
							) }
							help={ __(
								'It will be displayed at the ending of the first input field',
								'vk-filter-search-pro'
							) }
							value={ fieldAfter }
							onChange={ ( value ) =>
								setAttributes( { fieldAfter: value } )
							}
						/>
					</BaseControl>
					{ fieldCompare === 'range' && (
						<BaseControl
							id={ 'vsfs_pro_cudtomField-fieldAround2' }
							label={ __(
								'Around Text of Second Field',
								'vk-filter-search-pro'
							) }
						>
							{ ' ' }
							<TextControl
								label={ __(
									'Before Text of Second Field',
									'vk-filter-search-pro'
								) }
								help={ __(
									'It will be displayed at the beginning of the second input field',
									'vk-filter-search-pro'
								) }
								value={ fieldBefore2 }
								onChange={ ( value ) =>
									setAttributes( { fieldBefore2: value } )
								}
							/>
							<TextControl
								label={ __(
									'After Text of Second Field',
									'vk-filter-search-pro'
								) }
								help={ __(
									'It will be displayed at the ending of the second input field',
									'vk-filter-search-pro'
								) }
								value={ fieldAfter2 }
								onChange={ ( value ) =>
									setAttributes( { fieldAfter2: value } )
								}
							/>
						</BaseControl>
					) }
					{ fieldCompare === 'range' && (
						<BaseControl id={ 'vsfs_pro_cudtomField-rangeBetween' }>
							<TextControl
								label={ __(
									'Text of Between Minimum Field and Maximum Field',
									'vk-filter-search-pro'
								) }
								help={ __(
									'It will be displayed between input fields',
									'vk-filter-search-pro'
								) }
								value={ rangeBetween }
								onChange={ ( value ) =>
									setAttributes( { rangeBetween: value } )
								}
							/>
						</BaseControl>
					) }
					<BaseControl id={ 'vsfs_pro_cudtomField-fieldMin' }>
						<TextControl
							label={ __( 'Minimum', 'vk-filter-search-pro' ) }
							value={ fieldMin }
							onChange={ ( value ) =>
								setAttributes( { fieldMin: value } )
							}
							help={ formatMinMaxHelp }
						/>
						{ minMaxAlert }
					</BaseControl>
					<BaseControl id={ 'vsfs_pro_cudtomField-fielMax' }>
						<TextControl
							label={ __( 'Maximum', 'vk-filter-search-pro' ) }
							value={ fieldMax }
							onChange={ ( value ) =>
								setAttributes( { fieldMax: value } )
							}
							help={ formatMinMaxHelp }
						/>
						{ minMaxAlert }
					</BaseControl>
					{ fieldStepSetting }
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				{ blockContent }
				<OuterColumnStyle { ...props } />
			</div>
		</>
	);
}
