(function($) {

    var masonryWrap;

    function setupIsotope() {
        if(typeof imagesLoaded != 'undefined') {
            $('.stm_gutenberg_masonry').imagesLoaded(function () {
                masonryWrap = $('.stm_gutenberg_masonry').isotope({
                    itemSelector: '.stm-msnr',
                    layoutMode: 'packery',
                    packery: {
                        gutter: 10
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
            masonryWrap.empty();
            masonryWrap.isotope( 'insert', elems );
            setupIsotope();
            masonryWrap.removeClass('preload');
        }, 10);

    }

    $(document).ready( function () {

        setupIsotope();

        $('.masonry_view_category_navigation li a').on('click', function (e) {
            e.preventDefault();

            var list = $(this).parent().parent();
            var current = $(this).parent();

            list.find('li').each(function () {
                $(this).removeClass('active');
            });

            current.addClass('active');

            var view = list.attr('data-view');
            var queryParams = $(this).attr('data-args');
            var catId = (typeof ($(this).attr('data-cat-id')) != 'undefined') ? $(this).attr('data-cat-id') : '';

            var offset = $(this).attr('data-offset');
            var perPage = $(this).attr('data-p-p');

            $.ajax({
                url: currentAjaxUrl,
                dataType: 'json',
                context: this,
                data: 'cat_id=' + catId + '&args=' + queryParams + '&viewStyle=' + view + '&offset=' + offset + '&perPage=' + perPage + '&action=stmt_ajax_get_masonry_posts_by_cat_id',
                beforeSend: function () {
                    masonryWrap.addClass('preload');
                },
                complete: function (data) {

                    afterAjax(data.responseJSON.html);
                }
            });

        });
    });

})(jQuery);