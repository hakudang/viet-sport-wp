import { ReactComponent as Icon } from './icon.svg';

import metadata from './block.json';
import edit from './edit';
import save from './save';
import deprecated from './deprecated/';

const { name } = metadata;

export { metadata, name };

export const settings = {
	icon: <Icon />,
	example: {
		attributes: {
			TargetPostType: 'post',
			DisplayOnResult: false,
			DisplayOnPosttypeArchive: '[]',
			SubmitText: '',
			FormID: null,
			PostID: null,
		},
		innerBlocks: [
			{
				name: 'vk-filter-search-pro/taxonomy-search-pro',
				attributes: {
					isSelectedTaxonomy: 'category',
				},
			},
			{
				name: 'vk-filter-search-pro/taxonomy-search-pro',
				attributes: {
					isSelectedTaxonomy: 'post_tag',
				},
			},
			{
				name: 'vk-filter-search-pro/keyword-search-pro',
			},
		],
	},
	edit,
	save,
	deprecated,
};
