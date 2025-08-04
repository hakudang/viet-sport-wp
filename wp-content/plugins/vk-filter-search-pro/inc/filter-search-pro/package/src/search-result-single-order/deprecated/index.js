import save2_15_1 from './2.15.1/save';
import save2_15_0 from './2.15.0/save';
import save2_11_2 from './2.11.2/save';
import save2_2_3 from './2.2.3/save';
import save1_12_1 from './1.12.1/save';

const blockAttributes = {
	blockLabel: {
		type: 'string',
		default: '',
	},
	selectOption: {
		type: 'string',
		default: '[]',
	},
};

const blockAttributes2 = {
	...blockAttributes,
	outerColumnXs: {
		type: 'string',
		default: 12,
	},
	outerColumnSm: {
		type: 'string',
		default: 12,
	},
	outerColumnMd: {
		type: 'string',
		default: 6,
	},
	outerColumnLg: {
		type: 'string',
		default: 6,
	},
	outerColumnXl: {
		type: 'string',
		default: 6,
	},
	outerColumnXxl: {
		type: 'string',
		default: 6,
	},
};

// 2.2.3 で追加
const blockAttributes3 = {
	...blockAttributes2,
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

// 2.11.2 からパラメータを追加
const blockAttributes4 = {
	...blockAttributes3,
	blockDisplay: {
		type: 'boolean',
		default: true,
	},
};

const deprecated = [
	{
		attributes: blockAttributes4,
		save: save2_15_1,
	},
	{
		attributes: blockAttributes4,
		save: save2_15_0,
	},
	{
		attributes: blockAttributes3,
		save: save2_11_2,
	},
	{
		attributes: blockAttributes2,
		save: save2_2_3,
	},
	{
		attributes: blockAttributes,
		save: save1_12_1,
	},
];
export default deprecated;
