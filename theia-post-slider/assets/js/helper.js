export function addQueryString(url) {
    if (!url) {
        return url;
    }

    // Plain permalinks?
    if (document.location.search.indexOf('?p=') !== -1) {
        return url;
    }

    const query = document.location.search.substr(1);
    if (!query) {
        return url;
    }

    if (url.indexOf('?') === -1) {
        url += '?' + query;
    } else {
        url += '&' + query;
    }

    return url;
}

export function isHtml5StorageAvailable() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}


// Function that is called right after a new slide has been added to the DOM, and right before the animation (if available) has started.
export function onNewSlide(currentSlide) {
    // WordPress [audio] and [video] shortcodes.
    if (typeof _wpmejsSettings !== 'undefined') {
        window.jQuery('.wp-audio-shortcode, .wp-video-shortcode').mediaelementplayer(_wpmejsSettings);
    }

    // Instagram embeds.
    if (typeof instgrm !== 'undefined' && typeof instgrm.Embeds !== 'undefined' && typeof instgrm.Embeds.process !== 'undefined') {
        instgrm.Embeds.process();
    }

    // Fire events.
    try {
        document.dispatchEvent(new CustomEvent('theiaPostSlider.changeSlide', {detail: {currentSlide}}));
        if (window.jQuery) {
            window.jQuery(document).trigger('theiaPostSlider.changeSlide', [currentSlide]);
        }
    } catch (e) {
        console.error(e);
    }
}

export function getInnerWidth(e) {
    return window.getComputedStyle(e).getPropertyValue('width');
}

export function getInnerHeight(e) {
    return window.getComputedStyle(e).getPropertyValue('height');
}

export function forceRender(e) {
    getInnerWidth(e);
}

export function getTimeout(speed) {
    return speed * 1.1; // Increase timeout by 10% to account for FPS drops/delays.
}
