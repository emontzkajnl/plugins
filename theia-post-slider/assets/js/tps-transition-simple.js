/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

import { onNewSlide, getInnerHeight, getTimeout } from './helper';

window.tps = window.tps || {};
window.tps.transitions = window.tps.transitions || {};
window.tps.transitions.simple = function (me, previousIndex, index) {
    // Start all animations at once, at the end of this function. Otherwise we can get rare race conditions.
    const animationsQueue = [];

    // Remove previous slide
    const previousSlide = previousIndex !== null ? me.slides[previousIndex].content : null;
    if (previousSlide) {
        me.slideContainer.style.height = getInnerHeight(previousSlide);
        previousSlide.parentNode.removeChild(previousSlide);
    }

    // Set the current slide
    const slide = me.slides[index].content;

    if (previousSlide == null) {
        // Don't animate the first shown slide
        me.slideContainer.append(slide);
    } else {
        me.slideContainer.append(slide);

        // Call event handlers
        onNewSlide(me.currentSlide);

        // Animate the height
        animationsQueue.push(function () {
            me.slideContainer.style.transition = 'height ' + (me.options.transitionSpeed / 1000) + 's ease-in';
            me.slideContainer.style.height = getInnerHeight(slide);
            setTimeout(() => {
                me.slideContainer.style.position = '';
                me.slideContainer.style.width = '';
                me.slideContainer.style.height = '';
                me.decrementSemaphore();
            }, getTimeout(me.options.transitionSpeed));
        });
    }

    return animationsQueue;
};
