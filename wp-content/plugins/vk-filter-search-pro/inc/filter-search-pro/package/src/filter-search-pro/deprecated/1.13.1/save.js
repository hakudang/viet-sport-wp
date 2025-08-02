import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import parse from 'html-react-parser';

export default function save( props ) {
	const { attributes } = props;

	const { TargetPostType, DisplayOnResult, SubmitText, FormID } = attributes;

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
		submitTextContent = parse( SubmitText );
	}

	const blockProps = useBlockProps.save( {
		className: `vk-filter-search vkfs`,
	} );

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
			{ hiddenResult }
			<input type="hidden" name="vkfs_submitted" value="true" />
			<button className={ `btn btn-primary` } type={ `submit` }>
				{ submitTextContent }
			</button>
		</form>
	);
}
