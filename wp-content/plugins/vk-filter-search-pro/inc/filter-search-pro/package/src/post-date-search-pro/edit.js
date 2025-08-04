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
} from '@wordpress/components';
import parse from 'html-react-parser';

// 独自のコンポーネントをインポート
import {
	OuterColumnSetting,
	OuterColumnClasses,
	OuterColumnStyle,
	getParentBlock,
	getParentAttributes,
	useInheritLayout,
} from '@vk-filter-search-pro/common/outer-columns';

import { useEffect } from '@wordpress/element';
import {
	AdvancedCheckboxControl,
	isParentReusableBlock,
	sanitizeIconHTML,
} from '@vk-filter-search/common/component';

export default function PostDateSearchProEdit( props ) {
	const { attributes, setAttributes, clientId } = props;
	const {
		labelAccordionType,
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
		disableFuturePostType,
		blockId,
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

	// Outer Class //////////////////////////////////////////////////////////////////
	// ブロックの識別クラスも OuterColumnClasses から edit と save に共通で付与したかったが
	// props.name が save.js に渡せないため 手動で付与
	const blockProps = useBlockProps( {
		className:
			`vkfs__date` +
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
			<span className="vkfs__input-date-between">
				{ parse( sanitizeIconHTML( rangeBetween ) ) }
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
					<div className="vkfs__label-name">
						<div className="vkfs__label-name-inner">
							{ BlockLabel }
						</div>
					</div>
					<div className="vkfs__input-form vkfs__input-wrap vkfs__input-wrap--only">
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
					<div className="vkfs__label-name">
						<div className="vkfs__label-name-inner">
							{ BlockLabel }
						</div>
					</div>
					<div className="vkfs__input-form vkfs__input-wrap vkfs__input-wrap--before">
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
					<div className="vkfs__label-name">
						<div className="vkfs__label-name-inner">
							{ BlockLabel }
						</div>
					</div>
					<div className="vkfs__input-form vkfs__input-wrap vkfs__input-wrap--after">
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
					<div className="vkfs__label-name">
						<div className="vkfs__label-name-inner">
							{ BlockLabel }
						</div>
					</div>
					<div className="vkfs__input-form vkfs__input-wrap vkfs__input-wrap--range">
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
	} else {
		blockContent = (
			<>
				<div className="vkfs__label-name">
					<div className="vkfs__label-name-inner">{ BlockLabel }</div>
				</div>
				<div className="vkfs__input-form vkfs__input-wrap vkfs__input-wrap--text vkfs__alert vkfs__alert--warning">
					{ __(
						'This block will active when Date Type, Compare Operator, Label of This Block is set.',
						'vk-filter-search-pro'
					) }
				</div>
			</>
		);
	}

	let minMaxAlert = '';
	if ( dateMin > dateMax ) {
		minMaxAlert = (
			<div className="vkfs__alert vkfs__alert--danger">
				{ __(
					'Please set the minimum value to a value less than the maximum value.',
					'vk-filter-search-pro'
				) }
			</div>
		);
	}

	return (
		<>
			<InspectorControls>
				{ labelAccordionType === 'none' && (
					<OuterColumnSetting { ...props } />
				) }
				<PanelBody
					title={ __(
						'Date Block Description',
						'vk-filter-search-pro'
					) }
					initialOpen={ true }
				>
					{ ' ' }
					<p className={ `mt-0 mb-3` }>
						{ __(
							'This block is intended for use in searches such as events and facility usage. Search is performed based on the release date, but WordPress is supposed to be used after making it public even on a future date because future posts will be reserved posts by default.',
							'vk-filter-search-pro'
						) }
					</p>
					<BaseControl
						id={ 'vsfs_pro_date-disableFuturePostType' }
						label={ __(
							'Post Type of Disable Future Post',
							'vk-filter-search-pro'
						) }
						help={ __(
							'This setting item is common to all post date search blocks.',
							'vk-filter-search-pro'
						) }
					>
						<AdvancedCheckboxControl
							schema={ 'disableFuturePostType' }
							rawData={
								//eslint-disable-next-line camelcase,no-undef
								vk_filter_search_pro_params.post_type_checkbox
							}
							checkedData={ JSON.parse( disableFuturePostType ) }
							{ ...props }
						/>
					</BaseControl>
				</PanelBody>
				<PanelBody
					title={ __( 'Date Block Options', 'vk-filter-search-pro' ) }
					initialOpen={ true }
				>
					<BaseControl id={ 'vsfs_pro_date-dateName' }>
						<SelectControl
							label={ __( 'Date Type', 'vk-filter-search-pro' ) }
							value={ dateName }
							options={ [
								{
									label: __(
										'Publish date',
										'vk-filter-search-pro'
									),
									value: 'post_date',
								},
								{
									label: __(
										'Modified Date',
										'vk-filter-search-pro'
									),
									value: 'post_modified',
								},
							] }
							onChange={ ( value ) =>
								setAttributes( { dateName: value } )
							}
						/>
					</BaseControl>
					<BaseControl id={ 'vsfs_pro_date-dateCompare' }>
						<SelectControl
							label={ __(
								'Comparison conditions',
								'vk-filter-search-pro'
							) }
							value={ dateCompare }
							options={ [
								{
									label: __(
										'Only Specified Date',
										'vk-filter-search-pro'
									),
									value: 'only',
								},
								{
									label: __(
										'Before Specified Date',
										'vk-filter-search-pro'
									),
									value: 'before',
								},
								{
									label: __(
										'After Specified Date',
										'vk-filter-search-pro'
									),
									value: 'after',
								},
								{
									label: __(
										'In Specified Range',
										'vk-filter-search-pro'
									),
									value: 'range',
								},
							] }
							onChange={ ( value ) =>
								setAttributes( { dateCompare: value } )
							}
						/>
					</BaseControl>
					<BaseControl id={ 'vsfs_pro_cudtomField-dateMin' }>
						<TextControl
							label={ __( 'Minimum', 'vk-filter-search-pro' ) }
							value={ dateMin }
							onChange={ ( value ) =>
								setAttributes( { dateMin: value } )
							}
							help={ __(
								'Please enter a date in the format "YYYY–MM–DD". (e.g. 2020–01–01)',
								'vk-filter-search-pro'
							) }
						/>
						{ minMaxAlert }
					</BaseControl>
					<BaseControl id={ 'vsfs_pro_cudtomField-fielMax' }>
						<TextControl
							label={ __( 'Maximum', 'vk-filter-search-pro' ) }
							value={ dateMax }
							onChange={ ( value ) =>
								setAttributes( { dateMax: value } )
							}
							help={ __(
								'Please enter a date in the format "YYYY–MM–DD". (e.g. 2020–01–01)',
								'vk-filter-search-pro'
							) }
						/>
						{ minMaxAlert }
					</BaseControl>
					<BaseControl id={ 'vsfs_pro_date-blockLabel' }>
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
							'Text Around The First Field',
							'vk-filter-search-pro'
						) }
					>
						<TextControl
							label={ __(
								'Before The Field',
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
								'After The Field',
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
					{ dateCompare === 'range' && (
						<BaseControl
							id={ 'vsfs_pro_cudtomField-fieldAround2' }
							label={ __(
								'Text Around The Second Field',
								'vk-filter-search-pro'
							) }
						>
							{ ' ' }
							<TextControl
								label={ __(
									'Before The Field',
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
									'After The Field',
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
					{ dateCompare === 'range' && (
						<BaseControl id={ 'vsfs_pro_date-rangeBetween' }>
							<TextControl
								label={ __(
									'Text Between First and Second Field',
									'vk-filter-search-pro'
								) }
								help={ __(
									'It will be displayed between input dates.',
									'vk-filter-search-pro'
								) }
								value={ rangeBetween }
								onChange={ ( value ) =>
									setAttributes( { rangeBetween: value } )
								}
							/>
						</BaseControl>
					) }
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				{ blockContent }
				<OuterColumnStyle { ...props } />
			</div>
		</>
	);
}
