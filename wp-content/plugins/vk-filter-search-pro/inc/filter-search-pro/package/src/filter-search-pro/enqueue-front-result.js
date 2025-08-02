/**
 * Common result JS
 */
/* eslint camelcase: 0 */
/* eslint no-shadow: 0 */
import {
	query_string,
	get_url_queries,
} from '@vk-filter-search/common/enqueue-front-component';
import { makeDropdown } from '@vk-filter-search-pro/common/enqueue-front-component';

const form_html = document.getElementsByClassName( `vk-filter-search` );
const url_queries = get_url_queries();

const set_query_value = ( i ) => {
	Object.keys( url_queries ).forEach( async ( key ) => {
		let value_array;
		if ( url_queries[ key ].indexOf( ',' ) !== -1 ) {
			value_array = url_queries[ key ].split( ',' );
		} else if ( url_queries[ key ].indexOf( '+' ) !== -1 ) {
			value_array = url_queries[ key ].split( '+' );
		} else {
			value_array = [ url_queries[ key ] ];
		}
		if ( key === 's' ) {
			const keyword_selector = form_html[ i ].querySelector(
				'.vkfs__keyword input[name="s"]'
			);
			if ( keyword_selector !== null ) {
				keyword_selector.value = decodeURIComponent(
					url_queries[ key ]
				);
			}
		} else if ( key === 'post_type' ) {
			const post_type_select_selector = form_html[ i ].querySelectorAll(
				'.vkfs__input-wrap--select.vkfs__input-wrap--post_type'
			);
			if (
				post_type_select_selector !== null &&
				post_type_select_selector !== undefined
			) {
				for ( let j = 0; j < post_type_select_selector.length; j++ ) {
					const post_type_select_options =
						post_type_select_selector[ j ].querySelectorAll(
							'option'
						);
					if (
						post_type_select_options !== null &&
						post_type_select_options !== undefined
					) {
						for (
							let k = 0;
							k < post_type_select_options.length;
							k++
						) {
							Object.keys( value_array ).forEach( ( key ) => {
								if (
									post_type_select_options[ k ].value ===
									decodeURIComponent( value_array[ key ] )
								) {
									post_type_select_options[
										k
									].selected = true;
								}
							} );
						}
					}
				}
			}

			const post_type_checkbox_selector = form_html[ i ].querySelectorAll(
				'.vkfs__input-wrap--checkbox.vkfs__input-wrap--post_type'
			);
			if (
				post_type_checkbox_selector !== null &&
				post_type_checkbox_selector !== undefined
			) {
				for ( let j = 0; j < post_type_checkbox_selector.length; j++ ) {
					const post_type_checkbox_options =
						post_type_checkbox_selector[ j ].querySelectorAll(
							'input'
						);
					if (
						post_type_checkbox_options !== null &&
						post_type_checkbox_options !== undefined
					) {
						for (
							let k = 0;
							k < post_type_checkbox_options.length;
							k++
						) {
							Object.keys( value_array ).forEach( ( key ) => {
								if (
									post_type_checkbox_options[ k ].value ===
									decodeURIComponent( value_array[ key ] )
								) {
									post_type_checkbox_options[
										k
									].checked = true;
								}
							} );
						}
					}
				}
			}

			const post_type_radio_selector = form_html[ i ].querySelectorAll(
				'.vkfs__input-wrap--radio.vkfs__input-wrap--post_type'
			);
			if (
				post_type_radio_selector !== null &&
				post_type_radio_selector !== undefined
			) {
				for ( let j = 0; j < post_type_radio_selector.length; j++ ) {
					const post_type_radio_options =
						post_type_radio_selector[ j ].querySelectorAll(
							'input'
						);
					if (
						post_type_radio_options !== null &&
						post_type_radio_options !== undefined
					) {
						for (
							let k = 0;
							k < post_type_radio_options.length;
							k++
						) {
							Object.keys( value_array ).forEach( ( key ) => {
								if (
									post_type_radio_options[ k ].value ===
									decodeURIComponent( value_array[ key ] )
								) {
									post_type_radio_options[ k ].checked = true;
								}
							} );
						}
					}
				}
			}
		} else if ( key !== 'vkfs_form_id' && key !== 'vkfs_orderby' ) {
			if (
				! key.endsWith( '_numeric_equal' ) &&
				! key.endsWith( '_numeric_min' ) &&
				! key.endsWith( '_numeric_max' ) &&
				! key.endsWith( '_date_equal' ) &&
				! key.endsWith( '_date_before' ) &&
				! key.endsWith( '_date_after' ) &&
				! key.endsWith( '_time_equal' ) &&
				! key.endsWith( '_time_before' ) &&
				! key.endsWith( '_time_after' ) &&
				! key.endsWith( '_datetime_equal' ) &&
				! key.endsWith( '_datetime_before' ) &&
				! key.endsWith( '_datetime_after' ) &&
				! key.endsWith( '_operator' ) &&
				! key.endsWith( '_ancestor' )
			) {
				const taxonomy_select_selector = form_html[
					i
				].querySelectorAll(
					`.vkfs__input-wrap--select.vkfs__input-wrap--${ key }`
				);
				if (
					taxonomy_select_selector !== null &&
					taxonomy_select_selector !== undefined
				) {
					for (
						let j = 0;
						j < taxonomy_select_selector.length;
						j++
					) {
						if (
							taxonomy_select_selector[ j ].classList.contains(
								'vkfs__input-wrap--child-dropdown'
							)
						) {
							let currentDropdown = taxonomy_select_selector[ j ];
							let termsString = '';
							if (
								url_queries[ key + '_ancestor' ] !== undefined
							) {
								termsString = decodeURIComponent(
									url_queries[ key + '_ancestor' ] +
										',' +
										url_queries[ key ]
								);
							} else {
								termsString = decodeURIComponent(
									url_queries[ key ]
								);
							}
							const split = ',';
							const terms = termsString.split( split );
							const currentOptions =
								currentDropdown.querySelectorAll( 'option' );
							currentOptions.forEach( ( option ) => {
								if ( option.value === terms[ 0 ] ) {
									option.selected = true;
								}
							} );

							for ( let l = 0; l < terms.length; l++ ) {
								const parentName = currentDropdown.name;
								const parentValue = terms[ l ];
								const parentOptionFirst =
									currentOptions[ 0 ].innerText;
								const className =
									currentDropdown.className.replace(
										` vkfs__depth-${ l }`,
										` vkfs__depth-${ l + 1 }`
									);
								const container = currentDropdown.parentNode;
								const settings = container.getAttribute(
									'data-vkfs-dropdown-options'
								);
								const dropdown = await makeDropdown(
									parentName,
									parentValue,
									parentOptionFirst,
									className,
									settings
								);
								if ( dropdown ) {
									currentDropdown.insertAdjacentHTML(
										'afterend',
										dropdown
									);
									if (
										currentDropdown.name.indexOf(
											'category'
										) !== -1
									) {
										currentDropdown.name =
											parentName.replace(
												'category',
												'category_name_ancestor'
											);
									} else if (
										currentDropdown.name.indexOf(
											'post_tag'
										) !== -1
									) {
										currentDropdown.name =
											parentName.replace(
												'post_tag',
												'tag_ancestor'
											);
									} else {
										currentDropdown.name =
											parentName.replace(
												'[]',
												'_ancestor[]'
											);
									}

									currentDropdown = container.querySelector(
										`.vkfs__depth-${ l + 1 }`
									);

									const currentOptions =
										currentDropdown.querySelectorAll(
											'option'
										);
									currentOptions.forEach( ( option ) => {
										if ( option.value === terms[ l + 1 ] ) {
											option.selected = true;
										}
									} );
								} else {
									return false;
								}
							}
						} else {
							const taxonomy_select_options =
								taxonomy_select_selector[ j ].querySelectorAll(
									'option'
								);
							if (
								taxonomy_select_options !== null &&
								taxonomy_select_options !== undefined
							) {
								for (
									let k = 0;
									k < taxonomy_select_options.length;
									k++
								) {
									Object.keys( value_array ).forEach(
										( key ) => {
											if (
												taxonomy_select_options[ k ]
													.value ===
												decodeURIComponent(
													value_array[ key ]
												)
											) {
												taxonomy_select_options[
													k
												].selected = true;
											}
										}
									);
								}
							}
						}
					}
				}

				const taxonomy_checkbox_selector = form_html[
					i
				].querySelectorAll(
					`.vkfs__input-wrap--checkbox.vkfs__input-wrap--${ key }`
				);
				if (
					taxonomy_checkbox_selector !== null &&
					taxonomy_checkbox_selector !== undefined
				) {
					for (
						let j = 0;
						j < taxonomy_checkbox_selector.length;
						j++
					) {
						const taxonomy_checkbox_options =
							taxonomy_checkbox_selector[ j ].querySelectorAll(
								'input'
							);
						if ( taxonomy_checkbox_options !== null ) {
							for (
								let k = 0;
								k < taxonomy_checkbox_options.length;
								k++
							) {
								Object.keys( value_array ).forEach( ( key ) => {
									if (
										taxonomy_checkbox_options[ k ].value ===
										decodeURIComponent( value_array[ key ] )
									) {
										taxonomy_checkbox_options[
											k
										].checked = true;
									}
								} );
							}
						}
					}
				}

				const taxonomy_checkbox_operator_selector = form_html[
					i
				].querySelectorAll(
					`.vkfs__operator-wrap.vkfs__input-wrap--radio`
				);
				if (
					taxonomy_checkbox_operator_selector !== null &&
					taxonomy_checkbox_operator_selector !== undefined
				) {
					for (
						let j = 0;
						j < taxonomy_checkbox_operator_selector.length;
						j++
					) {
						const taxonomy_checkbox_operator_options =
							taxonomy_checkbox_operator_selector[
								j
							].querySelectorAll( 'input' );
						if ( taxonomy_checkbox_operator_options !== null ) {
							for (
								let k = 0;
								k < taxonomy_checkbox_operator_options.length;
								k++
							) {
								let operator_key = '';
								if ( key.endsWith( '_operator' ) ) {
									operator_key = key;
								}
								if (
									taxonomy_checkbox_operator_options[ k ]
										.name ===
										'vkfs_' + operator_key &&
									taxonomy_checkbox_operator_options[ k ]
										.value === url_queries[ operator_key ]
								) {
									taxonomy_checkbox_operator_options[
										k
									].checked = true;
								}
							}
						}
					}
				}

				const taxonomy_radio_selector = form_html[ i ].querySelectorAll(
					`.vkfs__input-wrap--radio.vkfs__input-wrap--${ key }`
				);
				if (
					taxonomy_radio_selector !== null &&
					taxonomy_radio_selector !== undefined
				) {
					for ( let j = 0; j < taxonomy_radio_selector.length; j++ ) {
						const taxonomy_radio_options =
							taxonomy_radio_selector[ j ].querySelectorAll(
								'input'
							);
						if ( taxonomy_radio_options !== null ) {
							for (
								let k = 0;
								k < taxonomy_radio_options.length;
								k++
							) {
								Object.keys( value_array ).forEach( ( key ) => {
									if (
										taxonomy_radio_options[ k ].value ===
										decodeURIComponent( value_array[ key ] )
									) {
										taxonomy_radio_options[
											k
										].checked = true;
									}
								} );
							}
						}
					}
				}
			} else if (
				key.endsWith( '_numeric_equal' ) ||
				key.endsWith( '_numeric_min' ) ||
				key.endsWith( '_numeric_max' )
			) {
				const numericSelector = form_html[ i ].querySelector(
					`.vkfs__input-numeric.vkfs__input-numeric--${ key }`
				);
				if (
					numericSelector !== null &&
					numericSelector !== undefined
				) {
					numericSelector.value = decodeURIComponent(
						url_queries[ key ]
					);
				}
			} else if (
				key.endsWith( '_date_equal' ) ||
				key.endsWith( '_date_before' ) ||
				key.endsWith( '_date_after' )
			) {
				const dateSelector = form_html[ i ].querySelector(
					`.vkfs__input-date.vkfs__input-date--${ key }`
				);
				if ( dateSelector !== null && dateSelector !== undefined ) {
					dateSelector.value = decodeURIComponent(
						url_queries[ key ]
					);
				}
			} else if (
				key.endsWith( '_time_equal' ) ||
				key.endsWith( '_time_before' ) ||
				key.endsWith( '_time_after' )
			) {
				const timeSelector = form_html[ i ].querySelector(
					`.vkfs__input-time.vkfs__input-time--${ key }`
				);
				if ( timeSelector !== null && timeSelector !== undefined ) {
					timeSelector.value = decodeURIComponent(
						url_queries[ key ]
					);
				}
			} else if (
				key.endsWith( '_datetime_equal' ) ||
				key.endsWith( '_datetime_before' ) ||
				key.endsWith( '_datetime_after' )
			) {
				const datetimeSelector = form_html[ i ].querySelector(
					`.vkfs__input-datetime.vkfs__input-datetime--${ key }`
				);
				if (
					datetimeSelector !== null &&
					datetimeSelector !== undefined
				) {
					datetimeSelector.value = decodeURIComponent(
						url_queries[ key ]
					).replace( '+', ' ' );
				}
			}
		} else if ( key === 'vkfs_orderby' ) {
			const orderSelector = form_html[ i ].querySelector(
				`.vkfs__input-wrap--select.vkfs__input-wrap--orderby`
			);
			if ( orderSelector !== null && orderSelector !== undefined ) {
				const orderSelectOptions =
					orderSelector.querySelectorAll( 'option' );
				if (
					orderSelectOptions !== null &&
					orderSelectOptions !== undefined
				) {
					for ( let j = 0; j < orderSelectOptions.length; j++ ) {
						Object.keys( value_array ).forEach( ( key ) => {
							if (
								orderSelectOptions[ j ].value ===
								decodeURIComponent( value_array[ key ] )
							) {
								orderSelectOptions[ j ].selected = true;
							}
						} );
					}
				}
			}
		}
	} );
};

const set_default_value = ( i ) => {
	const redio_selector = form_html[ i ].querySelectorAll(
		'.vkfs__input-wrap--radio'
	);
	if ( redio_selector !== null && redio_selector !== undefined ) {
		for ( let j = 0; j < redio_selector.length; j++ ) {
			const radio_options =
				redio_selector[ j ].querySelectorAll( 'input' );
			if ( radio_options !== null ) {
				for ( let k = 0; k < radio_options.length; k++ ) {
					if (
						radio_options[ k ].value === '' ||
						radio_options[ k ].value === 'any' ||
						radio_options[ k ].value === 'and'
					) {
						radio_options[ k ].checked = true;
					}
				}
			}
		}
	}
};
document.addEventListener( 'DOMContentLoaded', function () {
	if ( query_string.indexOf( 'vkfs_submitted=true' ) === -1 ) {
		for ( let i = 0; i < form_html.length; i++ ) {
			set_default_value( i );
			set_query_value( i );
		}
	}
} );
