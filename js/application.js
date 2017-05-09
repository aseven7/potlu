/* application.js */
$(function() {
	var $form = $('form.form');

	for(var field in beans) {
		var $field = $form.find('[name="' + field + '"]');
		var value = beans[field];

		$field.val(value);
	}

	/* Inline edit features */
    $('.inline-edit').each(function() {
    	$(this).after('<div class="container-inline-edit"></div>');
    })

	/* Inline edit action features */
    $('.inline-edit').on('click', function(e) {
    	e.preventDefault();
    	
    	$('.inline-edit').each(function() {
    		$(this).next('.container-inline-edit').eq(0).html("");
    		var previousValue = $(this).attr('data-previous-value');
    		$(this).html(previousValue);
    	});

    	var htmlValue = $(this).html();

    	$(this).attr('data-previous-value', htmlValue);
    	$(this).html("");
    	
    	var key = $(this).attr('data-key');
    	var value = $(this).attr('data-value');
    	var column = $(this).attr('data-column');
    	var $container = $(this).next('.container-inline-edit').eq(0);
    	var inlineEditForm = $('#inlineEditForm').html();
    	var compiledTemplate = _.template(inlineEditForm);
    	var rendered = compiledTemplate({ VALUE : value, KEY : key, COLUMN: column });

    	$container.html(rendered);
    	$container.find('input[type="text"]').focus().select();
    	$container.find('input[type="text"]').on('keyup', function(e) {
	    	if(e.which == 13) {
	    		$(this).parents('.input-group').eq(0).find('button[type="submit"]').click();
	    	}
	    });

        $('#inlineFormCloseButton').on('click', function() {
            var $inlineEditor = $container.find('input[type="text"]');
    		var $inlineEditContainer = $inlineEditor.parents('.container-inline-edit').eq(0).prev('a.inline-edit');
    		$inlineEditContainer.next('.container-inline-edit').eq(0).html("");
    		var previousValue = $inlineEditContainer.attr('data-previous-value');
    		$inlineEditContainer.html(previousValue);
	    });
    });
})