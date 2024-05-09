/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

import { onNewSlide } from './helper';

window.tps = window.tps || {};
window.tps.transitions = window.tps.transitions || {};
window.tps.transitions.none = function (me, previousIndex, index) {
    // Remove previous slide.
    const previousSlide = previousIndex !== null ? me.slides[previousIndex].content : null;
    if (previousSlide) {
        previousSlide.parentNode.removeChild(previousSlide);
    }

    // Set the current slide.
    const slide = me.slides[index].content;
    me.slideContainer.append(slide);

    if (previousSlide) {
        // Call event handlers.
        onNewSlide(me.currentSlide);
    }

    return [];
};
