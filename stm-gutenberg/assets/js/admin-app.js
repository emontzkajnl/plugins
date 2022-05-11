(function ($) {
    "use strict";
    $(document).ready( function () {

        if(typeof imagesLoaded != 'undefined') {
            $('.stm_gutenberg_masonry').imagesLoaded(function () {
                $('.stm_gutenberg_masonry').isotope({
                    itemSelector: '.stm-msnr',
                    layoutMode: 'packery',
                    packery: {
                        gutter: 10
                    }
                });
            });
        }

        $('blockquote p').addClass('normal-font');

    });
})(jQuery);