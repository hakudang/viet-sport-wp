/**
 * WordPress dependencies
 */
import '@wordpress/core-data';
import '@wordpress/notices';
// import '@wordpress/block-editor';
import {
	registerBlockType,
	unstable__bootstrapServerSideBlockDefinitions, // eslint-disable-line camelcase
} from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import * as filterSearchPro from './filter-search-pro';
import * as keywordSearchPro from './keyword-search-pro';
import * as postDateSearchPro from './post-date-search-pro';
import * as postTypeSearchPro from './post-type-search-pro';
import * as taxonomySearchPro from './taxonomy-search-pro';
import * as customFieldSearchPro from './custom-field-search-pro';
import * as searchResultSingleOrder from './search-result-single-order';

/**
 * Function to get all the VK Blocks in an array.
 */
export const __getVKFilterSearchProBlocks = () => [
	filterSearchPro,
	keywordSearchPro,
	postDateSearchPro,
	postTypeSearchPro,
	taxonomySearchPro,
	customFieldSearchPro,
	searchResultSingleOrder,
];

/**
 * Function to register an individual block.
 *
 * @param {Object} block The block to be registered.
 */
const registerBlock = ( block ) => {
	if ( ! block ) {
		return;
	}

	const { metadata, settings, name } = block;

	if ( metadata ) {
		unstable__bootstrapServerSideBlockDefinitions( { [ name ]: metadata } );
	}
	registerBlockType( name, settings );
};

/**
 * Function to register VK Blocks.
 */
__getVKFilterSearchProBlocks().forEach( registerBlock );
