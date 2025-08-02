/**
 * Custom Field Block Save.
 */

// wordpress のコンポーネントをインポート
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import parse from 'html-react-parser';

// 独自のコンポーネントをインポート
import { OuterColumnClasses } from '@vk-filter-search-pro/common/outer-columns';

export default function CustomFieldSearchProSave( props ) {
	const { attributes } = props;
	const { blockLabel, selectOption } = attributes;

	const blockProps = useBlockProps.save( {
		className: `vkfs-search-result-single-order vkfs__outer-wrap vkfs__orderby ${ OuterColumnClasses(
			props
		) }`,
	} );

	const currentOption = JSON.parse( selectOption );

	const orderOption = [
		{
			label: __( 'Plese Select', 'vk-filter-search-pro' ),
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
	let blockContent = '';
	const blockContentLabel =
		blockLabel !== ''
			? parse( blockLabel )
			: __( 'Order', 'vk-filter-search-pro' );
	let blockContentInner = '';
	blockContentInner = currentOption.map( ( select, index ) => {
		let contentInner = '';
		if ( select.orderType !== '' ) {
			if (
				select.orderType === 'custom-field' &&
				select.fieldName !== ''
			) {
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
	return <div { ...blockProps }>{ blockContent }</div>;
}
