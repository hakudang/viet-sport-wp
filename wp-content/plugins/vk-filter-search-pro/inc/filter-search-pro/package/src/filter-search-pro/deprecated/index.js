import save2_15_0 from './2.15.0/save';
import save2_4_1 from './2.4.1/save';
import save2_2_3 from './2.2.3/save';
import save2_0_6 from './2.0.6/save';
import save1_14_3 from './1.14.3/save';
import save1_13_1 from './1.13.1/save';
import save1_6_1 from './1.6.1/save';

const blockAttributes = {
	TargetPostType: {
		type: 'string',
		default: '',
	},
	DisplayOnResult: {
		type: 'boolean',
		default: false,
	},
	DisplayOnPosttypeArchive: {
		type: 'string',
		default: '[]',
	},
	SubmitText: {
		type: 'string',
		default: '',
	},
	FormID: {
		type: 'string',
		default: null,
	},
	PostID: {
		type: 'number',
		default: null,
	},
};

const blockAttributes2 = {
	...blockAttributes,
	SubmitFontSize: {
		type: 'string',
		default: '',
	},
	SubmitLetterSpacing: {
		type: 'string',
		default: '',
	},
	SubmitPadding: {
		type: 'object',
		default: {
			top: null,
			right: null,
			bottom: null,
			left: null,
		},
	},
	SubmitBorderRadius: {
		type: 'object',
		default: {
			topLeft: null,
			topRight: null,
			bottomRight: null,
			bottomLeft: null,
		},
	},
	submitBackgroundColor: {
		type: 'string',
	},
	submitTextColor: {
		type: 'string',
	},
	submitBorderColor: {
		type: 'string',
	},
	submitChangeColorHover: {
		type: 'boolean',
		default: false,
	},
};

const blockAttributes3 = {
	...blockAttributes2,
	AutoSubmit: {
		type: 'boolean',
		default: false,
	},
};

// 2.2.3 で追加
const blockAttributes4 = {
	...blockAttributes3,
	layoutMethod: {
		type: 'string',
		default: null,
	},
	layoutBaseWidthMin: {
		type: 'string',
		default: null,
	},
	layoutGap: {
		type: 'string',
		default: '1rem',
	},
};

const deprecated = [
	{
		attributes: blockAttributes4,
		save: save2_15_0,
	},
	{
		attributes: blockAttributes3,
		save: save2_4_1,
	},
	{
		attributes: blockAttributes3,
		save: save2_2_3,
	},
	{
		attributes: blockAttributes2,
		save: save2_0_6,
	},
	{
		attributes: blockAttributes2,
		save: save1_14_3,
	},
	{
		attributes: blockAttributes,
		save: save1_13_1,
	},
	{
		attributes: blockAttributes,
		save: save1_6_1,
	},
];
export default deprecated;
