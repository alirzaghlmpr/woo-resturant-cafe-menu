jQuery(function ($) {
	var mediaFrame;

	$('.wrmp-upload-logo').on('click', function (event) {
		event.preventDefault();

		if (mediaFrame) {
			mediaFrame.open();
			return;
		}

		mediaFrame = wp.media({
			title: wrmpAdmin.mediaTitle,
			button: { text: wrmpAdmin.mediaButton },
			multiple: false
		});

		mediaFrame.on('select', function () {
			var attachment = mediaFrame.state().get('selection').first().toJSON();
			$('#wrmp-logo-id').val(attachment.id);
			$('.wrmp-logo-preview').html('<img src="' + attachment.url + '" alt="">');
		});

		mediaFrame.open();
	});

	$('.wrmp-remove-logo').on('click', function (event) {
		event.preventDefault();
		$('#wrmp-logo-id').val('');
		$('.wrmp-logo-preview').html('<span class="wrmp-empty-preview">' + wrmpAdmin.noLogo + '</span>');
	});

	$('.wrmp-select-all-categories').on('click', function (event) {
		event.preventDefault();
		$('.wrmp-category-checkbox').prop('checked', true);
	});

	$('.wrmp-clear-categories').on('click', function (event) {
		event.preventDefault();
		$('.wrmp-category-checkbox').prop('checked', false);
	});
});
