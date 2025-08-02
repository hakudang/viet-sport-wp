import { __ } from '@wordpress/i18n';
import {
	SelectControl,
	PanelBody,
	BaseControl,
	__experimentalUnitControl as UnitControl, // eslint-disable-line @wordpress/no-unsafe-wp-apis
} from '@wordpress/components';
import { select } from '@wordpress/data';
import { useEffect } from '@wordpress/element';

/**
 * Returns the parent block of a given block ID.
 *
 * @param {string} clientId - The ID of the block to get the parent of.
 * @return {Object} - The parent block object.
 */
export function getParentBlock( clientId ) {
	return select( 'core/block-editor' ).getBlockParentsByBlockName( clientId, [
		'vk-filter-search-pro/filter-search-pro',
	] );
}

/**
 * 親ブロックの attributes を取得
 * Returns the attributes of the parent block of a given block ID.
 *
 * @param {string} parentBlock - The ID of the parent block to get the attributes of.
 * @return {Object} - The attributes of the parent block object.
 */
export function getParentAttributes( parentBlock ) {
	let attributes;
	if ( parentBlock.length > 0 ) {
		attributes = select( 'core/block-editor' ).getBlock(
			parentBlock[ 0 ]
		).attributes;
	}
	return attributes;
}

/**
 * 親ブロックで指定のレイアウト方式を子ブロックの outerColumnWidthMethod に反映
 * 子ブロックを新規配置した時に親ブロックのレイアウト指定を反映するために使用
 *
 * @param {Object} attributes
 * @param {Object} setAttributes
 * @param {Object} parentAttributes
 */
export function useInheritLayout(
	attributes,
	setAttributes,
	parentAttributes
) {
	useEffect( () => {
		if (
			parentAttributes &&
			parentAttributes.layoutMethod !== attributes.outerColumnWidthMethod
		) {
			setAttributes( {
				outerColumnWidthMethod: parentAttributes.layoutMethod,
			} );
		}
	}, [ attributes.outerColumnWidthMethod ] );
}

// 最小幅指定用のスタイルタグを出力
export const OuterColumnStyle = ( props ) => {
	const { attributes, clientId } = props;

	const { outerColumnWidthMethod, outerColumnWidthMin, blockId } = attributes;

	let selector;
	if ( undefined === clientId ) {
		// save.js の場合
		selector = `.vk-filter-search .vkfs__outer-wrap.vkfs__block-id--${ blockId }`;
	} else {
		// edit.js の場合
		selector = `#block-${ clientId }`;
	}
	return (
		<>
			{ outerColumnWidthMethod === 'minimum' && outerColumnWidthMin && (
				<style type="text/css">{ `${ selector }{
						flex-basis: ${ outerColumnWidthMin };
						min-width: ${ outerColumnWidthMin };
					}` }</style>
			) }
		</>
	);
};

export const OuterColumnClasses = ( props ) => {
	const { attributes } = props;
	// eslint-disable-next-line camelcase
	const {
		outerColumnXs,
		outerColumnSm,
		outerColumnMd,
		outerColumnLg,
		outerColumnXl,
		outerColumnXxl,
		outerColumnWidthMethod,
	} = attributes;

	const outerColumnPrefix = 'vkfs__outer-wrap--col';

	const outerColumns = [
		{
			name: 'xs',
			attribute: outerColumnXs,
		},
		{
			name: 'sm',
			attribute: outerColumnSm,
		},
		{
			name: 'md',
			attribute: outerColumnMd,
		},
		{
			name: 'lg',
			attribute: outerColumnLg,
		},
		{
			name: 'xl',
			attribute: outerColumnXl,
		},
		{
			name: 'xxl',
			attribute: outerColumnXxl,
		},
	];

	// Add outer wrap class
	let outerColumnClasses = ' vkfs__outer-wrap';

	// ブロック固有のクラス名
	// if ( props.name === 'vk-filter-search-pro/custom-field-search-pro' ) {
	// 	outerColumnClasses += ` vkfs__custom-field`;
	// 	// outerColumnClasses += ` vkfs__custom_field`; //  公開画面 非推奨旧クラス名
	// 	// outerColumnClasses += ` vkfs-custom-field-search-pro`; // 公開画面 非推奨旧クラス名
	// } else if ( props.name === 'vk-filter-search-pro/keyword-search-pro' ) {
	// 	outerColumnClasses += ` vkfs__keyword`;
	// } else if ( props.name === 'vk-filter-search-pro/post-date-search-pro' ) {
	// 	outerColumnClasses += ` vkfs__date`;
	// 	// outerColumnClasses += ` vkfs-date-search-pro`; // 公開画面 非推奨旧クラス名
	// } else if ( props.name === 'vk-filter-search-pro/post-type-search-pro' ) {
	// 	outerColumnClasses += ` vkfs__post-type`;
	// 	// outerColumnClasses += ` vkfs__post_type`; // 公開画面 非推奨旧クラス名
	// } else if ( props.name === 'vk-filter-search-pro/search-result-single-order' ) {
	// 	outerColumnClasses += ` vkfs__orderby`;
	// 	// outerColumnClasses += ` vkfs-search-result-single-order`; // 公開画面 非推奨旧クラス名
	// } else if ( props.name === 'vk-filter-search-pro/taxonomy-search-pro' ) {
	// 	outerColumnClasses += ` vkfs__taxonomy`;
	// 	// outerColumnClasses += ` vkfs-taxonomy-search`; // 公開画面 非推奨旧クラス名
	// }

	// Add outer column class
	if ( outerColumnWidthMethod !== 'minimum' ) {
		outerColumns.forEach( ( column ) => {
			if ( column.attribute !== null && column.attribute !== undefined ) {
				outerColumnClasses += ` ${ outerColumnPrefix }-${ column.name }-${ column.attribute }`;
			}
		} );
	}

	return outerColumnClasses;
};

export const OuterColumnSetting = ( props ) => {
	const { setAttributes, attributes } = props;
	// eslint-disable-next-line camelcase
	const {
		outerColumnXs,
		outerColumnSm,
		outerColumnMd,
		outerColumnLg,
		outerColumnXl,
		outerColumnXxl,
		outerColumnWidthMethod,
		outerColumnWidthMin,
	} = attributes;

	const outerColumnOptions = [
		{
			label: __( '1/4', 'vk-filter-search-pro' ),
			value: '3',
		},
		{
			label: __( '1/3', 'vk-filter-search-pro' ),
			value: '4',
		},
		{
			label: __( '1/2', 'vk-filter-search-pro' ),
			value: '6',
		},
		{
			label: __( '2/3', 'vk-filter-search-pro' ),
			value: '8',
		},
		{
			label: __( '3/4', 'vk-filter-search-pro' ),
			value: '9',
		},
		{
			label: __( '1/1', 'vk-filter-search-pro' ),
			value: '12',
		},
	];

	return (
		<>
			<PanelBody
				title={ __( 'Layout setting', 'vk-filter-search-pro' ) }
				initialOpen={ true }
			>
				<BaseControl
					id={ 'vsfs-pro-common--outerColumns' }
					className={ 'components-base-control__block-width' }
					label={ __( 'Block Width', 'vk-filter-search-pro' ) }
				>
					{ outerColumnWidthMethod === 'minimum' ? (
						<>
							<UnitControl
								label={ __(
									'Column min width',
									'vk-filter-search-pro'
								) }
								value={ outerColumnWidthMin }
								onChange={ ( value ) =>
									setAttributes( {
										outerColumnWidthMin: value,
									} )
								}
							/>
							<p>
								{ __(
									'Please enter the minimum column width.',
									'vk-filter-search-pro'
								) }
							</p>
							<p>
								{ __(
									'If not filled in, the minimum keep width specified by the parent VK Filter Search Pro block will be applied.',
									'vk-filter-search-pro'
								) }
							</p>
						</>
					) : (
						<>
							<SelectControl
								label={ __(
									'Screen size : XX Large',
									'vk-filter-search-pro'
								) }
								value={ outerColumnXxl }
								onChange={ ( value ) =>
									setAttributes( { outerColumnXxl: value } )
								}
								options={ outerColumnOptions }
							/>
							<SelectControl
								label={ __(
									'Screen size : Extra large',
									'vk-filter-search-pro'
								) }
								value={ outerColumnXl }
								onChange={ ( value ) =>
									setAttributes( { outerColumnXl: value } )
								}
								options={ outerColumnOptions }
							/>
							<SelectControl
								label={ __(
									'Screen size : Large',
									'vk-filter-search-pro'
								) }
								value={ outerColumnLg }
								onChange={ ( value ) =>
									setAttributes( { outerColumnLg: value } )
								}
								options={ outerColumnOptions }
							/>
							<SelectControl
								label={ __(
									'Screen size : Medium',
									'vk-filter-search-pro'
								) }
								value={ outerColumnMd }
								onChange={ ( value ) =>
									setAttributes( { outerColumnMd: value } )
								}
								options={ outerColumnOptions }
							/>
							<SelectControl
								label={ __(
									'Screen size : Small',
									'vk-filter-search-pro'
								) }
								value={ outerColumnSm }
								onChange={ ( value ) =>
									setAttributes( { outerColumnSm: value } )
								}
								options={ outerColumnOptions }
							/>
							<SelectControl
								label={ __(
									'Screen size : Extra small',
									'vk-filter-search-pro'
								) }
								value={ outerColumnXs }
								onChange={ ( value ) =>
									setAttributes( { outerColumnXs: value } )
								}
								options={ outerColumnOptions }
							/>
						</>
					) }
				</BaseControl>
			</PanelBody>
		</>
	);
};
