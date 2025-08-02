const inputDate = document.querySelectorAll( '.vkfs__input-date' );
// eslint-disable-next-line no-undef
const locale = vkfsDateParams.locale;

inputDate.forEach( ( date ) => {
	const dateOptions = {
		locale,
		dateFormat: 'Y-m-d',
		disableMobile: true,
		minDate: date.min !== undefined && date.min !== null ? date.min : '',
		maxDate: date.max !== undefined && date.max !== null ? date.max : '',
	};
	// eslint-disable-next-line no-undef
	flatpickr( date, dateOptions );
} );

const inputDateTime = document.querySelectorAll( '.vkfs__input-datetime' );

inputDateTime.forEach( ( date ) => {
	const dateTimeOptions = {
		locale,
		enableTime: true,
		enableSeconds: true,
		time_24hr: true,
		minuteIncrement: 1,
		dateFormat: 'Y-m-d H:i:s',
		disableMobile: true,
		minDate:
			date.min !== undefined &&
			date.min !== null &&
			date.min.match( /\d{4}-\d{2}-\d{2}/ )
				? date.min.match( /\d{4}-\d{2}-\d{2}/ )[ 0 ]
				: '',
		maxDate:
			date.max !== undefined &&
			date.max !== null &&
			date.max.match( /\d{4}-\d{2}-\d{2}/ )
				? date.max.match( /\d{4}-\d{2}-\d{2}/ )[ 0 ]
				: '',
		minTime:
			date.min !== undefined &&
			date.min !== null &&
			date.min.match( /\d{2}:\d{2}:\d{2}/ )
				? date.min.match( /\d{2}:\d{2}:\d{2}/ )[ 0 ]
				: '',
		maxTime:
			date.max !== undefined &&
			date.max !== null &&
			date.max.match( /\d{2}:\d{2}:\d{2}/ )
				? date.max.match( /\d{2}:\d{2}:\d{2}/ )[ 0 ]
				: '',
	};
	// eslint-disable-next-line no-undef
	flatpickr( date, dateTimeOptions );
} );

const inputTime = document.querySelectorAll( '.vkfs__input-time' );

inputTime.forEach( ( date ) => {
	const timeOptions = {
		locale,
		enableTime: true,
		enableSeconds: true,
		minuteIncrement: 1,
		noCalendar: true,
		dateFormat: 'H:i:s',
		disableMobile: true,
		time_24hr: true,
		minTime: date.min !== undefined && date.min !== null ? date.min : '',
		maxTime: date.max !== undefined && date.max !== null ? date.max : '',
	};
	// eslint-disable-next-line no-undef
	flatpickr( date, timeOptions );
} );
