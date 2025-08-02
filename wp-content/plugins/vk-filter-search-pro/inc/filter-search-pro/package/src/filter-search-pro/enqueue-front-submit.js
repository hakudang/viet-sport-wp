/**
 * Auto Submit JS
 */
/* eslint camelcase: 0 */
/* eslint no-shadow: 0 */
document.body.addEventListener( 'change', async function ( e ) {
	if (
		e.target.matches(
			'.vkfs__input-wrap--select, .vkfs__input-wrap--checkbox input[type="checkbox"], .vkfs__input-wrap--radio input[type="radio"]'
		) &&
		e.target.closest( '.vkfs__submit--auto' )
	) {
		e.target.closest( '.vkfs__submit--auto' ).submit();
	}
} );
