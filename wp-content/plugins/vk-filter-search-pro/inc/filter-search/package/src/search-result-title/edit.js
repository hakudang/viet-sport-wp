import { __, sprintf } from '@wordpress/i18n';
import {
	PanelBody,
	BaseControl,
	TextControl,
	SelectControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';
import {
	InspectorControls,
	useBlockProps,
	BlockControls,
	AlignmentControl,
} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

/* eslint-disable @wordpress/i18n-translator-comments */
export default function SearchTitleEdit( props ) {
	const { attributes, setAttributes } = props;

	const {
		outerTagName,
		textAlign,
		queriesFormat,
		queryTitleDisplay,
		queryTitleAfter,
		queryElementOR,
		queryElementAND,
		queryElementBefore,
		queryElementAfter,
		queryElementsAfter,
		queryDateMinFormat,
		queryDateMaxFormat,
		queryDaterRangeFormat,
	} = attributes;

	const vkfsIsPro = VKSearchTitle.vkfsIsPro; // eslint-disable-line no-undef
	const minDate =
		queryElementBefore + VKSearchTitle.minDate + queryElementAfter; // eslint-disable-line no-undef
	const maxDate =
		queryElementBefore + VKSearchTitle.maxDate + queryElementAfter; // eslint-disable-line no-undef

	const blockProps = useBlockProps( {
		className: `vkfs-search-result-title has-text-align-${ textAlign }`,
	} );

	const pathString = window.location.pathname;

	let editContent = '';
	if (
		pathString.indexOf( 'site-editor.php' ) === -1 &&
		pathString.indexOf( 'widgets.php' ) === -1 &&
		pathString.indexOf( 'post-new.php' ) === -1 &&
		pathString.indexOf( 'post.php' ) === -1
	) {
		editContent = (
			<div { ...blockProps }>
				<ServerSideRender
					block="vk-filter-search/search-result-title"
					attributes={ props.attributes }
				/>
			</div>
		);
	} else {
		// 投稿タイプのクエリタイトル
		const postTypeQueryTitle =
			queryTitleDisplay === 'display'
				? __( 'Post Type', 'vk-filter-search-pro' ) + queryTitleAfter
				: '';

		// ジャンルのクエリタイトル
		const genreQueryTitle =
			queryTitleDisplay === 'display'
				? __( 'Genre', 'vk-filter-search-pro' ) + queryTitleAfter
				: '';

		// エリアのクエリタイトル
		const areaQueryTitle =
			queryTitleDisplay === 'display'
				? __( 'Area', 'vk-filter-search-pro' ) + queryTitleAfter
				: '';

		// 投稿日のクエリタイトル
		const postDateQueryTitle =
			queryTitleDisplay === 'display'
				? __( 'Post Date', 'vk-filter-search-pro' ) + queryTitleAfter
				: '';

		// 更新日のクエリタイトル
		const postModifiedaQueryTitle =
			queryTitleDisplay === 'display'
				? __( 'Post Modified', 'vk-filter-search-pro' ) +
				  queryTitleAfter
				: '';

		// キーワードのクエリタイトル
		const keywordQueryTitle =
			queryTitleDisplay === 'display'
				? __( 'Keyword', 'vk-filter-search-pro' ) + queryTitleAfter
				: '';

		// 投稿タイプのタイトル
		const postTypeTitle =
			postTypeQueryTitle +
			queryElementBefore +
			__( 'Event', 'vk-filter-search-pro' ) +
			queryElementAfter +
			queryElementsAfter;

		// ジャンルのタイトル
		const genreTitle = vkfsIsPro
			? genreQueryTitle +
			  queryElementBefore +
			  __( 'Web', 'vk-filter-search-pro' ) +
			  queryElementAfter +
			  queryElementAND +
			  queryElementBefore +
			  __( 'Product', 'vk-filter-search-pro' ) +
			  queryElementAfter +
			  queryElementsAfter
			: genreQueryTitle +
			  queryElementBefore +
			  __( 'Product', 'vk-filter-search-pro' ) +
			  queryElementAfter +
			  queryElementsAfter;

		// エリアのタイトル
		const areaTitle = vkfsIsPro
			? areaQueryTitle +
			  queryElementBefore +
			  __( 'Aichi', 'vk-filter-search-pro' ) +
			  queryElementAfter +
			  queryElementOR +
			  queryElementBefore +
			  __( 'Tokyo', 'vk-filter-search-pro' ) +
			  queryElementAfter +
			  queryElementsAfter
			: areaQueryTitle +
			  queryElementBefore +
			  __( 'Aichi', 'vk-filter-search-pro' ) +
			  queryElementAfter +
			  queryElementsAfter;

		// 投稿日のタイトル
		const postDateTitle =
			postDateQueryTitle +
			sprintf( queryDaterRangeFormat, minDate, maxDate ) +
			queryElementsAfter;

		// 更新日のタイトル
		const postModifiedTitle =
			postModifiedaQueryTitle +
			sprintf( queryDateMinFormat, minDate ) +
			queryElementsAfter;

		// キーワードのタイトル
		const keywordTitle =
			keywordQueryTitle +
			queryElementBefore +
			__( 'WordPress', 'vk-filter-search-pro' ) +
			queryElementAfter +
			queryElementsAfter;

		// 検索結果のタイトル
		let searchTitle = vkfsIsPro
			? sprintf(
					queriesFormat,
					postTypeTitle +
						genreTitle +
						areaTitle +
						postDateTitle +
						postModifiedTitle +
						keywordTitle
			  )
			: sprintf(
					queriesFormat,
					postTypeTitle + genreTitle + areaTitle + keywordTitle
			  );
		searchTitle = searchTitle.slice( 0, searchTitle.length - 3 );
		const Tag = outerTagName;
		editContent = <Tag { ...blockProps }>{ searchTitle }</Tag>;
	}

	return (
		<>
			<BlockControls group="block">
				<AlignmentControl
					value={ textAlign }
					onChange={ ( value ) => {
						setAttributes( { textAlign: value } );
					} }
				/>
			</BlockControls>
			<InspectorControls>
				<PanelBody
					title={ __(
						'Search Title Option',
						'vk-filter-search-pro'
					) }
					initialOpen={ true }
				>
					<BaseControl id={ 'vkfs_searchTitle_outerTagName' }>
						<SelectControl
							label={ __(
								'HTML element',
								'vk-filter-search-pro'
							) }
							value={ outerTagName }
							options={ [
								{ label: 'Default (<div>)', value: 'div' },
								{ label: '<h1>', value: 'h1' },
								{ label: '<h2>', value: 'h2' },
							] }
							onChange={ ( value ) =>
								setAttributes( { outerTagName: value } )
							}
						/>
					</BaseControl>
					<BaseControl id={ 'vkfs_searchTitle_queriesFormat' }>
						<TextControl
							label={ __(
								'Queries title format',
								'vk-filter-search-pro'
							) }
							value={ queriesFormat }
							onChange={ ( value ) =>
								setAttributes( { queriesFormat: value } )
							}
							help={ __(
								'eg) Search Result for %s',
								'vk-filter-search-pro'
							) }
						/>
					</BaseControl>
					<BaseControl
						id={ 'vkfs_searchTitle_queryTitleDisplay' }
						label={ __(
							'Queries titile display',
							'vk-filter-search-pro'
						) }
					>
						<ToggleGroupControl
							label={ __(
								'Query Title Display',
								'vk-filter-search-pro'
							) }
							value={ queryTitleDisplay }
							onChange={ ( value ) => {
								setAttributes( { queryTitleDisplay: value } );
							} }
							isBlock
						>
							<ToggleGroupControlOption
								value="display"
								label={ __(
									'Display',
									'vk-filter-search-pro'
								) }
							/>
							<ToggleGroupControlOption
								value="hide"
								label={ __( 'Hide', 'vk-filter-search-pro' ) }
							/>
						</ToggleGroupControl>
					</BaseControl>
					<BaseControl id={ 'vkfs_searchTitle_queryTitleAfter' }>
						<TextControl
							label={ __(
								'String of the query title after',
								'vk-filter-search-pro'
							) }
							value={ queryTitleAfter }
							onChange={ ( value ) =>
								setAttributes( { queryTitleAfter: value } )
							}
							help={ __( 'eg) :', 'vk-filter-search-pro' ) }
						/>
					</BaseControl>
					{ vkfsIsPro && (
						<>
							<BaseControl
								id={ 'vkfs_searchTitle_queryElementOR' }
							>
								<TextControl
									label={ __(
										'Query element "or"',
										'vk-filter-search-pro'
									) }
									value={ queryElementOR }
									onChange={ ( value ) =>
										setAttributes( {
											queryElementOR: value,
										} )
									}
									help={ __(
										'eg) or',
										'vk-filter-search-pro'
									) }
								/>
							</BaseControl>
							<BaseControl
								id={ 'vkfs_searchTitle_queryElementAND' }
							>
								<TextControl
									label={ __(
										'Query element "and"',
										'vk-filter-search-pro'
									) }
									value={ queryElementAND }
									onChange={ ( value ) =>
										setAttributes( {
											queryElementAND: value,
										} )
									}
									help={ __(
										'eg) and',
										'vk-filter-search-pro'
									) }
								/>
							</BaseControl>
						</>
					) }
					<BaseControl id={ 'vkfs_searchTitle_queryElementBefore' }>
						<TextControl
							label={ __(
								'String before the query element',
								'vk-filter-search-pro'
							) }
							value={ queryElementBefore }
							onChange={ ( value ) =>
								setAttributes( { queryElementBefore: value } )
							}
							help={ __( 'eg) "', 'vk-filter-search-pro' ) }
						/>
					</BaseControl>
					<BaseControl id={ 'vkfs_searchTitle_queryElementAfter' }>
						<TextControl
							label={ __(
								'String after the query element',
								'vk-filter-search-pro'
							) }
							value={ queryElementAfter }
							onChange={ ( value ) =>
								setAttributes( { queryElementAfter: value } )
							}
							help={ __( 'eg) "', 'vk-filter-search-pro' ) }
						/>
					</BaseControl>
					<BaseControl id={ 'vkfs_searchTitle_queryElementsAfter' }>
						<TextControl
							label={ __(
								'The separator string after the query element',
								'vk-filter-search-pro'
							) }
							value={ queryElementsAfter }
							onChange={ ( value ) =>
								setAttributes( { queryElementsAfter: value } )
							}
							help={ __( 'eg) |', 'vk-filter-search-pro' ) }
						/>
					</BaseControl>
					{ vkfsIsPro && (
						<>
							<BaseControl
								id={ 'vkfs_searchTitle_queryDateMinFormat' }
							>
								<TextControl
									label={ __(
										'Minimum format for a date query',
										'vk-filter-search-pro'
									) }
									value={ queryDateMinFormat }
									onChange={ ( value ) =>
										setAttributes( {
											queryDateMinFormat: value,
										} )
									}
									help={ __(
										'eg) From %s',
										'vk-filter-search-pro'
									) }
								/>
							</BaseControl>
							<BaseControl
								id={ 'vkfs_searchTitle_queryDateMaxFormat' }
							>
								<TextControl
									label={ __(
										'Maximum format for a date query',
										'vk-filter-search-pro'
									) }
									value={ queryDateMaxFormat }
									onChange={ ( value ) =>
										setAttributes( {
											queryDateMaxFormat: value,
										} )
									}
									help={ __(
										'eg) To %s',
										'vk-filter-search-pro'
									) }
								/>
							</BaseControl>
							<BaseControl
								id={ 'vkfs_searchTitle_queryDaterRangeFormat' }
							>
								<TextControl
									label={ __(
										'Format for a date range query',
										'vk-filter-search-pro'
									) }
									value={ queryDaterRangeFormat }
									onChange={ ( value ) =>
										setAttributes( {
											queryDaterRangeFormat: value,
										} )
									}
									help={ __(
										'eg) From %s to %s',
										'vk-filter-search-pro'
									) }
								/>
							</BaseControl>
						</>
					) }
				</PanelBody>
			</InspectorControls>
			{ editContent }
		</>
	);
}
