(function ($) {
    "use strict";

    var onHoverMap = [];

    $(document).ready(function () {
        stretch_child();
        preloadPosts();
        wrapMenuElement();
        loadPostsOnCatHover();
    });

    $(window).load(function(){
        stretch_child();
    });

    $(window).resize(function(){
        stretch_child();
    });

    function loadPostsOnCatHover () {
        var timeout = null;

        $('.stm-mm-load-on-hover').each(function () {
            $(this).on('mouseover', function () {
                var catId = $(this).attr('data-cat-id');
                var hasChild = $(this).attr('data-has-child');
                var container = $('#' + $(this).parent().parent().attr('data-container'));
                var viewStyle = container.attr('data-view-style');

                if(typeof(onHoverMap[catId]) != 'undefined') {
                    $(container).empty();
                    $(container).html(onHoverMap[catId]);
                } else {
                    $.ajax({
                        url: mmAjaxUrl,
                        dataType: 'json',
                        context: this,
                        data: 'catId=' + catId + '&viewStyle=' + viewStyle + '&hasChild=' + hasChild + '&action=stm_mm_get_posts_by_cat',
                        beforeSend: function () {
                            $(container).addClass('loading');
                        },
                        complete: function (data) {

                            $(container).empty();
                            $(container).html(data.responseJSON);
                            var item = data.responseJSON;

                            onHoverMap[catId] = item;

                            if (timeout != null) clearTimeout(timeout);

                            timeout = setTimeout(function () {
                                $('.stm-mm-posts-container').removeClass('loading');
                            }, 100);
                        }
                    });
                }
            });
        } );

        $('.stm-mm-parent-load-on-hover').each(function () {
            $(this).on('mouseover', function () {
                var catId = $(this).attr('data-cat-id');
                var hasChild = $(this).attr('data-has-child');
                var container = $(this).parent().find('.stm-mm-posts-container');
                var viewStyle = container.attr('data-view-style');

                if(typeof(onHoverMap[catId]) != 'undefined') {
                    $(container).empty();
                    $(container).html(onHoverMap[catId]);
                } else {
                    $.ajax({
                        url: mmAjaxUrl,
                        dataType: 'json',
                        context: this,
                        data: 'catId=' + catId + '&viewStyle=' + viewStyle + '&hasChild=' + hasChild + '&action=stm_mm_get_posts_by_cat',
                        beforeSend: function () {
                            $(container).addClass('loading');
                        },
                        complete: function (data) {

                            $(container).empty();
                            $(container).html(data.responseJSON);
                            var item = data.responseJSON;

                            onHoverMap[catId] = item;

                            if (timeout != null) clearTimeout(timeout);

                            timeout = setTimeout(function () {
                                $('.stm-mm-posts-container').removeClass('loading');
                            }, 100);
                        }
                    } );
                }
            } );
        } );
    }

    function preloadPosts () {
        $('.stm-mm-parent-load-on-hover').each(function () {
            var catId = $(this).attr('data-cat-id');
            var hasChild = (typeof($(this).attr('data-has-child')) != 'undefined') ? $(this).attr('data-has-child') : 'no_child';
            var container = $(this).parent().find('.stm-mm-posts-container');
            var viewStyle = container.attr('data-view-style');

            $.ajax({
                url: mmAjaxUrl,
                dataType: 'json',
                context: this,
                data: 'catId=' + catId + '&viewStyle=' + viewStyle + '&hasChild=' + hasChild + '&action=stm_mm_get_posts_by_cat',
                beforeSend: function () {},
                complete: function (data) {
                    $(container).empty();
                    $(container).html(data.responseJSON);
                    var item = data.responseJSON;
                    onHoverMap[catId] = item;
                }
            } );
        } );
    }

    function wrapMenuElement () {
        $('.stm_menu_item_has_filter_posts').each( function () {

            var bg = $(this).find('a[data-megabg]').attr('data-megabg');

            var style = '';
            var hasBg = '';
            if( typeof(bg) != 'undefined' ) {
                style = 'style="background-image: url(' + bg + ');"';
                hasBg = 'stm-mm-has_bg'
            }

            if($(this).find('.sub-menu').length > 0) {
                $(this).find('.stm-mm-category-filter-wrap, .sub-menu').wrapAll('<div class="stm-mm-container ' + hasBg + '" ' + style + '></div>');
            } else {
                $(this).find('.stm-mm-category-filter-wrap').wrap('<div class="stm-mm-container ' + hasBg + '" ' + style + '></div>');
            }

        } );
    }

    function stretch_child() {
        // Wide
        var $wide = $('.stm_megamenu__wide > ul.sub-menu');
        var windowW = $(document).width();

        if ($wide.length) {
            var $containerWide = $wide.closest('.header_top .container, .top_nav .container');
            var containerWideW = $containerWide.width();

            // -15 due to global style left 15px
            var xPos = ((windowW - containerWideW) / 2 ) - 15;

            $wide.each(function () {

                $(this).css({
                    width: windowW + 'px',
                    'margin-left': '-' + xPos + 'px'
                })
            })
        }

        // Boxed
        var $boxed = $('.stm_megamenu__boxed > ul.sub-menu');
        if ($boxed.length) {
            var $container = $boxed.closest('.header_top .container, .top_nav .container');
            var containerW = $container.width();


            $boxed.each(function () {
                $(this).css({
                    width: containerW + 'px'
                })
            })
        }


        /*GET BG*/
        var $mega_menu = $('.stm_megamenu');
        $mega_menu.each(function(){
            var bg = $(this).find('a[data-megabg]').attr('data-megabg');
            if(typeof bg !== 'undefined') {
                $(this).find(' > ul.sub-menu').css({
                    'background-image' : 'url("' + bg + '")'
                })
            }
        })
    }


})(jQuery);