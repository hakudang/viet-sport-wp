/**
 * Custom Field Block Save.
 */

// wordpress のコンポーネントをインポート
import { useBlockProps } from '@wordpress/block-editor';
import parse from 'html-react-parser';

// 独自のコンポーネントをインポート
import { OuterColumnClasses } from '@vk-filter-search-pro/common/outer-columns';

// 独自のコンポーネントをインポート
import { sanitizeIconHTML } from '@vk-filter-search/common/component';

export default function CustomFieldSearchProSave( props ) {
	const { attributes } = props;
	const {
		fieldName,
		fieldType,
		fieldCompare,
		fieldMin,
		fieldMax,
		fieldStep,
		blockLabel,
		fieldBefore,
		fieldAfter,
		fieldBefore2,
		fieldAfter2,
		rangeBetween,
		blockId,
	} = attributes;

	// Outer Class //////////////////////////////////////////////////////////////////
	// ブロックの識別クラスも OuterColumnClasses から edit と save に共通で付与したかったが
	// props.name が save.js に渡せないため 手動で付与
	const blockProps = useBlockProps.save( {
		className:
			`vkfs__custom-field` +
			OuterColumnClasses( props ) +
			` vkfs__block-id--${ blockId }`,
	} );

	// 表示内容
	let blockContent = '';
	const BlockLabel =
		blockLabel !== undefined && blockLabel !== ''
			? parse( sanitizeIconHTML( blockLabel ) )
			: '';
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
	}
	return <div { ...blockProps }>{ blockContent }</div>;
}
