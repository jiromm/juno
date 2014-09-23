$(function() {
	$('*[data-toggle="tooltip"]').tooltip();
	$('*[data-toggle="popover"]').popover({
		placement: 'top',
		trigger: 'hover'
	});

	$('.selectize').selectize({
		plugins: ['remove_button']
	});

	$('.selectize-create').selectize({
		create: true
	});

	var dt = $('.dataTables'),
		lastIndex = dt.find('th').length - 1,
		dto = dt.dataTable({
		"aoColumnDefs": [{
			"bSortable": false, "aTargets": [lastIndex]
		}]
	});
});
