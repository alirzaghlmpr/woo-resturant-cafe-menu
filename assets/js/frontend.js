jQuery(function ($) {
	function renderSkeletons(count) {
		var html = '<div class="wrmp-product-grid wrmp-product-grid-skeleton">';
		for (var index = 0; index < count; index++) {
			html += '<div class="wrmp-product-card wrmp-product-card-skeleton">' +
				'<div class="wrmp-skeleton wrmp-skeleton-image"></div>' +
				'<div class="wrmp-product-body">' +
				'<div class="wrmp-skeleton wrmp-skeleton-title"></div>' +
				'<div class="wrmp-skeleton wrmp-skeleton-price"></div>' +
				'<div class="wrmp-skeleton wrmp-skeleton-line"></div>' +
				'<div class="wrmp-skeleton wrmp-skeleton-line short"></div>' +
				'</div>' +
				'</div>';
		}
		html += '</div>';
		return html;
	}

	function renderTitleSkeleton() {
		return '<span class="wrmp-title-skeleton-wrap">' +
			'<span class="wrmp-skeleton wrmp-skeleton-heading"></span>' +
			'</span>';
	}

	function renderDescriptionSkeleton() {
		return '<span>' +
			'<span></span>' +
			'</span>';
	}

	function renderSubnavSkeleton($subnav, count) {
		var html = '';
		for (var index = 0; index < count; index++) {
			html += '<span class="wrmp-subchip wrmp-subchip-skeleton"><span class="wrmp-skeleton wrmp-skeleton-chip"></span></span>';
		}
		$subnav.addClass('has-children').html(html);
	}

	function renderSubnav($subnav, children) {
		children = children || [];

		if (!children.length) {
			$subnav.empty().removeClass('has-children');
			return;
		}

		var html = '';

		$.each(children, function (index) {
			html += '<button type="button" class="wrmp-subchip' + (0 === index ? ' is-active' : '') + '" data-term-id="' + children[index].term_id + '">' +
				'<span class="wrmp-subchip-text"></span>' +
				'</button>';
		});

		$subnav.addClass('has-children').html(html);

		$subnav.find('.wrmp-subchip').each(function (index) {
			$(this).find('.wrmp-subchip-text').text(children[index].name);
		});
	}

	function loadCategoryProducts($wrapper, termId) {
		var $target = $wrapper.find('[data-wrmp-products]');
		var $title = $wrapper.find('[data-wrmp-category-title]');
		var $description = $wrapper.find('[data-wrmp-category-description]');

		$title.html(renderTitleSkeleton());
		$description.html(renderDescriptionSkeleton());
		$target.addClass('is-loading').html(renderSkeletons(wrmpMenu.skeletonCount || 6));

		return $.post(wrmpMenu.ajaxUrl, {
			action: 'wrmp_get_category_products',
			nonce: wrmpMenu.nonce,
			term_id: termId
		})
			.done(function (response) {
				if (!response || !response.success) {
					$title.text('');
					$target.html('<p class="wrmp-menu-error">' + wrmpMenu.errorText + '</p>');
					return;
				}

				$title.text(response.data.title);
				$description.text(response.data.description || '');
				$target.html(response.data.html);
			})
			.fail(function () {
				$title.text('');
				$target.html('<p class="wrmp-menu-error">' + wrmpMenu.errorText + '</p>');
			})
			.always(function () {
				$target.removeClass('is-loading');
			});
	}

	$(document).on('click', '.wrmp-menu-tab', function () {
		var $button = $(this);
		var termId = $button.data('term-id');
		var $wrapper = $button.closest('.wrmp-menu-page');
		var $subnav = $wrapper.find('[data-wrmp-subnav]');
		var children = $button.data('children') || [];

		if (!termId) {
			return;
		}

		$wrapper.find('.wrmp-menu-tab').removeClass('is-active').attr('aria-selected', 'false');
		$button.addClass('is-active').attr('aria-selected', 'true');

		var targetTermId = children.length ? children[0].term_id : termId;

		if (children.length) {
			renderSubnavSkeleton($subnav, children.length);
		} else {
			$subnav.empty().removeClass('has-children');
		}

		loadCategoryProducts($wrapper, targetTermId).always(function () {
			renderSubnav($subnav, children);
		});
	});

	$(document).on('click', '.wrmp-subchip', function () {
		var $chip = $(this);
		var termId = $chip.data('term-id');
		var $wrapper = $chip.closest('.wrmp-menu-page');

		if (!termId || $chip.hasClass('is-active')) {
			return;
		}

		$chip.closest('[data-wrmp-subnav]').find('.wrmp-subchip').removeClass('is-active');
		$chip.addClass('is-active');

		loadCategoryProducts($wrapper, termId);
	});
});
