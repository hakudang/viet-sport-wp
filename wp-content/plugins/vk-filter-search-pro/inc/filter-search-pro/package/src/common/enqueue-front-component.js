let isFetching = false;

export const makeDropdown = async (
	parentName,
	parentValue,
	parentOptionFirst,
	className,
	settings
) => {
	if ( isFetching ) {
		return;
	}

	isFetching = true;

	const ajaxUrl = ajax_object.ajax_url; // eslint-disable-line no-undef
	const nonce = ajax_object.nonce; // eslint-disable-line no-undef

	try {
		const response = await fetch( ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: `action=get_child_categories&parent_name=${ parentName }&parent_value=${ parentValue }&parent_class=${ className }&option_first=${ parentOptionFirst }&container_settings=${ settings }&nonce=${ nonce }`,
		} );

		const data = await response.json();

		if ( data.success ) {
			return data.data;
		}
	} catch ( error ) {
		throw error; // Optional: re-throw the error if you want to handle it further up the call stack
	} finally {
		isFetching = false;
	}
};

let currentDepth = 0;

export const contorolDropdown = async () => {
	document.body.addEventListener( 'change', async function ( e ) {
		if ( e.target.matches( '.vkfs__input-wrap--child-dropdown' ) ) {
			if ( e.target.name.indexOf( 'category_name_ancestor' ) !== -1 ) {
				e.target.name = e.target.name.replace(
					'category_name_ancestor',
					'category'
				);
			} else if ( e.target.name.indexOf( 'tag_ancestor' ) !== -1 ) {
				e.target.name = e.target.name.replace(
					'tag_ancestor',
					'post_tag'
				);
			} else if ( e.target.name.indexOf( '_ancestor' ) !== -1 ) {
				e.target.name = e.target.name.replace( '_ancestor[]', '[]' );
			}
			while ( e.target.nextElementSibling ) {
				e.target.nextElementSibling.remove();
			}
			const parentName = e.target.name;
			const parentValue = e.target.value;
			const parentOptionFirst =
				e.target.querySelector( 'option' ).innerText;
			const className = e.target.className.replace(
				` vkfs__depth-${ currentDepth }`,
				` vkfs__depth-${ currentDepth + 1 }`
			);
			const container = e.target.parentNode;
			const options = container.getAttribute(
				'data-vkfs-dropdown-options'
			);

			const dropdown = await makeDropdown(
				parentName,
				parentValue,
				parentOptionFirst,
				className,
				options
			);
			if ( dropdown ) {
				container.insertAdjacentHTML( 'beforeend', dropdown );
				currentDepth++;
				if ( e.target.name.indexOf( 'category' ) !== -1 ) {
					e.target.name = e.target.name.replace(
						'category',
						'category_name_ancestor'
					);
				} else if ( e.target.name.indexOf( 'post_tag' ) !== -1 ) {
					e.target.name = e.target.name.replace(
						'post_tag',
						'tag_ancestor'
					);
				} else {
					e.target.name = e.target.name.replace(
						'[]',
						'_ancestor[]'
					);
				}
			}
		}
	} );
};

const addCloseClass = ( trigger, content ) => {
	trigger.classList.add( `vkfs__taxonomy-accordion-trigger--close` );
	content.classList.add( `vkfs__taxonomy-accordion-content--close` );
	trigger.innerHTML = `+`;
};

const addOpenClass = ( trigger, content ) => {
	trigger.classList.add( `vkfs__taxonomy-accordion-trigger--open` );
	content.classList.add( `vkfs__taxonomy-accordion-content--open` );
	trigger.innerHTML = `-`;
};

const removeCloseClass = ( trigger, content ) => {
	trigger.classList.remove( `vkfs__taxonomy-accordion-trigger--close` );
	content.classList.remove( `vkfs__taxonomy-accordion-content--close` );
};

const removeOpenClass = ( trigger, content ) => {
	trigger.classList.remove( `vkfs__taxonomy-accordion-trigger--open` );
	content.classList.remove( `vkfs__taxonomy-accordion-content--open` );
};

const openToClose = ( trigger, content ) => {
	removeOpenClass( trigger, content );
	addCloseClass( trigger, content );
};

const closeToOpen = ( trigger, content ) => {
	removeCloseClass( trigger, content );
	addOpenClass( trigger, content );
};

const swapLabelPosition = ( parentLi ) => {
	const label = parentLi.querySelector( 'label' );
	const trigger = parentLi.querySelector(
		'.vkfs__taxonomy-accordion-trigger'
	);
	if ( label && trigger ) {
		parentLi.removeChild( trigger );
		parentLi.removeChild( label );
		parentLi.insertBefore( label, parentLi.firstChild );
		parentLi.insertBefore( trigger, label.nextSibling );
	}
};

export const taxonomyAccordion = () => {
	const taxonomySearchForm = document.querySelectorAll( `.vkfs__taxonomy` );

	if ( taxonomySearchForm ) {
		taxonomySearchForm.forEach( ( form ) => {
			const taxonomyAccordionAttribute = JSON.parse(
				form.getAttribute( `data-vkfs-taxonomy-accordion` )
			);

			if (
				taxonomyAccordionAttribute &&
				taxonomyAccordionAttribute.AccordionType &&
				taxonomyAccordionAttribute.AccordionType !== `none`
			) {
				const liHasChildNodes =
					form.querySelectorAll( `.vkfs__has-children` );
				if ( liHasChildNodes ) {
					liHasChildNodes.forEach( ( liHasChildNode ) => {
						if (
							! liHasChildNode.querySelector(
								'.vkfs__taxonomy-accordion-trigger'
							)
						) {
							const liHasChildNodeTrigger =
								document.createElement( `span` );
							liHasChildNodeTrigger.classList.add(
								'vkfs__taxonomy-accordion-trigger'
							);
							liHasChildNodeTrigger.innerHTML = `+`;
							liHasChildNode.insertBefore(
								liHasChildNodeTrigger,
								liHasChildNode.firstChild
							);
							swapLabelPosition( liHasChildNode ); // Swap position initially
							const liHasChildNodeContent =
								liHasChildNode.querySelector(
									`.vkfs__children`
								);
							liHasChildNodeContent.classList.add(
								`vkfs__taxonomy-accordion-content`
							);
							addCloseClass(
								liHasChildNodeTrigger,
								liHasChildNodeContent
							);
							liHasChildNodeTrigger.addEventListener(
								`click`,
								( event ) => {
									if (
										liHasChildNodeContent.classList.contains(
											`vkfs__taxonomy-accordion-content--close`
										)
									) {
										closeToOpen(
											liHasChildNodeTrigger,
											liHasChildNodeContent
										);
									} else if (
										liHasChildNodeContent.classList.contains(
											`vkfs__taxonomy-accordion-content--open`
										)
									) {
										openToClose(
											liHasChildNodeTrigger,
											liHasChildNodeContent
										);
									}
									swapLabelPosition( liHasChildNode ); // Swap position on toggle
									if (
										taxonomyAccordionAttribute.AccordionType ===
										`collapse`
									) {
										const ParentUl =
											event.currentTarget.parentNode
												.parentNode;
										const ParentLi =
											ParentUl.querySelectorAll(
												`.vkfs__has-children`
											);
										ParentLi.forEach( ( liHasChild ) => {
											if (
												liHasChild !==
												event.currentTarget.parentNode
											) {
												openToClose(
													liHasChild.querySelector(
														`.vkfs__taxonomy-accordion-trigger`
													),
													liHasChild.querySelector(
														`.vkfs__taxonomy-accordion-content`
													)
												);
												swapLabelPosition( liHasChild );
											}
										} );
									}
								}
							);
						}
					} );
				}
			}
		} );
	}
};
