import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import parse from 'html-react-parser';
import { isHexColor } from '@vk-filter-search/common/component';

export default function save( props ) {
	const { attributes } = props;

	const {
		TargetPostType,
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
	} = attributes;

	let hiddenPostTypes;

	if ( TargetPostType === '' ) {
		hiddenPostTypes = '';
	} else {
		hiddenPostTypes = (
			<input
				type="hidden"
				name="vkfs_post_type[]"
				value={ TargetPostType }
			/>
		);
	}

	let submitTextContent = __( 'Search', 'vk-filter-search-pro' );
	if (
		SubmitText !== null &&
		SubmitText !== undefined &&
		SubmitText !== ''
	) {
		submitTextContent = parse( SubmitText );
	}

	const blockProps = useBlockProps.save( {
		className: `vk-filter-search vkfs`,
	} );

	// 自動検索が有効な場合はその旨をクラスに追加
	if ( !! AutoSubmit ) {
		blockProps.className += ' vkfs__submit--auto';
	}

	/**
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
			submitClass += ` has-background has-${ submitBackgroundColor }-background-color`;
		} else {
			submitClass += ` has-background has-custom-background-color`;
		}
	}

	// 文字色を処理
	if ( submitTextColor !== undefined ) {
		if ( ! isHexColor( submitTextColor ) ) {
			submitClass += ` has-text-color has-${ submitTextColor }-color`;
		} else {
			submitClass += ` has-text-color has-custom-text-color`;
		}
	}

	// 線色を処理
	if ( submitBorderColor !== undefined ) {
		if ( ! isHexColor( submitBorderColor ) ) {
			submitClass += ` has-border-color has-${ submitBorderColor }-border-color`;
		} else {
			submitClass += ` has-border-color has-custom-border-color`;
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
		<form
			{ ...blockProps }
			method={ `get` }
			//eslint-disable-next-line camelcase,no-undef
			action={ vk_filter_search_pro_params.home_url }
		>
			<div className={ `vkfs__labels` }>
				<InnerBlocks.Content />
			</div>
			[no_keyword_hidden_input]
			{ hiddenPostTypes }
			[filter_search_result_input]
			<input type="hidden" name="vkfs_submitted" value="true" />
			<button
				type={ `submit` }
				className={ submitClass }
				style={ submitStyle }
			>
				<span className="vkfs_submit-text">{ submitTextContent }</span>
			</button>
		</form>
	);
}
