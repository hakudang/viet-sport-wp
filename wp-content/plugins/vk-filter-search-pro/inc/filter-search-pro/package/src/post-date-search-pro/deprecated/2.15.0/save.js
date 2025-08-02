/**
 * Custom Field Block Save.
 */

// wordpress のコンポーネントをインポート
import { useBlockProps } from '@wordpress/block-editor';
import parse from 'html-react-parser';

// 独自のコンポーネントをインポート
import { OuterColumnClasses } from '@vk-filter-search-pro/common/outer-columns';

export default function PostDateSearchProSave( props ) {
	const { attributes } = props;
	const {
		dateName,
		dateCompare,
		dateMin,
		dateMax,
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
			`vkfs__date` +
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
			<span className="vkfs__input-date-between">
				{ parse( rangeBetween ) }
			</span>
		) : (
			''
		);

	// 必要事項が揃っている場合
	if ( dateName !== undefined && dateCompare !== undefined ) {
		// 比較演算子が only の場合
		if ( dateCompare === 'only' ) {
			blockContent = (
				<>
					<div className="vkfs__label-name">{ BlockLabel }</div>
					<div className="vkfs__input-wrap vkfs__input-wrap--only">
						<div className="vkfs__input-date-wrap">
							{ beforeField }
							<input
								className={ `vkfs__input-date vkfs__input-date--${ dateName }_date_equal` }
								type="text"
								name={ `vkfs_date_${ dateName }_date_equal` }
								min={
									dateMin !== undefined &&
									dateMin.match( /\d{4}-\d{2}-\d{2}/ )
										? dateMin
										: undefined
								}
								max={
									dateMax !== undefined &&
									dateMax.match( /\d{4}-\d{2}-\d{2}/ )
										? dateMax
										: undefined
								}
							/>
							{ afterField }
						</div>
					</div>
				</>
			);
		}
		// 比較演算子が before の場合
		if ( dateCompare === 'before' ) {
			blockContent = (
				<>
					<div className="vkfs__label-name">{ BlockLabel }</div>
					<div className="vkfs__input-wrap vkfs__input-wrap--before">
						<div className="vkfs__input-date-wrap">
							{ beforeField }
							<input
								className={ `vkfs__input-date vkfs__input-date--${ dateName }_date_before` }
								type="text"
								name={ `vkfs_date_${ dateName }_date_before` }
								min={
									dateMin !== undefined &&
									dateMin.match( /\d{4}-\d{2}-\d{2}/ )
										? dateMin
										: undefined
								}
								max={
									dateMax !== undefined &&
									dateMax.match( /\d{4}-\d{2}-\d{2}/ )
										? dateMax
										: undefined
								}
							/>
							{ afterField }
						</div>
					</div>
				</>
			);
		}
		// 比較演算子が after の場合
		if ( dateCompare === 'after' ) {
			blockContent = (
				<>
					<div className="vkfs__label-name">{ BlockLabel }</div>
					<div className="vkfs__input-wrap vkfs__input-wrap--after">
						<div className="vkfs__input-date-wrap">
							{ beforeField }
							<input
								className={ `vkfs__input-date vkfs__input-date--${ dateName }_date_after` }
								type="text"
								name={ `vkfs_date_${ dateName }_date_after` }
								min={
									dateMin !== undefined &&
									dateMin.match( /\d{4}-\d{2}-\d{2}/ )
										? dateMin
										: undefined
								}
								max={
									dateMax !== undefined &&
									dateMax.match( /\d{4}-\d{2}-\d{2}/ )
										? dateMax
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
		if ( dateCompare === 'range' ) {
			blockContent = (
				<>
					<div className="vkfs__label-name">{ BlockLabel }</div>
					<div className="vkfs__input-wrap vkfs__input-wrap--range">
						<div className="vkfs__input-date-wrap">
							{ beforeField }
							<input
								className={ `vkfs__input-date vkfs__input-date--${ dateName }_date_after` }
								type="text"
								name={ `vkfs_date_${ dateName }_date_after` }
								min={
									dateMin !== undefined &&
									dateMin.match( /\d{4}-\d{2}-\d{2}/ )
										? dateMin
										: undefined
								}
								max={
									dateMax !== undefined &&
									dateMax.match( /\d{4}-\d{2}-\d{2}/ )
										? dateMax
										: undefined
								}
							/>
							{ afterField }
						</div>
						{ betweenRange }
						<div className="vkfs__input-date-wrap">
							{ beforeField2 }
							<input
								className={ `vkfs__input-date vkfs__input-date--${ dateName }_date_before` }
								type="text"
								name={ `vkfs_date_${ dateName }_date_before` }
								min={
									dateMin !== undefined &&
									dateMin.match( /\d{4}-\d{2}-\d{2}/ )
										? dateMin
										: undefined
								}
								max={
									dateMax !== undefined &&
									dateMax.match( /\d{4}-\d{2}-\d{2}/ )
										? dateMax
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
	return <div { ...blockProps }>{ blockContent }</div>;
}
