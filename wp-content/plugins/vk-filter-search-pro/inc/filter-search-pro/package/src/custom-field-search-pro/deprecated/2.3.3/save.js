/**
 * Custom Field Block Save.
 */

// wordpress のコンポーネントをインポート
import { useBlockProps } from '@wordpress/block-editor';
import parse from 'html-react-parser';

// 独自のコンポーネントをインポート
import { OuterColumnClasses } from '@vk-filter-search-pro/common/outer-columns';

export default function CustomFieldSearchProSave( props ) {
	const { attributes } = props;
	const {
		fieldName,
		fieldCompare,
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
			? parse( blockLabel )
			: '';
	const beforeField =
		fieldBefore !== undefined && fieldBefore !== '' ? (
			<span className="vkfs__input-text-addition vkfs__input-text-addition--before">
				{ parse( fieldBefore ) }
			</span>
		) : (
			''
		);
	const afterField =
		fieldAfter !== undefined && fieldAfter !== '' ? (
			<span className="vkfs__input-text-addition vkfs__input-text-addition--after">
				{ parse( fieldAfter ) }
			</span>
		) : (
			''
		);
	const beforeField2 =
		fieldBefore2 !== undefined && fieldBefore2 !== '' ? (
			<span className="vkfs__input-text-addition vkfs__input-text-addition--before">
				{ parse( fieldBefore2 ) }
			</span>
		) : (
			''
		);
	const afterField2 =
		fieldAfter2 !== undefined && fieldAfter2 !== '' ? (
			<span className="vkfs__input-text-addition vkfs__input-text-addition--after">
				{ parse( fieldAfter2 ) }
			</span>
		) : (
			''
		);
	const betweenRange =
		rangeBetween !== undefined && rangeBetween !== '' ? (
			<span className="vkfs__input-text-between">
				{ parse( rangeBetween ) }
			</span>
		) : (
			''
		);

	// 必要事項が揃っている場合
	if ( fieldName !== undefined && fieldCompare !== undefined ) {
		// 比較演算子が equal の場合
		if ( fieldCompare === 'equal' ) {
			blockContent = (
				<>
					<div className="vkfs__label-name">
						<div className="vkfs__label-name-inner">
							{ BlockLabel }
						</div>
					</div>
					<div className="vkfs__input-wrap vkfs__input-wrap--equal">
						<div className="vkfs__input-text-wrap">
							{ beforeField }
							<input
								className={ `vkfs__input-text-field vkfs__input-text--${ fieldName }_value` }
								type="text"
								name={ `vkfs_custom-field_${ fieldName }_value` }
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
					<div className="vkfs__label-name">
						<div className="vkfs__label-name-inner">
							{ BlockLabel }
						</div>
					</div>
					<div className="vkfs__input-wrap vkfs__input-wrap--higher">
						<div className="vkfs__input-text-wrap">
							{ beforeField }
							<input
								className={ `vkfs__input-text-field vkfs__input-text--${ fieldName }_value_min` }
								type="text"
								name={ `vkfs_custom-field_${ fieldName }_value_min` }
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
					<div className="vkfs__label-name">
						<div className="vkfs__label-name-inner">
							{ BlockLabel }
						</div>
					</div>
					<div className="vkfs__input-wrap vkfs__input-wrap--lower">
						<div className="vkfs__input-text-wrap">
							{ beforeField }
							<input
								className={ `vkfs__input-text-field vkfs__input-text--${ fieldName }_value_max` }
								type="text"
								name={ `vkfs_custom-field_${ fieldName }_value_max` }
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
					<div className="vkfs__label-name">
						<div className="vkfs__label-name-inner">
							{ BlockLabel }
						</div>
					</div>
					<div className="vkfs__input-wrap vkfs__input-wrap--range">
						<div className="vkfs__input-text-wrap">
							{ beforeField }
							<input
								className={ `vkfs__input-text-field vkfs__input-text--${ fieldName }_range_min` }
								type="text"
								name={ `vkfs_custom-field_${ fieldName }_range_min` }
							/>
							{ afterField }
						</div>
						{ betweenRange }
						<div className="vkfs__input-text-wrap">
							{ beforeField2 }
							<input
								className={ `vkfs__input-text-field vkfs__input-text--${ fieldName }_range_max` }
								type="text"
								name={ `vkfs_custom-field_${ fieldName }_range_max` }
							/>
							{ afterField2 }
						</div>
					</div>
				</>
			);
		}
	}
	return <div { ...blockProps }>{ blockContent }</div>;
}
