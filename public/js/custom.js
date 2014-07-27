$(function() {
	$('*[data-toggle="tooltip"]').tooltip();
	$('*[data-toggle="popover"]').popover({
		placement: 'top',
		trigger: 'hover'
	});

	$('.selectize').selectize({
		plugins: ['remove_button']
	});
});
