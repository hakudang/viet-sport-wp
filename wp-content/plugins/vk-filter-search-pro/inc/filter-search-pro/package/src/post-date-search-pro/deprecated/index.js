import { __ } from '@wordpress/i18n';
import save2_15_0 from './2.15.0/save';
import save2_3_3 from './2.3.3/save';
import save2_2_3 from './2.2.3/save';
import save1_5_2 from './1.5.2/save';

// eslint-disable-next-line no-undef
const disableFuturePost = vkPostDateSearchPro.futurePostDisable;

const blockAttributes = {
	dateName: {
		type: 'string',
		default: 'post_date',
	},
	dateCompare: {
		type: 'string',
		default: 'only',
	},
	blockLabel: {
		type: 'string',
		default: __( 'Post Date', 'vk-filter-search-pro' ),
	},
	fieldBefore: {
		type: 'string',
	},
	fieldAfter: {
		type: 'string',
	},
	fieldBefore2: {
		type: 'string',
	},
	fieldAfter2: {
		type: 'string',
	},
	rangeBetween: {
		type: 'string',
		default: '～',
	},
	outerColumnXs: {
		type: 'string',
		default: '12',
	},
	outerColumnSm: {
		type: 'string',
		default: '12',
	},
	outerColumnMd: {
		type: 'string',
		default: '6',
	},
	outerColumnLg: {
		type: 'string',
		default: '6',
	},
	outerColumnXl: {
		type: 'string',
		default: '6',
	},
	outerColumnXxl: {
		type: 'string',
		default: '6',
	},
	disableFuturePostType: {
		type: 'string',
		default: disableFuturePost,
	},
};

// 2.2.3 で追加
const blockAttributes2 = {
	...blockAttributes,
	outerColumnWidthMethod: {
		type: 'string',
		default: null,
	},
	outerColumnWidthMin: {
		type: 'string',
		default: null,
	},
	blockId: {
		type: 'string',
	},
};

const deprecated = [
	{
		attributes: blockAttributes2,
		save: save2_15_0,
	},
	{
		attributes: blockAttributes2,
		save: save2_3_3,
	},
	{
		attributes: blockAttributes,
		save: save2_2_3,
	},
	{
		attributes: blockAttributes,
		save: save1_5_2,
	},
];
export default deprecated;
