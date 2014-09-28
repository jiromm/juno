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

	// Product
	$('#product_type_id').on('change', function(e) {
		e.preventDefault();

		$('.product-types').addClass('hidden');
		$('.product-type-' + $(this).val()).removeClass('hidden');
	}).trigger('change');

	$('#product input[name=submit]').on('click', function(e) {
		$('.product-types.hidden input').prop('disabled', true);
	});
});
