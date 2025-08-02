import {
	AdvancedCheckboxControl,
	isHexColor,
	isParentReusableBlock,
	sanitizeSlug,
	sanitizeIconHTML,
} from '@vk-filter-search/common/component';
import { __ } from '@wordpress/i18n';
import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import {
	PanelBody,
	BaseControl,
	SelectControl,
	RadioControl,
	ToggleControl,
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import { useEffect } from '@wordpress/element';
import { select, dispatch } from '@wordpress/data';
import parse from 'html-react-parser';
import SubmitButtonControl from './edit-submitbutton.js';

export default function FilterSearchProEdit( props ) {
	const { attributes, setAttributes, clientId } = props;

	const {
		TargetPostType,
		DisplayOnResult,
		DisplayOnPosttypeArchive,
		AutoSubmit,
		SubmitText,
		SubmitFontSize,
		SubmitLetterSpacing,
		SubmitPadding,
		SubmitBorderRadius,
		submitBackgroundColor,
		submitTextColor,
		submitBorderColor,
		submitChangeColorHover,
		FormID,
		layoutMethod,
		layoutBaseWidthMin,
		layoutGap,
		blockID,
	} = attributes;

	const pathString = window.location.pathname;

	//eslint-disable-next-line camelcase,no-undef
	const isBlockTheme = vk_filter_search_params.isBlockTheme;

	let currentPostType = null;
	let currentPostID = null;
	if (
		pathString.indexOf( 'site-editor.php' ) === -1 &&
		pathString.indexOf( 'widgets.php' ) === -1
	) {
		currentPostType = select( 'core/editor' ).getCurrentPostType();
		currentPostID = select( 'core/editor' ).getCurrentPostId();
	}

	const thisBlock = select( 'core/block-editor' ).getBlock( clientId );

	const { updateBlockAttributes } = dispatch( 'core/block-editor' );

	// 以前のバージョンからの互換処理 /////////////////////////////////////
	useEffect( () => {
		if ( clientId ) {
			// 1.13.0 以前に attributes を追加をしたときの互換処理
			// 先祖のブロックに再利用ブロックがない場合
			if ( ! isParentReusableBlock( clientId ) ) {
				// 投稿タイプが Filter Search の場合は現在の投稿 ID をそうでなければブロックの ID を FormID に格納
				if ( currentPostType === 'filter-search' && !! currentPostID ) {
					setAttributes( { FormID: currentPostID } );
				} else {
					setAttributes( { FormID: clientId } );
				}
				// 一応投稿 ID を PostID に格納しておく
				setAttributes( {
					PostID: !! currentPostID ? currentPostID : null,
				} );
				setAttributes( {
					blockID: !! clientId ? clientId : null,
				} );
			}

			// 1.13.1 で新たに SubmitLetterSpacing を追加したときの互換処理
			if ( SubmitLetterSpacing === undefined ) {
				setAttributes( {
					SubmitLetterSpacing: '',
				} );
			}

			// 1.13.1 で新たに SubmitPadding を追加したときの互換処理
			if ( SubmitPadding === undefined ) {
				setAttributes( {
					SubmitPadding: {
						top: null,
						right: null,
						bottom: null,
						left: null,
					},
				} );
			}

			// 1.13.1 で新たに SubmitBorderRadius を追加したときの互換処理
			if ( SubmitBorderRadius === undefined ) {
				setAttributes( {
					SubmitBorderRadius: {
						topLeft: null,
						topRight: null,
						bottomRight: null,
						bottomLeft: null,
					},
				} );
			}

			// 1.13.1 で新たに submitChangeColorHover を追加したときの互換処理
			if ( submitChangeColorHover === undefined ) {
				setAttributes( {
					submitChangeColorHover: false,
				} );
			}

			// レイアウト方式の初期値をここで設定
			if ( layoutMethod === undefined ) {
				// block.json では null にしてある。
				// 旧バージョンのユーザーは column レイアウトにする必要がるが、いずれは minimum レイアウトをデフォルトにしたい。
				// しかし block.json のデフォルトを column にすると、ユーザーが更新した時に column が保存されないままになるので、
				// デフォルトを変更した時にいきなり意図しない状態になる可能性が発生する。
				// その発生率を下げるために layoutMethod の値を保存させたいので block.js では null にしてある。
				setAttributes( { layoutMethod: 'column' } );
			}
		}
	}, [ clientId ] );

	// 親ブロックのレイアウト情報をもとに子ブロックのレイアウト方式の属性を上書き
	useEffect( () => {
		if ( !! thisBlock && !! thisBlock.innerBlocks ) {
			const innerBlocks = thisBlock.innerBlocks;
			innerBlocks.forEach( ( innerBlock ) => {
				if (
					select( 'core/block-editor' ).getBlockName(
						innerBlock.clientId
					) === 'vk-filter-search-pro/taxonomy-search-pro' ||
					select( 'core/block-editor' ).getBlockName(
						innerBlock.clientId
					) === 'vk-filter-search-pro/post-type-search-pro' ||
					select( 'core/block-editor' ).getBlockName(
						innerBlock.clientId
					) === 'vk-filter-search-pro/keyword-search-pro'
				) {
					updateBlockAttributes( innerBlock.clientId, {
						outerColumnWidthMethod: layoutMethod,
					} );
				}
			} );
		}
	}, [ layoutMethod ] );

	useEffect( () => {
		const iframe = document.querySelector(
			'.block-editor__container iframe'
		);
		const iframeDoc = iframe?.contentWindow?.document;
		const targetDoc = iframeDoc || document;

		// eslint-disable-next-line no-undef
		const observer = new MutationObserver( () => {
			const editorRoot = targetDoc.querySelector(
				'.block-editor-block-list__layout'
			);
			if ( ! editorRoot ) {
				return;
			}

			const filterSearchSubmit = editorRoot.querySelectorAll(
				'.vk-filter-search .vkfs_submit'
			);
			if ( filterSearchSubmit.length === 0 ) {
				return;
			}

			filterSearchSubmit.forEach( ( link ) => {
				if ( link.dataset.prevented ) {
					return;
				} // 二重適用防止

				link.dataset.prevented = 'true';
				link.addEventListener( 'click', function ( event ) {
					event.preventDefault();
					link.style.cursor = 'default';
					link.style.boxShadow = 'unset';
					link.style.pointerEvents = 'none';
				} );
				link.addEventListener( 'mouseover', function ( event ) {
					event.preventDefault();
					link.style.cursor = 'default';
					link.style.boxShadow = 'unset';
					link.style.pointerEvents = 'none';
				} );
			} );
		} );
		const observeTarget =
			targetDoc.querySelector( '.block-editor-block-list__layout' ) ||
			targetDoc.body;
		if ( observeTarget ) {
			observer.observe( observeTarget, {
				childList: true,
				subtree: true,
			} );
		}

		// クリーンアップ
		return () => {
			observer.disconnect();
		};
	}, [] );

	let postTypeAlert = '';
	if (
		( currentPostType && currentPostType !== 'filter-search' ) ||
		! currentPostType
	) {
		postTypeAlert = (
			<ul className={ `vkfs__alert vkfs__alert--warning` }>
				<li>
					{ __(
						'Please do not place the VK Filter Search Pro Block directly into the normal post content or the site editor. First, create a form using the Post Type "VK Filter Search".',
						'vk-filter-search-pro'
					) }
				</li>
				<li>
					{ __(
						'After that, please place the "Call Filter Search" Block where you want the form displayed and select the form you created.',
						'vk-filter-search-pro'
					) }
				</li>
			</ul>
		);
	}

	let blockThemeAlert = '';
	if ( isBlockTheme && DisplayOnResult ) {
		blockThemeAlert = (
			<p
				className={ `vkfs__alert vkfs__alert--warning vkfs__alert--blockTheme` }
			>
				{ __(
					'If you want to display the Search Form on the results screen, you need to put a "Search Result Form" block in the "Search" template on theme editor.',
					'vk-filter-search-pro'
				) }
			</p>
		);
	}

	let blockThemeAlertArchive = '';

	if ( isBlockTheme && '[]' !== DisplayOnPosttypeArchive ) {
		blockThemeAlertArchive = (
			<p
				className={ `vkfs__alert vkfs__alert--warning vkfs__alert--blockTheme` }
			>
				{ __(
					'If you want to display the Search Form on the Post Type Archive screen, you need to put a "Search Result Form" block in the "Archive" template on theme editor.',
					'vk-filter-search-pro'
				) }
				{ __(
					'Alternatively, uncheck it and place the search form directly in the archive page template.',
					'vk-filter-search-pro'
				) }
			</p>
		);
	}

	let formOptionControl = '';
	if (
		pathString.indexOf( 'site-editor.php' ) === -1 &&
		pathString.indexOf( 'widgets.php' ) === -1
	) {
		formOptionControl = (
			<>
				<BaseControl
					id={ 'vsfs-search-form-pro-DisplayOnResult' }
					label={ __(
						'Displaying Forms on Search Results Page',
						'vk-filter-search-pro'
					) }
				>
					<ToggleControl
						label={ __( 'Display', 'vk-filter-search-pro' ) }
						className="mb-description"
						checked={ DisplayOnResult }
						onChange={ ( checked ) =>
							setAttributes( {
								DisplayOnResult: checked,
							} )
						}
					/>
					{ blockThemeAlert }
				</BaseControl>
				<BaseControl
					id={ 'vsfs-search-form-pro-DisplayOnPosttypeArchive' }
					className={ 'components-base-control__checkbox-list' }
					label={ __(
						'Display on post type archive.',
						'vk-filter-search-pro'
					) }
				>
					<AdvancedCheckboxControl
						schema={ 'DisplayOnPosttypeArchive' }
						rawData={
							//eslint-disable-next-line camelcase,no-undef
							vk_filter_search_params.post_type_archive_checkbox
						}
						checkedData={ JSON.parse( DisplayOnPosttypeArchive ) }
						{ ...props }
					/>
					{ blockThemeAlertArchive }
				</BaseControl>
			</>
		);
	}

	let hiddenPostTypes;

	if ( TargetPostType === '' ) {
		hiddenPostTypes = '';
	} else {
		hiddenPostTypes = (
			<input type="hidden" name="post_type" value={ TargetPostType } />
		);
	}

	let hiddenResult;
	if ( DisplayOnResult ) {
		hiddenResult = (
			<input type="hidden" name="vkfs_form_id" value={ FormID } />
		);
	} else {
		hiddenResult = '';
	}

	let submitTextContent = __( 'Search', 'vk-filter-search-pro' );
	if (
		SubmitText !== null &&
		SubmitText !== undefined &&
		SubmitText !== ''
	) {
		submitTextContent = parse( sanitizeIconHTML( SubmitText ) );
	}

	// レイアウト方式指定クラスの付与
	let outerClass = `vk-filter-search vkfs vkfs__block-id--${ blockID }`;
	if ( layoutMethod === 'minimum' ) {
		outerClass += ` vkfs__layout--min`;
	}

	const blockProps = useBlockProps( {
		className: outerClass,
	} );

	// 自動検索が有効な場合はその旨をクラスに追加
	if ( !! AutoSubmit ) {
		blockProps.className += ' vkfs__submit--auto';
	}

	/******************************************************************************
	 * ボタンの処理
	 */
	// もともとボタンにあったクラス
	let submitClass = `vkfs_submit`;

	// 背景色・文字色の設定がない場合はもともとのクラスを追加
	if (
		submitBackgroundColor === undefined &&
		submitTextColor === undefined
	) {
		submitClass += ` btn btn-primary`;
	}

	// 背景色を処理
	if ( submitBackgroundColor !== undefined ) {
		if ( ! isHexColor( submitBackgroundColor ) ) {
			submitClass += ` has-background has-${ sanitizeSlug(
				submitBackgroundColor
			) }-background-color`;
		} else {
			submitClass += ` has-background`;
		}
	}

	// 文字色を処理
	if ( submitTextColor !== undefined ) {
		if ( ! isHexColor( submitTextColor ) ) {
			submitClass += ` has-text-color has-${ sanitizeSlug(
				submitTextColor
			) }-color`;
		} else {
			submitClass += ` has-text-color`;
		}
	}

	// 線色を処理
	if ( submitBorderColor !== undefined ) {
		if ( ! isHexColor( submitBorderColor ) ) {
			submitClass += ` has-border-color has-${ sanitizeSlug(
				submitBorderColor
			) }-border-color`;
		} else {
			submitClass += ` has-border-color`;
		}
	}

	if ( !! submitChangeColorHover ) {
		submitClass += ` vkfs_submit-change-color-hover`;
	}

	// スタイルを初期化
	const submitStyle = {};

	// 文字サイズを処理
	if ( !! SubmitFontSize && SubmitFontSize !== '' ) {
		submitStyle.fontSize = SubmitFontSize;
	}

	// 文字間隔を処理
	if ( !! SubmitLetterSpacing && SubmitLetterSpacing !== '' ) {
		submitStyle.letterSpacing = SubmitLetterSpacing;
	}

	// 内部余白を処理
	if ( !! SubmitPadding ) {
		if (
			!! SubmitPadding.top &&
			!! SubmitPadding.right &&
			!! SubmitPadding.bottom &&
			!! SubmitPadding.left
		) {
			// 左右が同じ場合は 上・左右・下と上下・左右と上下左右の３通りの書き方が可能
			if ( SubmitPadding.right === SubmitPadding.left ) {
				// 更に上下が同じ場合は上下・左右と上下左右の２通りの書き方が可能
				if ( SubmitPadding.top === SubmitPadding.bottom ) {
					// 更に上右が同じ場合は上下左右の書き方が可能なので TOP の値のみ採用
					if ( SubmitPadding.top === SubmitPadding.right ) {
						// 上下左右
						submitStyle.padding = SubmitPadding.top;
					} else {
						// 上下・左右
						submitStyle.padding = `${ SubmitPadding.top } ${ SubmitPadding.right }`;
					}
				} else {
					// 上・左右・下
					submitStyle.padding = `${ SubmitPadding.top } ${ SubmitPadding.right } ${ SubmitPadding.bottom }`;
				}
			} else {
				// 上・右・下・左
				submitStyle.padding = `${ SubmitPadding.top } ${ SubmitPadding.right } ${ SubmitPadding.bottom } ${ SubmitPadding.left }`;
			}
		} else {
			submitStyle.paddingTop = !! SubmitPadding.top
				? SubmitPadding.top
				: undefined;
			submitStyle.paddingRight = !! SubmitPadding.right
				? SubmitPadding.right
				: undefined;
			submitStyle.paddingBottom = !! SubmitPadding.bottom
				? SubmitPadding.bottom
				: undefined;
			submitStyle.paddingLeft = !! SubmitPadding.left
				? SubmitPadding.left
				: undefined;
		}
	}

	// 角丸を処理
	if ( !! SubmitBorderRadius ) {
		if (
			!! SubmitBorderRadius.topLeft &&
			!! SubmitBorderRadius.topRight &&
			!! SubmitBorderRadius.bottomRight &&
			!! SubmitBorderRadius.bottomLeft
		) {
			// 右上と左下が同じ場合は 左上・右上左下・右下と左上右下・右上左下と全ての３通りの書き方が可能
			if (
				SubmitBorderRadius.topRight === SubmitBorderRadius.bottomLeft
			) {
				// 更に左上と右下が同じ場合は左上右下・右上左下と全ての２通りの書き方が可能
				if (
					SubmitBorderRadius.topLeft ===
					SubmitBorderRadius.bottomRight
				) {
					// 更に左上と右上が同じ場合は全ての書き方が可能なので TOP の値のみ採用
					if (
						SubmitBorderRadius.topLeft ===
						SubmitBorderRadius.topRight
					) {
						// 全て
						submitStyle.borderRadius = SubmitBorderRadius.topLeft;
					} else {
						// 左上右下・右上左下
						submitStyle.borderRadius = `${ SubmitBorderRadius.topLeft } ${ SubmitBorderRadius.topRight }`;
					}
				} else {
					// 左上・右上左下・右下
					submitStyle.borderRadius = `${ SubmitBorderRadius.topLeft } ${ SubmitBorderRadius.topRight } ${ SubmitBorderRadius.bottomRight }`;
				}
			} else {
				// 左上・右上・左下・右下
				submitStyle.borderRadius = `${ SubmitBorderRadius.topLeft } ${ SubmitBorderRadius.topRight } ${ SubmitBorderRadius.bottomRight } ${ SubmitBorderRadius.bottomLeft }`;
			}
		} else {
			submitStyle.borderTopLeftRadius = !! SubmitBorderRadius.topLeft
				? SubmitBorderRadius.topLeft
				: undefined;
			submitStyle.borderTopRightRadius = !! SubmitBorderRadius.topRight
				? SubmitBorderRadius.topRight
				: undefined;
			submitStyle.borderBottomRightRadius =
				!! SubmitBorderRadius.bottomRight
					? SubmitBorderRadius.bottomRight
					: undefined;
			submitStyle.borderBottomLeftRadius =
				!! SubmitBorderRadius.bottomLeft
					? SubmitBorderRadius.bottomLeft
					: undefined;
		}
	}

	// 背景色を処理
	if (
		submitBackgroundColor !== undefined &&
		submitBackgroundColor !== null &&
		submitBackgroundColor !== '' &&
		isHexColor( submitBackgroundColor )
	) {
		submitStyle.backgroundColor = submitBackgroundColor;
	}

	// 文字色を処理
	if (
		submitTextColor !== undefined &&
		submitTextColor !== null &&
		submitTextColor !== '' &&
		isHexColor( submitTextColor )
	) {
		submitStyle.color = submitTextColor;
	}

	// 線色
	if (
		submitBorderColor !== undefined &&
		submitBorderColor !== null &&
		submitBorderColor !== '' &&
		isHexColor( submitBorderColor )
	) {
		submitStyle.borderColor = submitBorderColor;
	}

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Layout setting', 'vk-filter-search-pro' ) }
					initialOpen={ true }
				>
					<RadioControl
						label={ __(
							'Method of specifying width',
							'vk-filter-search-pro'
						) }
						selected={ layoutMethod }
						options={ [
							{
								label: __(
									'Set Min Width',
									'vk-filter-search-pro'
								),
								value: 'minimum',
							},
							{
								label: __(
									'Specify division count for each screen size',
									'vk-filter-search-pro'
								),
								value: 'column',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { layoutMethod: value } )
						}
					/>

					{ layoutMethod === 'minimum' ? (
						<>
							<UnitControl
								label={ __(
									'Column min width',
									'vk-filter-search-pro'
								) }
								value={ layoutBaseWidthMin }
								help={ __(
									'Please enter the minimum width of the inner block.',
									'vk-filter-search-pro'
								) }
								onChange={ ( value ) =>
									setAttributes( {
										layoutBaseWidthMin: value,
									} )
								}
							/>
							<UnitControl
								label={ __(
									'Column Gap',
									'vk-filter-search-pro'
								) }
								value={ layoutGap }
								onChange={ ( value ) =>
									setAttributes( { layoutGap: value } )
								}
							/>
						</>
					) : (
						<>
							<div className="vkfs__warning">
								{ __(
									'Please specify column width from each inner block.',
									'vk-filter-search-pro'
								) }
							</div>
						</>
					) }
				</PanelBody>
				<PanelBody
					title={ __(
						'Search Form Setting',
						'vk-filter-search-pro'
					) }
					initialOpen={ true }
				>
					{ postTypeAlert }
					<BaseControl id={ 'vsfs-search-form-pro-TargetPostType' }>
						<SelectControl
							label={ __(
								'Target of Post Type',
								'vk-filter-search-pro'
							) }
							value={ TargetPostType }
							options={
								//eslint-disable-next-line camelcase,no-undef
								vk_filter_search_pro_params.post_type_select
							}
							onChange={ ( value ) =>
								setAttributes( { TargetPostType: value } )
							}
						/>
					</BaseControl>
					{ formOptionControl }
					<BaseControl
						id={ 'vsfs-search-form-pro-autoSubmit' }
						label={ __(
							'Direct search setting',
							'vk-filter-search-pro'
						) }
					>
						<ToggleControl
							label={ __( 'Enable', 'vk-filter-search-pro' ) }
							checked={ AutoSubmit }
							onChange={ ( checked ) =>
								setAttributes( {
									AutoSubmit: checked,
								} )
							}
							help={ __(
								'If you activate this setting, then, when the user changes the pulldown, checkbox, or radio button, the search result page will be displayed without the need to press the submit button.',
								'vk-filter-search-pro'
							) }
						/>
					</BaseControl>
				</PanelBody>
				<SubmitButtonControl { ...props } />
			</InspectorControls>
			<form
				{ ...blockProps }
				method={ `get` }
				//eslint-disable-next-line camelcase,no-undef
				action={ vk_filter_search_pro_params.home_url }
			>
				<div className={ `vkfs__labels` }>
					<InnerBlocks
						// allowedBlocks は子ブロックの block.json から parent で指定されている
						templateLock={ false }
						template={ [
							[
								'vk-filter-search-pro/taxonomy-search-pro',
								{
									isSelectedTaxonomy: 'category',
								},
							],
							[
								'vk-filter-search-pro/taxonomy-search-pro',
								{
									isSelectedTaxonomy: 'post_tag',
								},
							],
							[ 'vk-filter-search-pro/keyword-search-pro' ],
						] }
					/>
				</div>
				{ hiddenPostTypes }
				{ hiddenResult }
				<input type="hidden" name="vkfs_submitted" value="true" />
				<button
					type={ `submit` }
					className={ submitClass }
					style={ submitStyle }
				>
					<span className="vkfs_submit-text">
						{ submitTextContent }
					</span>
				</button>
			</form>
			{ layoutMethod === 'minimum' && (
				<style type="text/css">{ `
					form[data-block="${ blockID }"] .block-editor-block-list__layout {
						gap: ${ layoutGap === '' ? '1em' : layoutGap };
					}
					form[data-block="${ blockID }"] .vkfs_submit {
						margin-top: calc( ${ layoutGap === '' ? '1em' : layoutGap } + 0.5em );
					}
					${
						layoutBaseWidthMin !== ''
							? `form[data-block="${ blockID }"] .block-editor-block-list__layout > .block-editor-block-list__block { flex-basis: ${ layoutBaseWidthMin }; min-width: ${ layoutBaseWidthMin }; }`
							: ''
					}
				` }</style>
			) }
		</>
	);
}
