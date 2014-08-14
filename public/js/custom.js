$(function() {
	$('*[data-toggle="tooltip"]').tooltip();
	$('*[data-toggle="popover"]').popover({
		placement: 'top',
		trigger: 'hover'
	});

	$('.selectize').selectize({
		plugins: ['remove_button']
	});

	var dt = $('.dataTables'),
		lastIndex = dt.find('th').length - 1,
		dto = dt.dataTable({
		"aoColumnDefs": [{
			"bSortable": false, "aTargets": [lastIndex]
		}]
	});
});
