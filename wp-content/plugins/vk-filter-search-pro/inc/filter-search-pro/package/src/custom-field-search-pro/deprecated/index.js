import save2_15_1 from './2.15.1/save';
import save2_15_0 from './2.15.0/save';
import save2_11_2 from './2.11.2/save';
import save2_3_3 from './2.3.3/save';
import save2_2_3 from './2.2.3/save';
import save1_7_2 from './1.7.2/save';

const blockAttributes = {
	fieldName: {
		type: 'string',
	},
	fieldCompare: {
		type: 'string',
		default: 'equal',
	},
	blockLabel: {
		type: 'string',
	},
	fieldBefore: {
		type: 'string',
	},
	fieldAfter: {
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
	alertCheck: {
		type: 'boolean',
		default: false,
	},
};

// 1.7.2 で追加
const blockAttributes2 = {
	fieldBefore2: {
		type: 'string',
	},
	fieldAfter2: {
		type: 'string',
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

// 2.3.3 で追加
const blockAttributes4 = {
	...blockAttributes3,
	fieldType: {
		type: 'string',
		default: 'numeric',
	},
	fieldMin: {
		type: 'string',
	},
	fieldMax: {
		type: 'string',
	},
	fieldStep: {
		type: 'string',
	},
};

const deprecated = [
	{
		attributes: blockAttributes2,
		save: save2_15_1,
	},
	{
		attributes: blockAttributes2,
		save: save2_15_0,
	},
	{
		attributes: blockAttributes4,
		save: save2_11_2,
	},
	{
		attributes: blockAttributes2,
		save: save2_3_3,
	},
	{
		attributes: blockAttributes2,
		save: save2_2_3,
	},
	{
		attributes: blockAttributes,
		save: save1_7_2,
	},
];
export default deprecated;
