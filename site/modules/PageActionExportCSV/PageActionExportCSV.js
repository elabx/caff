function PageActionExportCSV() {
	
	var $fieldset = $('#PageActionExportCSV-settings');

	// load a preset
	$fieldset.on('click', 'a.load-csv-preset', function() {

		var $a = $(this);
		var $icon = $fieldset.children('.InputfieldHeader').find('.fa-file-excel-o');
		
		InputfieldClose($('#csv_presets'));
		InputfieldClose($fieldset);

		var submitData = {
			render_actions: 'PageActionExportCSV',
			PageActionExportCSV: 'load_preset',
			load_preset: $a.attr('data-preset')
		};
		
		$icon.removeClass('fa-file-excel-o').addClass('fa-spin fa-spinner');

		$.ajax({
			url: './',
			type: 'POST',
			data: submitData,
			success: function(data) {
				var $newFieldset = $(data);
				var $inputfields = $fieldset.find('.export-csv-settings');
				$inputfields.each(function() {
					var $f = $(this);
					var newHTML = $newFieldset.find('#' + $f.attr('id')).children('.InputfieldContent').html();
					$f.children('.InputfieldContent').html(newHTML);
				});
				$inputfields.trigger('reloaded');
				setTimeout(function() {
					InputfieldOpen($fieldset);
					$icon.removeClass('fa-spin fa-spinner').addClass('fa-file-excel-o');
				}, 200);
			},
			error: function(error) {
				alert("ajax error");
			}
		});

		return true;
	}); 

	// add a new preset
	$fieldset.on('click', '#submit_csv_new_preset', function() {

		var $submitButton = $(this);
		var $presetsFieldset = $('#csv_presets');
		var $presetsList = $('#csv_presets_list'); 
		var presetTitle = $('#csv_new_preset').val();
		var $icon = $presetsFieldset.children('.InputfieldHeader').find('.fa-bookmark-o');
		
		if(!presetTitle.length) return false;
		
		InputfieldClose($presetsFieldset); 
		InputfieldClose($submitButton.closest('.Inputfield'));
		$icon.removeClass('fa-bookmark-o').addClass('fa-spin fa-spinner');
		
		var submitData = {
			render_actions: 'PageActionExportCSV',
			PageActionExportCSV: 'add_preset',
			add_preset: presetTitle,
			exportFields: $('#csv_exportFields').val(), 
			exportHeadings: $('#wrap_csv_exportHeadings').find('input:checked').val(),
			multiSeparator: $('#wrap_csv_multiSeparator').find('input:checked').val(),
			delimiter: $('#wrap_csv_delimiter').find('input:checked').val(),
			enclosure: $('#wrap_csv_enclosure').find('input:checked').val(),
			extras: []
		};
		
		$('#wrap_csv_extras').find('input:checked').each(function() {
			submitData.extras.push($(this).val());
		}); 
	
		console.log(submitData);
		
		$.ajax({
			url: './',
			type: 'POST',
			data: submitData,
			success: function(data) {
				var $newList = $(data).find('#csv_presets_list');
				$presetsList.children('.InputfieldContent').html($newList.children('.InputfieldContent').html());
				//$presetsList.trigger('reloaded');
				InputfieldOpen($presetsFieldset);
				$icon.removeClass('fa-spin fa-spinner').addClass('fa-bookmark-o');
			},
			error: function(error) {
				alert("ajax error");
			}
		});

	}); 

	$fieldset.on('click', '#submit_csv_presets_delete', function() {

		var $presetsFieldset = $('#csv_presets');
		var $presetsList = $('#csv_presets_list'); 
		var $deletePresets = $('input.csv-presets-delete:checked'); 
		var names = [];
		var $icon = $presetsFieldset.children('.InputfieldHeader').find('.fa-bookmark-o');
		
		if(!$deletePresets.length) return false;
		
		$deletePresets.each(function() {
			names.push($(this).val()); 
		});
		
		$icon.removeClass('fa-bookmark-o').addClass('fa-spin fa-spinner');
		
		var submitData = {
			render_actions: 'PageActionExportCSV',
			PageActionExportCSV: 'delete_presets',
			delete_presets: names
		};
		
		console.log(submitData);
		
		$.ajax({
			url: './',
			type: 'POST',
			data: submitData,
			success: function(data) {
				var $newList = $(data).find('#csv_presets_list');
				$presetsList.children('.InputfieldContent').html($newList.children('.InputfieldContent').html());
				$icon.removeClass('fa-spin fa-spinner').addClass('fa-bookmark-o');
			},
			error: function(error) {
				alert("ajax error");
			}
		});
		
		return false;
	});
	
}


$(document).ready(function() {
	PageActionExportCSV();
});