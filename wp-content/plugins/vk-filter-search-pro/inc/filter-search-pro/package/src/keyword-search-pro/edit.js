import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, BaseControl, TextControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { useEffect } from '@wordpress/element';

import {
	OuterColumnSetting,
	OuterColumnClasses,
	OuterColumnStyle,
	getParentBlock,
	getParentAttributes,
	useInheritLayout,
} from '@vk-filter-search-pro/common/outer-columns';
import { isParentReusableBlock } from '@vk-filter-search/common/component';

export default function KeywordSearchProEdit( props ) {
	const { attributes, setAttributes, clientId } = props;
	const { BlockLabel, Placeholder } = attributes;

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
		className: `vkfs__keyword` + OuterColumnClasses( props ),
	} );

	return (
		<>
			<InspectorControls>
				<OuterColumnSetting { ...props } />
				<PanelBody
					title={ __(
						'Keyword Block Options',
						'vk-filter-search-pro'
					) }
					initialOpen={ true }
				>
					<BaseControl id={ 'vsfs-pro-keyword-01' }>
						<TextControl
							label={ __(
								'Label of This Block',
								'vk-filter-search-pro'
							) }
							value={ BlockLabel }
							onChange={ ( value ) =>
								setAttributes( { BlockLabel: value } )
							}
						/>
					</BaseControl>
					<BaseControl id={ 'vsfs-pro-keyword-02' }>
						<TextControl
							label={ __(
								'Placeholder',
								'vk-filter-search-pro'
							) }
							value={ Placeholder }
							onChange={ ( value ) =>
								setAttributes( { Placeholder: value } )
							}
						/>
					</BaseControl>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<ServerSideRender
					block="vk-filter-search-pro/keyword-search-pro"
					attributes={ props.attributes }
				/>
				<OuterColumnStyle { ...props } />
			</div>
		</>
	);
}
