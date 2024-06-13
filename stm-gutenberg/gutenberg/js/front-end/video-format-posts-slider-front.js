(function ($) {
    $(document).ready(function () {

        if($('.play-btn').length) {
            $('.play-btn').click(function(e){
                e.preventDefault();

                var $container = $(this).closest('.stm-video-format-posts-slider-block');
                var $iframe = $container.find('iframe');
                $container.addClass('active');
                $iframe.attr('allow', 'autoplay');
                $iframe.attr('src', $iframe.attr('src') + '?autoplay=1');
            });
        }

        if($('.stmt-v-p-slider-wrap').length) {
            var owlVFPS = $('.stmt-v-p-slider-wrap');

            $('p:empty').remove();

            owlVFPS.owlCarousel({
                items: VFPSVisibleItem,
                loop: true,
                dots: false,
                nav: true,
                margin: 30,
                responsive: {
                    0: {
                        items: 1,
                    },
                    500: {
                        items: 2,
                    },
                    600: {
                        items: 3,
                    },
                    1000: {
                        items: VFPSVisibleItem,
                        loop: true
                    }
                }
            });

            $('.stmt-vps-nav .prev').click(function() {
                owlVFPS.trigger('prev.owl.carousel');
            });

            $('.stmt-vps-nav .next').click(function() {
                owlVFPS.trigger('next.owl.carousel');
            });
        }
    });
})(jQuery);