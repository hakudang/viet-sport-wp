/**
 * Custom Field Block Save.
 */

// wordpress のコンポーネントをインポート
import { useBlockProps } from '@wordpress/block-editor';

// 独自のコンポーネントをインポート
import { OuterColumnClasses } from '@vk-filter-search-pro/common/outer-columns';

export default function CustomFieldSearchProEdit( props ) {
	const { attributes } = props;
	const {
		fieldName,
		fieldCompare,
		blockLabel,
		fieldBefore,
		fieldAfter,
		rangeBetween,
	} = attributes;

	const blockProps = useBlockProps.save( {
		className: `vkfs-custom-field-search-pro vkfs__outer-wrap vkfs__custom_field ${ OuterColumnClasses(
			props
		) }`,
	} );

	// 表示内容
	let blockContent = '';
	const beforeField =
		fieldBefore !== undefined ? (
			<span className="vkfs__input-text-addition vkfs__input-text-addition--before">
				{ fieldBefore }
			</span>
		) : (
			''
		);
	const afterField =
		fieldAfter !== undefined ? (
			<span className="vkfs__input-text-addition vkfs__input-text-addition--after">
				{ fieldAfter }
			</span>
		) : (
			''
		);
	const betweenRange =
		rangeBetween !== undefined ? (
			<span className="vkfs__input-text-between">{ rangeBetween }</span>
		) : (
			''
		);

	// 必要事項が揃っている場合
	if (
		fieldName !== undefined &&
		fieldCompare !== undefined &&
		blockLabel !== undefined
	) {
		// 比較演算子が equal の場合
		if ( fieldCompare === 'equal' ) {
			blockContent = (
				<>
					<div className="vkfs__label-name">{ blockLabel }</div>
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
					<div className="vkfs__label-name">{ blockLabel }</div>
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
					<div className="vkfs__label-name">{ blockLabel }</div>
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
					<div className="vkfs__label-name">{ blockLabel }</div>
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
							{ beforeField }
							<input
								className={ `vkfs__input-text-field vkfs__input-text--${ fieldName }_range_max` }
								type="text"
								name={ `vkfs_custom-field_${ fieldName }_range_max` }
							/>
							{ afterField }
						</div>
					</div>
				</>
			);
		}
	}
	return <div { ...blockProps }>{ blockContent }</div>;
}
