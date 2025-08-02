import { __ } from '@wordpress/i18n';
import {
	SelectControl,
	RadioControl,
	__experimentalUnitControl as UnitControl, // eslint-disable-line @wordpress/no-unsafe-wp-apis
} from '@wordpress/components';

export const InnerColumnSetting = ( props ) => {
	const { setAttributes, attributes } = props;
	// eslint-disable-next-line camelcase
	const {
		innerColumnXs,
		innerColumnSm,
		innerColumnMd,
		innerColumnLg,
		innerColumnXl,
		innerColumnXxl,
		innerColumnWidthMethod,
		innerColumnWidthMin,
	} = attributes;

	const innerColumnOptions = [
		{
			label: __( 'Unspecified', 'vk-filter-search-pro' ),
			value: '',
		},
		{
			label: __( '1 column', 'vk-filter-search-pro' ),
			value: '12',
		},
		{
			label: __( '2 column', 'vk-filter-search-pro' ),
			value: '6',
		},
		{
			label: __( '3 column', 'vk-filter-search-pro' ),
			value: '4',
		},
		{
			label: __( '4 column', 'vk-filter-search-pro' ),
			value: '3',
		},
		{
			label: __( '6 column', 'vk-filter-search-pro' ),
			value: '2',
		},
	];

	return (
		<>
			<RadioControl
				label={ __(
					'Method of specifying width',
					'vk-filter-search-pro'
				) }
				selected={ innerColumnWidthMethod }
				options={ [
					{
						label: __( 'Set Min Width', 'vk-filter-search-pro' ),
						value: 'minimum',
					},
					{
						label: __(
							'Set Number of Columns',
							'vk-filter-search-pro'
						),
						value: 'column',
					},
				] }
				onChange={ ( value ) =>
					setAttributes( { innerColumnWidthMethod: value } )
				}
			/>
			{ innerColumnWidthMethod === 'minimum' ? (
				<UnitControl
					label={ __( 'Column min width', 'vk-filter-search-pro' ) }
					value={ innerColumnWidthMin }
					help={ __(
						'Please enter the minimum column width.',
						'vk-filter-search-pro'
					) }
					onChange={ ( value ) =>
						setAttributes( { innerColumnWidthMin: value } )
					}
				/>
			) : (
				<>
					<SelectControl
						label={ __(
							'Screen size : Extra small',
							'vk-filter-search-pro'
						) }
						value={ innerColumnXs }
						onChange={ ( value ) =>
							setAttributes( { innerColumnXs: value } )
						}
						options={ innerColumnOptions }
					/>
					<SelectControl
						label={ __(
							'Screen size : Small',
							'vk-filter-search-pro'
						) }
						value={ innerColumnSm }
						onChange={ ( value ) =>
							setAttributes( { innerColumnSm: value } )
						}
						options={ innerColumnOptions }
					/>
					<SelectControl
						label={ __(
							'Screen size : Medium',
							'vk-filter-search-pro'
						) }
						value={ innerColumnMd }
						onChange={ ( value ) =>
							setAttributes( { innerColumnMd: value } )
						}
						options={ innerColumnOptions }
					/>
					<SelectControl
						label={ __(
							'Screen size : Large',
							'vk-filter-search-pro'
						) }
						value={ innerColumnLg }
						onChange={ ( value ) =>
							setAttributes( { innerColumnLg: value } )
						}
						options={ innerColumnOptions }
					/>
					<SelectControl
						label={ __(
							'Screen size : Extra large',
							'vk-filter-search-pro'
						) }
						value={ innerColumnXl }
						onChange={ ( value ) =>
							setAttributes( { innerColumnXl: value } )
						}
						options={ innerColumnOptions }
					/>
					<SelectControl
						label={ __(
							'Screen size : XX Large',
							'vk-filter-search-pro'
						) }
						value={ innerColumnXxl }
						onChange={ ( value ) =>
							setAttributes( { innerColumnXxl: value } )
						}
						options={ innerColumnOptions }
					/>
				</>
			) }
		</>
	);
};
