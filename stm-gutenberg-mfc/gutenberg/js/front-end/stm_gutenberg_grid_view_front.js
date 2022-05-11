(function($) {

    var gridMosaic;

    function setupIsotope() {
        if(typeof imagesLoaded != 'undefined') {
            $('.stmt-grid-mosaic').imagesLoaded(function () {
                gridMosaic = $('.stmt-grid-mosaic').isotope({
                    itemSelector: '.col',
                    layoutMode: 'packery',
                    packery: {
                        gutter: 0
                    }
                });
            });
        }
    }

    function afterAjax(html) {

        var $elem = $(html);
        var elems = [];


        $elem.each(function (i) {
            elems.push($elem[i]);
        });

        setupIsotope();
        setTimeout(function() {
            gridMosaic.isotope( 'insert', elems );
            setupIsotope();
        }, 10);

    }

    $(document).ready( function () {

        setupIsotope();

        $('.stmt-grid-load-more').on('click', function () {

            var loadBtn = $(this);
            var viewStyle = $(this).attr('data-vs');
            var columns = $(this).attr('data-columns');
            var offset = $(this).attr('data-offset');
            var args = $(this).attr('data-args');
            var perPage = $(this).attr('data-p-p');

            $.ajax({
                url: currentAjaxUrl,
                dataType: 'json',
                context: this,
                data: 'columns=' + columns + '&viewStyle=' + viewStyle + '&offset=' + offset + '&args=' + args + '&perPage=' + perPage + '&action=stmt_ajax_get_grid_news',
                beforeSend: function () {
                    loadBtn.addClass('loading');
                },
                complete: function (data) {
                    if(data.responseJSON.offset != 0) {
                        loadBtn.attr('data-offset', data.responseJSON.offset);
                    } else {
                        loadBtn.hide();
                    }

                    loadBtn.removeClass('loading');

                    if(!$(this).parent().parent().find('.stmt-grid-mosaic').length) {
                        $(this).closest('.stm-grid-view-block').find('.container').find('.row').append(data.responseJSON.html);
                    } else {
                        afterAjax(data.responseJSON.html);
                    }
                }
            });
        });

        $('.mosaic_grid_view_category_navigation li a').on('click', function (e) {
            e.preventDefault();
            var list = $(this).parent().parent();
            var current = $(this).parent();

            list.find('li').each(function () {
                $(this).removeClass('active');
            });

            current.addClass('active');

            var cols = $(this).attr('data-columns');
            var view = list.attr('data-view');
            var queryParams = $(this).attr('data-args');
            var catId = (typeof ($(this).attr('data-cat-id')) != 'undefined') ? $(this).attr('data-cat-id') : '';

            var offset = $(this).attr('data-offset');
            var perPage = $(this).attr('data-p-p');

            var loadBtn = $('.stmt-grid-load-more');

            $.ajax({
                url: currentAjaxUrl,
                dataType: 'json',
                context: this,
                data: 'cat_id=' + catId + '&args=' + queryParams + '&columns=' + cols + '&viewStyle=' + view + '&offset=' + offset + '&perPage=' + perPage + '&action=stmt_ajax_get_mosaic_posts_by_cat_id',
                beforeSend: function () {
                    loadBtn.attr('data-args', queryParams);
                    loadBtn.attr('data-offset', offset);
                    loadBtn.attr('data-p-p', perPage);
                },
                complete: function (data) {

                    if(data.responseJSON.offset != 0) {
                        loadBtn.attr('data-offset', data.responseJSON.offset);
                        loadBtn.show();
                    } else {
                        loadBtn.hide();
                    }

                    gridMosaic.empty();

                    afterAjax(data.responseJSON.html);
                }
            });

        });
    });

})(jQuery);