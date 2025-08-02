/**
 * Get URL Queries
 */
/* eslint camelcase: 0 */
/* eslint no-shadow: 0 */

import {
	get_url_queries,
	query_string,
} from '@vk-filter-search/common/enqueue-front-component';

const set_redirect_url = () => {
	const url_queries = get_url_queries();
	const search_query = {};
	Object.keys( url_queries ).forEach( ( key ) => {
		if ( ! url_queries[ key ] ) {
			if (
				key === 'vkfs_category' &&
				url_queries.vkfs_category_name_ancestor
			) {
				let termStrings =
					url_queries.vkfs_category_name_ancestor.split( ',' );
				if ( typeof termStrings === 'string' ) {
					termStrings = [ termStrings ];
				}
				url_queries[ key ] = termStrings.pop();

				if ( termStrings.length > 0 ) {
					url_queries.vkfs_category_name_ancestor =
						termStrings.join( ',' );
				} else {
					url_queries.vkfs_category_name_ancestor = undefined;
				}
			} else if (
				key === 'vkfs_post_tag' &&
				url_queries.vkfs_tag_ancestor
			) {
				let termStrings = url_queries.vkfs_tag_ancestor.split( ',' );
				if ( typeof termStrings === 'string' ) {
					termStrings = [ termStrings ];
				}
				url_queries[ key ] = termStrings.pop();

				if ( termStrings.length > 0 ) {
					url_queries.vkfs_tag_ancestor = termStrings.join( ',' );
				} else {
					url_queries.vkfs_tag_ancestor = undefined;
				}
			} else if (
				key !== 'vkfs_submitted' &&
				key !== 'vkfs_form_id' &&
				key !== 'vkfs_orderby' &&
				! key.endsWith( '_operator' ) &&
				! key.endsWith( '_ancestor' ) &&
				! key.startsWith( 'vkfs_custom-field_' ) &&
				! key.startsWith( 'vkfs_date_' ) &&
				url_queries[ key + '_ancestor' ]
			) {
				let termStrings = url_queries[ key + '_ancestor' ].split( ',' );
				if ( typeof termStrings === 'string' ) {
					termStrings = [ termStrings ];
				}
				url_queries[ key ] = termStrings.pop();

				if ( termStrings.length > 0 ) {
					url_queries[ key + '_ancestor' ] = termStrings.join( ',' );
				} else {
					url_queries[ key + '_ancestor' ] = undefined;
				}
			} else {
				url_queries[ key ] = '';
			}
		}
	} );
	Object.keys( url_queries ).forEach( ( key ) => {
		if ( !! url_queries[ key ] ) {
			if ( key === 's' ) {
				let keyword = decodeURIComponent( url_queries[ key ] );
				keyword = keyword.replace( /　/g, '+' ); // eslint-disable-line no-irregular-whitespace
				keyword = encodeURIComponent( keyword );
				search_query.s = `s=${ keyword }`;
			} else if ( key === 'vkfs_post_type' ) {
				search_query.post_type = `post_type=${ url_queries[ key ] }`;
			} else if ( key === 'vkfs_category' ) {
				search_query.category_name = `category_name=${ url_queries[ key ] }`;
				if ( url_queries.vkfs_category_operator === 'and' ) {
					search_query.category_name =
						search_query.category_name.replace( /,/g, '+' );
				}
			} else if ( key === 'vkfs_post_tag' ) {
				search_query.post_tag = `tag=${ url_queries[ key ] }`;
				if ( url_queries.vkfs_post_tag_operator === 'and' ) {
					search_query.post_tag = search_query.post_tag.replace(
						/,/g,
						'+'
					);
				}
			} else if (
				key !== 'vkfs_submitted' &&
				key !== 'vkfs_form_id' &&
				key !== 'vkfs_orderby' &&
				! key.endsWith( '_operator' ) &&
				! key.endsWith( '_ancestor' ) &&
				! key.startsWith( 'vkfs_custom-field_' ) &&
				! key.startsWith( 'vkfs_date_' )
			) {
				if ( key.indexOf( '_operator' ) === -1 ) {
					const taxonomy_key = key.replace( 'vkfs_', '' );
					search_query[
						taxonomy_key
					] = `${ taxonomy_key }=${ url_queries[ key ] }`;
					if (
						url_queries[ `vkfs_${ taxonomy_key }_operator` ] ===
						'and'
					) {
						search_query[ taxonomy_key ] = search_query[
							taxonomy_key
						].replace( /,/g, '+' );
					}
				}
			} else if ( key.startsWith( 'vkfs_date_' ) ) {
				const dateKey = key.replace( 'vkfs_date_', '' );
				search_query[
					dateKey
				] = `${ dateKey }=${ url_queries[ key ] }`;
			} else if ( key.startsWith( 'vkfs_custom-field_' ) ) {
				const customFieldkey = key.replace( 'vkfs_custom-field_', '' );
				search_query[
					customFieldkey
				] = `${ customFieldkey }=${ url_queries[ key ] }`;
			} else if ( key === 'vkfs_orderby' ) {
				search_query.vkfs_orderby = `vkfs_orderby=${ url_queries[ key ] }`;
			} else if ( key.endsWith( '_ancestor' ) ) {
				const ancestorKey = key.replace( 'vkfs_', '' );
				search_query[
					ancestorKey
				] = `${ ancestorKey }=${ url_queries[ key ] }`;
			} else if ( key.endsWith( '_operator' ) ) {
				const operatorKey = key.replace( 'vkfs_', '' );
				search_query[
					operatorKey
				] = `${ operatorKey }=${ url_queries[ key ] }`;
			} else if ( key === 'vkfs_form_id' ) {
				search_query.vkfs_form_id = `vkfs_form_id=${ url_queries[ key ] }`;
			}
		}
	} );

	if ( search_query.s === undefined ) {
		search_query.s = 's=';
	}

	let search_url = vk_filter_search_params.home_url; // eslint-disable-line no-undef
	let url_question = false;
	if ( search_query.post_type !== undefined ) {
		search_url += '?' + search_query.post_type;
		url_question = true;
	}
	if ( search_query.category_name !== undefined ) {
		if ( url_question === false ) {
			search_url += '?' + search_query.category_name;
			url_question = true;
		} else {
			search_url += '&' + search_query.category_name;
		}
	}
	if ( search_query.post_tag !== undefined ) {
		if ( url_question === false ) {
			search_url += '?' + search_query.post_tag;
			url_question = true;
		} else {
			search_url += '&' + search_query.post_tag;
		}
	}

	Object.keys( search_query ).forEach( ( query ) => {
		if (
			search_query[ query ] !== undefined &&
			query !== 'post_type' &&
			query !== 'category_name' &&
			query !== 'post_tag' &&
			query !== 's' &&
			query !== 'vkfs_form_id' &&
			query !== 'vkfs_orderby' &&
			! query.endsWith( '_operator' ) &&
			! query.endsWith( '_ancestor' )
		) {
			if ( url_question === false ) {
				search_url += '?' + search_query[ query ];
				url_question = true;
			} else {
				search_url += '&' + search_query[ query ];
			}
		}
	} );
	if ( search_query.s !== undefined ) {
		if ( url_question === false ) {
			search_url += '?' + search_query.s;
			url_question = true;
		} else {
			search_url += '&' + search_query.s;
		}
	}
	if ( search_query.vkfs_orderby !== undefined ) {
		if ( url_question === false ) {
			search_url += '?' + search_query.vkfs_orderby;
			url_question = true;
		} else {
			search_url += '&' + search_query.vkfs_orderby;
		}
	}
	Object.keys( search_query ).forEach( ( query ) => {
		if (
			search_query[ query ] !== undefined &&
			query.endsWith( '_ancestor' )
		) {
			if ( url_question === false ) {
				search_url += '?' + search_query[ query ];
				url_question = true;
			} else {
				search_url += '&' + search_query[ query ];
			}
		}
	} );
	Object.keys( search_query ).forEach( ( query ) => {
		if (
			search_query[ query ] !== undefined &&
			query.endsWith( '_operator' )
		) {
			if ( url_question === false ) {
				search_url += '?' + search_query[ query ];
				url_question = true;
			} else {
				search_url += '&' + search_query[ query ];
			}
		}
	} );
	if ( search_query.vkfs_form_id !== undefined ) {
		if ( url_question === false ) {
			search_url += '?' + search_query.vkfs_form_id;
			url_question = true;
		} else {
			search_url += '&' + search_query.vkfs_form_id;
		}
	}
	return search_url;
};

if ( query_string.indexOf( 'vkfs_submitted=true' ) !== -1 ) {
	setTimeout( ( document.location.href = set_redirect_url() ), 0 );
}
