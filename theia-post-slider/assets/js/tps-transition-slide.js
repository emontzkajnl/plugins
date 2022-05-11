/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

import { onNewSlide, getInnerWidth, getInnerHeight, forceRender, getTimeout } from './helper';

window.tps = window.tps || {};
window.tps.transitions = window.tps.transitions || {};
window.tps.transitions.slide = function (me, previousIndex, newIndex) {
    // Check for RTL layout.
    const isRtl = me.options.isRtl;
    const transitionDirection = isRtl ? 'right' : 'left';

    // Init
    const width = getInnerWidth(me.slideContainer);
    const diff = newIndex - previousIndex;
    const direction = diff > 0 ? 1 : -1;

    // Start all animations at once, at the end of this function. Otherwise we can get rare race conditions.
    const animationsQueue = [];

    // Remove previous slide.
    const previousSlide = previousIndex !== null ? me.slides[previousIndex].content : null;
    if (previousSlide) {
        me.slideContainer.style.height = getInnerHeight(previousSlide);
        animationsQueue.push(function () {
            previousSlide.style.width = width;
            previousSlide.style.position = 'absolute';
            previousSlide.style[transitionDirection] = 0;
            slide.style.transition = '';
            forceRender(slide);
            previousSlide.style.transition = transitionDirection + ' ' + (me.options.transitionSpeed / 1000) + 's ease-in';
            previousSlide.style[transitionDirection] = -direction * parseFloat(width) + 'px';
            setTimeout(() => {
                previousSlide.parentNode.removeChild(previousSlide);
                previousSlide.style.position = '';
                previousSlide.style[transitionDirection] = '';
                me.decrementSemaphore();
            }, getTimeout(me.options.transitionSpeed));
        });
    }

    const slide = me.slides[newIndex].content;

    if (previousSlide == null) {
        // Don't animate the first shown slide.
        me.slideContainer.append(slide);
    } else {
        slide.style.width = width;
        slide.style.visibility = 'hidden';
        me.slideContainer.append(slide);

        // Call event handlers.
        onNewSlide(me.currentSlide);

        // Animate the height.
        animationsQueue.push(function () {
            me.slideContainer.style.transition = 'height ' + (me.options.transitionSpeed / 1000) + 's ease-in';
            me.slideContainer.style.height = getInnerHeight(slide);
            setTimeout(() => {
                me.slideContainer.style.position = '';
                me.decrementSemaphore();
            }, getTimeout(me.options.transitionSpeed));
        });

        // Animate the new slide.
        animationsQueue.push(function () {
            slide.style.position = 'absolute';
            slide.style.visibility = 'visible';
            slide.style[transitionDirection] = direction * parseFloat(width) + 'px';
            slide.style.transition = '';
            forceRender(slide);
            slide.style.transition = transitionDirection + ' ' + (me.options.transitionSpeed / 1000) + 's ease-in';
            slide.style[transitionDirection] = 0;
            setTimeout(() => {
                slide.style.position = '';
                slide.style.width = '';
                me.slideContainer.style.height = '';
                me.decrementSemaphore();
            }, getTimeout(me.options.transitionSpeed));
        });
    }

    return animationsQueue;
};
