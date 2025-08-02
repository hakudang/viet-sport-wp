import { __ } from '@wordpress/i18n';
import { ReactComponent as Icon } from './icon.svg';

import metadata from './block.json';
import edit from './edit';
import save from './save';
import deprecated from './deprecated/';

const { name } = metadata;

export { metadata, name };

// eslint-disable-next-line no-undef
const disableFuturePost = vkPostDateSearchPro.futurePostDisable;

export const settings = {
	icon: <Icon />,
	attributes: {
		...metadata.attributes,
		blockLabel: {
			type: 'string',
			default: __( 'Post Date', 'vk-filter-search-pro' ),
		},
		disableFuturePostType: {
			type: 'string',
			default: disableFuturePost,
		},
	},
	edit,
	save,
	deprecated,
};
