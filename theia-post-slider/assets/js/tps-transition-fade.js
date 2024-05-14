/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

import { onNewSlide, getInnerHeight, forceRender, getTimeout } from './helper';

window.tps = window.tps || {};
window.tps.transitions = window.tps.transitions || {};
window.tps.transitions.fade = function (me, previousIndex, index) {
    // Start all animations at once, at the end of this function. Otherwise we can get rare race conditions.
    const animationsQueue = [];

    // Remove previous slide
    const previousSlide = previousIndex !== null ? me.slides[previousIndex].content : null;
    if (previousSlide) {
        me.slideContainer.style.height = getInnerHeight(previousSlide);
        animationsQueue.push(function () {
            previousSlide.style.width = '100%';
            previousSlide.style.position = 'absolute';
            previousSlide.style.transition = 'opacity ' + (me.options.transitionSpeed / 1000) + 's ease-in';
            previousSlide.style.opacity = 0;
            setTimeout(() => {
                previousSlide.parentNode.removeChild(previousSlide);
                previousSlide.style.position = '';
                previousSlide.style.opacity = 1;
                me.decrementSemaphore();
            }, getTimeout(me.options.transitionSpeed));
        });
    }

    // Set the current slide.
    const slide = me.slides[index].content;

    if (previousSlide == null) {
        // Don't animate the first shown slide.
        me.slideContainer.append(slide);
    } else {
        //slide.css('width', width);
        me.slideContainer.append(slide);

        // Call event handlers.
        onNewSlide(me.currentSlide);

        // Animate the height.
        animationsQueue.push(function () {
            me.slideContainer.style.transition = 'height ' + (me.options.transitionSpeed / 1000) + 's ease-in';
            me.slideContainer.style.height = getInnerHeight(slide);
            setTimeout(() => {
                slide.style.position = '';
                me.decrementSemaphore();
            }, getTimeout(me.options.transitionSpeed));
        });

        // Animate the new slide.
        animationsQueue.push(function () {
            slide.style.transition = '';
            slide.style.opacity = 0;
            forceRender(slide);
            slide.style.transition = 'opacity ' + (me.options.transitionSpeed / 1000) + 's ease-in';
            slide.style.opacity = 1;
            setTimeout(() => {
                slide.style.position = '';
                me.slideContainer.style.height = '';
                me.decrementSemaphore();
            }, getTimeout(me.options.transitionSpeed));
        });
    }

    return animationsQueue;
};
