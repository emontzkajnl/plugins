/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

import { Carousel } from './carousel';

export function initCarousel() {
    window.cftpsObjects = [];

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-cftps-options]').forEach((element) => {
            const o = {};

            o.definitionElement = element;
            o.options = JSON.parse(element.dataset.cftpsOptions);

            o.cftpsInstance = new Carousel({
                selector: o.options['selector'],
                prevSelector: o.options['prevSelector'],
                nextSelector: o.options['nextSelector'],
                items: o.options['items'],
                margin: o.options['margin'],
                speed: o.options['speed']
            });

            window.cftpsObjects.push(o);
        });
    });
}
