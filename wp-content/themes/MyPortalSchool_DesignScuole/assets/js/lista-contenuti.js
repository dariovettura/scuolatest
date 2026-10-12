(function ($) {
	'use strict';

	function loadMore($btn) {
		if ($btn.data('loading')) {
			return;
		}

		var $wrap = $btn.closest('.mps-lista-contenuti');
		var $list = $wrap.find('.mps-lista-contenuti__list');
		var page = parseInt($btn.attr('data-page'), 10) || 1;
		var nextPage = page + 1;

		$btn.data('loading', true).prop('disabled', true).addClass('is-loading');

		$.ajax({
			url: (window.mpsListaContenuti && mpsListaContenuti.ajaxUrl) || '',
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'mps_lista_contenuti_load_more',
				nonce: $btn.attr('data-nonce'),
				page: nextPage,
				numero: $btn.attr('data-numero'),
				layout: $btn.attr('data-layout'),
				col: $btn.attr('data-col'),
				tipo: $btn.attr('data-tipo'),
				argomento: $btn.attr('data-argomento'),
				post_type: $btn.attr('data-post-type'),
				categoria: $btn.attr('data-categoria')
			}
		})
			.done(function (response) {
				if (!response || !response.success || !response.data) {
					return;
				}

				if (response.data.html) {
					$list.append(response.data.html);
				}

				$btn.attr('data-page', nextPage);

				if (!response.data.has_more) {
					$btn.closest('.mps-lista-contenuti__actions').remove();
				}
			})
			.always(function () {
				$btn.data('loading', false).prop('disabled', false).removeClass('is-loading');
			});
	}

	$(document).on('click', '.mps-lista-contenuti__load-more', function (e) {
		e.preventDefault();
		loadMore($(this));
	});
})(jQuery);
