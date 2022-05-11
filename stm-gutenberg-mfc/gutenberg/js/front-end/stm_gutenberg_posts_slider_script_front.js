(function($) {
    $(document).ready( function () {

        $('p:empty').remove();

        var
            owl = $('.stmt-ps-content');
            owl_beauty = $('.stmt-ps-content_beauty');

        owl_beauty.owlCarousel({
            items:4,
            loop:true,
            margin:5,
            merge:true,
            responsive:{
                0:{
                    items:1,
                },
                1024:{
                    items:1,
                },
                1200:{
                    items:4,
                    loop:true
                }
            }
        });

        owl.owlCarousel({
            items: 1,
            loop: true,
            dots: false,
            nav: false,
            margin:0,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            singleItem : true,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            dotsContainer: '#stmt-ps-dots',
            responsiveClass:true,
            responsiveRefreshRate: 1000,
            responsive:{
                0:{
                    items:1,
                },
                600:{
                    items:1,
                },
                1000:{
                    items:1,
                    loop:true
                }
            }
        });

        owl.on('changed.owl.carousel', function(event) {
            var currentSlide = $(event.target).find(".owl-item").eq(event.item.index).find("div:first-child").attr('data-slide');
            $('.stmt-ps-nav-item .stmt-ps-nav-item-meta').each(function () {
                $(this).removeClass('active');
            });

            $('#' + currentSlide + ' .stmt-ps-nav-item-meta').addClass('active');
        });

        $('.stmt-ps-controlls .prev').click(function() {
            owl.trigger('prev.owl.carousel');
        });

        $('.stmt-ps-controlls .next').click(function() {
            owl.trigger('next.owl.carousel');
        });

        $('.owl-dot').click(function () {
            owl.trigger('to.owl.carousel', [$(this).index(), 300]);
        });
    });

})(jQuery);