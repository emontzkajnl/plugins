/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

import { tns } from '../../../node_modules/tiny-slider/src/tiny-slider';

export class Carousel {
    defaults = {
        selector: '',
        prevSelector: '',
        nextSelector: '',
        items: 6,
        margin: 10,
        speed: 300,
    }

    options;
    carousel;
    prev;
    next;
    selectedSlideIndex = null;
    clickIsEnabled = false;

    constructor(options) {
        // Initialization function.
        this.options = Object.assign({}, this.defaults, options);
        this.carousel = document.querySelector(this.options.selector);
        this.prev = document.querySelector(this.options.prevSelector);
        this.next = document.querySelector(this.options.nextSelector);

        this.tinySlider = tns({
            container: this.carousel,
            items: this.options.items,
            slideBy: 1,
            lazyLoad: false,
            center: false,
            loop: true,
            speed: this.options.speed,
            controls: false,
            navPosition: 'bottom',
            gutter: this.options.margin,
        });

        this.tinySlider.events.on('indexChanged', (event) => {
            this.clickIsEnabled = false;
        });

        this.carousel.querySelectorAll('.item').forEach(item => {
            item.addEventListener('mousedown', (event) => {
                this.clickIsEnabled = true;
            });
            item.addEventListener('click', (event) => {
                if (this.clickIsEnabled) {
                    tpsInstance.setSlide(parseInt(item.dataset.slide));
                }
            });
        })

        // Hook events.
        this.prev.addEventListener('click', () => {
            this.tinySlider.goTo('prev');
            return false;
        })
        this.next.addEventListener('click', () => {
            this.tinySlider.goTo('next');
            return false;
        })

        // Change the carousel's selected item upon changing the slide.
        document.addEventListener('theiaPostSlider.willChangeSlide', (event) => {
            const slideIndex = event.detail.index;
            const item = this.carousel.querySelector('.item[data-slide="' + slideIndex + '"]:not(.tns-slide-cloned)');
            this.selectedSlideIndex = slideIndex;

            // Check if item is visible.
            const info = this.tinySlider.getInfo();
            const currentIndex = info.index - info.cloneCount;
            if (!(currentIndex <= slideIndex && slideIndex < currentIndex + this.options.items)) {
                this.tinySlider.goTo(slideIndex);
            }

            // Set classes.
            this.carousel.querySelectorAll('.item').forEach(item => {
                item.classList.remove('active');
            })
            this.carousel.querySelectorAll('.item[data-slide="' + slideIndex + '"]').forEach(item => {
                item.classList.add('active');
            })
        });
    }
}
