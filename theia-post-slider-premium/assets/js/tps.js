/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

import { addQueryString, isHtml5StorageAvailable } from './helper';
import * as async from './async';

export class TheiaPostSlider {
    defaults = {
        slideContainer: '',
        nav: '',
        navText: '%{currentSlide} of %{totalSlides}',
        helperText: '',
        defaultSlide: 0,
        transitionEffect: 'none',
        transitionSpeed: 400,
        keyboardShortcuts: false,
        scrollAfterRefresh: true,
        numberOfSlides: 0, // Total number of slides, including the ones not loaded.
        slides: [],
        useSlideSources: false,
        themeType: 'classic',
        prevPost: null,
        nextPost: null,
        prevText: null,
        nextText: null,
        buttonWidth: 0,
        prevText_post: null,
        nextText_post: null,
        buttonWidth_post: 0,
        onShowPost: null,
        postUrl: null,
        postId: null,
        refreshAds: false,
        refreshAdsEveryNSlides: 1,
        adRefreshingMechanism: 'javascript',
        ajaxUrl: '/',
        loopSlides: false,
        scrollTopOffset: 0,
        isRtl: false,
        excludedWords: []
    };

    options;
    slides = [];
    slideContainer;
    navEl = [];
    currentPostId;
    isLoading = false;
    slidesSinceLastAdRefresh = 0;
    remoteLoadingQueue;
    asyncQueue;
    currentSlide = null; // The current slide.
    currentlyDisplayedSlide = null; // The slide that is currently displayed. This may lag behind "this.currentSlide" because of the animations.
    animations = 0; // The number of animations that are currently running.
    semaphore = 0; // A queue that is executed when no animation is running.

    constructor(options) {
        // Merge options with defaults and sanitize values.
        this.options = Object.assign({}, this.defaults, options);
        Object.keys(this.options).forEach(key => {
            // If the default is a number and the option is a string, then convert it to a number if possible.
            if (!isNaN(this.defaults[key]) && (typeof this.options[key] == 'string')) {
                const value = parseFloat(value);
                if (!isNaN(value)) {
                    this.options[key] = value;
                }
            }
        });

        this.slideContainer = document.querySelector(this.options.slideContainer);
        if (this.slideContainer.length === 0) {
            return null;
        }

        document.querySelectorAll(this.options.nav).forEach(nav => {
            this.navEl.push({
                container: nav
            });
        })

        // Initialize variables.
        this.currentPostId = this.options.postId;

        // Remote loading queue.
        this.remoteLoadingQueue = async.queue(function (task, callback) {
            callback();
        }, 1);
        this.asyncQueue = async.queue(function (task, callback) {
            callback();
        }, 1);

        // Initialize the slider.
        this.init();
    }

    incrementSemaphore = () => {
        this.asyncQueue.pause();
        this.semaphore++;
    };

    decrementSemaphore = () => {
        this.semaphore--;
        if (this.semaphore === 0) {
            this.asyncQueue.resume();
        }
    };

    init = () => {
        // Load scroll position, if available.
        this.loadScrollTop();

        // Get slides from this.options.slides
        Object.keys(this.options.slides).forEach(i => {
            const slide = this.options.slides[i];
            if ('content' in slide) {
                if (this.options.useSlideSources) {
                    slide.source = slide.content;
                }

                const div = document.createElement('div');
                div.innerHTML = slide.content;
                slide.content = div;
            }

            this.slides[i] = slide;
        });
        this.options.slides = null;

        // Get slides from HTML. The first slide is considered the default one, while the rest are detached.
        this.slideContainer.querySelectorAll('.theiaPostSlider_preloadedSlide').forEach((slide, index) => {
            index = this.options.defaultSlide + index;
            this.slides[index] = this.slides[index] || {};
            this.slides[index].title = document.title;
            this.slides[index].permalink = document.location.href;
            this.slides[index].content = slide;

            if (index !== this.options.defaultSlide) {
                slide.parentElement.removeChild(slide);
            }
        });

        // Count the slides.
        this.numberOfSlides = this.options.numberOfSlides;

        // Setup the navigation bars.
        this.navEl.forEach((navEl, i) => {
            navEl.text = navEl.container.querySelector('._text');
            navEl.prev = navEl.container.querySelector('._prev');
            navEl.prev.addEventListener('click', (event) => {
                this.onNavClick('prev', event);
            });
            navEl.next = navEl.container.querySelector('._next');
            navEl.next.addEventListener('click', (event) => {
                this.onNavClick('next', event);
            });
            navEl.title = navEl.container.querySelector('._title');

            // Get the default slide's title. The title will be the same for all navigation bars, so get it only from the first.
            if (i === 0) {
                this.slides[this.options.defaultSlide].shortCodeTitle = navEl.title.innerHTML;
            }
        });

        // Set up navigation dropdowns.
        this.navigationDropdowns = document.querySelectorAll('.theiaPostSlider_dropdown select');
        this.navigationDropdowns.forEach(dropdown => {
            dropdown.addEventListener('change', (e) => {
                this.setSlide(parseInt(e.target.value) - 1);
            });
        });

        // Skip loading the slide if we're not going to display another one anyway.
        if (this.numberOfSlides === 1 || (this.options.refreshAds && this.options.adRefreshingMechanism === 'page' && this.options.refreshAdsEveryNSlides <= 1)) {
            this.currentSlide = this.options.defaultSlide;
        } else {
            // Show the first slide
            this.setSlide(this.options.defaultSlide);

            // Add history handler.
            if (window.history && window.history.pushState) {
                window.onpopstate = (e) => {
                    if (e.state && e.state.currentPostId !== undefined) {
                        if (e.state.currentSlide !== undefined) {
                            this.setSlide(e.state.currentSlide);
                        } else {
                            this.setSlide(this.options.defaultSlide);
                        }
                    }
                };
            }
        }

        // Set up touch events.
        if (typeof Hammer !== 'undefined') {
            this.previousTouch = 0;
            this.minimumTimeBetweenGestures = 500;

            // Eanble text selection.
            delete Hammer.defaults.cssProps.userSelect;

            // Create hammer.js instance.
            const hammertime = new Hammer(this.slideContainer[0], {
                inputClass: Hammer.TouchInput
            });

            hammertime
                .on('swipeleft', () => {
                    const t = (new Date).getTime();
                    if (t - this.minimumTimeBetweenGestures >= this.previousTouch) {
                        this.setNext();
                        this.previousTouch = t;
                    }
                })
                .on('swiperight', () => {
                    const t = (new Date).getTime();
                    if (t - this.minimumTimeBetweenGestures >= this.previousTouch) {
                        this.setPrev();
                        this.previousTouch = t;
                    }
                });
        }

        // Enable keyboard shortcuts
        if (this.options.keyboardShortcuts) {
            document.addEventListener('keydown', (e) => {
                // Disable shortcut if there is more than one slideshow on the page.
                if (window.tpsObjects.length > 1) {
                    return true;
                }

                // Disable shortcut if the target element is editable (input boxes, textareas, etc.).
                if (
                    /textarea|select/i.test(e.target.nodeName) ||
                    e.target.type === "text" ||
                    e.target.isContentEditable
                ) {
                    return true;
                }

                switch (e.which) {
                    case 37:
                        this.navEl[0].prev.click();
                        return false;

                    case 39:
                        this.navEl[0].next.click();
                        return false;
                }

                return true;
            });
        }
    };

    // Load slides remotely
    loadSlides = (slides, callback) => {
        this.remoteLoadingQueue.push({name: ''}, (err) => {
            // Check if the slides are already loaded.
            let allSlidesAreLoaded = true;
            for (let i in slides) {
                if (!(slides[i] in this.slides) || !('content' in this.slides[slides[i]])) {
                    allSlidesAreLoaded = false;
                    break;
                }
            }
            if (allSlidesAreLoaded) {
                if (callback) {
                    callback();
                }
                return;
            }

            // Load the slides and don't load anything else in the meantime.
            this.remoteLoadingQueue.concurrency = 0;
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = () => {
                if (xhr.readyState !== 4) {
                    return
                }

                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.response);
                    if (!data) {
                        return;
                    }
                    if (data.postId === this.currentPostId) {
                        // Add each slide.
                        for (const i in data.slides) {
                            if (!(i in this.slides)) {
                                this.slides[i] = {};
                            }

                            // Add content.
                            if ('content' in data.slides[i]) {
                                if (this.options.useSlideSources) {
                                    data.slides[i].source = data.slides[i].content;
                                }

                                const div = document.createElement('div');
                                div.innerHTML = data.slides[i].content;
                                data.slides[i].content = div;
                            }

                            // Overwrite old data with new data.
                            for (const j in data.slides[i]) {
                                this.slides[i][j] = data.slides[i][j];
                            }
                        }
                    } else {
                        console.error('Received AJAX results for a different post ID.');
                    }
                }

                if (callback) {
                    callback();
                }
                this.remoteLoadingQueue.concurrency = 1;
                this.remoteLoadingQueue.push({});
            };
            xhr.open('POST', this.options.ajaxUrl, true);
            const formData = new FormData();
            formData.append('action', 'tps_get_slides');
            formData.append('postId', this.currentPostId);
            slides.forEach(slide => {
                formData.append('slides[]', slide);
            });
            xhr.send(formData);
        });
    };

    // Set the current slide.
    setSlide = (index, isCallback) => {
        if (this.isLoading === true && isCallback !== true || this.currentSlide === index) {
            return;
        }

        // Do we need to refresh the ads by refreshing the entire page?
        if (this.needToRefreshPage() && index in this.slides) {
            this.saveScrollTop();
            window.location = addQueryString(this.slides[index].permalink);

            return;
        }

        // Fire events.
        try {
            document.dispatchEvent(new CustomEvent('theiaPostSlider.willChangeSlide', {detail: {index: index}}));
        } catch (e) {
            console.error(e);
        }

        // Is the slide not yet loaded?
        if (!this.isSlideLoaded(index)) {
            if (!isCallback) {
                this.showLoading();
                this.loadSlides([index], () => {
                    this.hideLoading();
                    this.setSlide(index, true);
                });

                return;
            } else {
                // The slide could not be loaded via AJAX. Abort.
                console.error("The slide could not be loaded via AJAX.");
                return;
            }
        }

        const slideContent = this.slides[index].source;
        if (slideContent) {
            for (let i = 0; i < this.options.excludedWords.length; i++) {
                let word = this.options.excludedWords[i];

                // Check if word is actually a string.
                if (typeof word !== 'string') {
                    continue;
                }

                // Trim string.
                word = word.trim();

                // Length must be at least one.
                if (word.length == 0) {
                    continue;
                }

                // Search for string and redirect if necessary.
                if (slideContent.indexOf(word) !== -1) {
                    this.showLoading();
                    window.location = addQueryString(this.slides[index].permalink);

                    return;
                }
            }
        }

        let previousSlide = this.currentSlide;
        this.currentSlide = index;

        if (previousSlide != null) {
            // Scroll the window up, if the beginning of the slide is out-of-view.
            // Get the lowest offset.top
            let scrollTop = this.slideContainer.offsetTop;
            for (let i = 0; i < this.navEl.length; i++) {
                scrollTop = Math.min(scrollTop, this.navEl[i].container.offsetTop);
            }
            for (let i = 0; i < this.navigationDropdowns.length; i++) {
                // Also consider navigation dropdowns.
                scrollTop = Math.min(scrollTop, this.navigationDropdowns[i].offsetTop);
            }
            scrollTop += this.options.scrollTopOffset;
            scrollTop = Math.max(0, scrollTop);
            if (window.pageYOffset > scrollTop) {
                if (window.scroll) {
                    window.scroll({top: scrollTop, behavior: 'smooth'});
                } else {
                    document.documentElement.scrollTop = scrollTop;
                }
            }

            // Remove number class.
            for (let i = 0; i < this.navEl.length; i++) {
                const navEl = this.navEl[i];
                navEl.container.classList.remove('_slide_number_' + previousSlide);
            }
        }

        // Set the title text.
        this.updateTitleText();

        // Set the document title.
        document.title = this.slides[index].title;

        // Change URL, but only if this isn't the first slide set (i.e. the default slide).
        if (window.history && window.history.pushState) {
            let url = this.slides[this.currentSlide].permalink;

            // Replace protocol and domain (e.g. using CloudFlare's HTTPS from an HTTP source).
            const a = document.createElement('a');
            a.href = url;
            url = addQueryString(window.location.protocol + '//' + window.location.host + a.pathname + a.search) + a.hash;

            if (url && previousSlide !== null) {
                const state = {
                    currentSlide: index,
                    currentPostId: this.currentPostId
                };
                const title = this.slides[this.currentSlide].title;

                if (url === window.location) {
                    window.history.replaceState(state, title, url);
                } else {
                    window.history.pushState(state, title, url);
                }
            }
        }

        // Show the slide.
        this.asyncQueue.push({name: ''}, this.showSlide);

        // Set the navigation bars.
        this.updateNavigationBars();

        // Preload, but only if we don't have to refresh the page immediately after.
        if (
            !(
                this.options.refreshAds &&
                this.slidesSinceLastAdRefresh + 1 >= this.options.refreshAdsEveryNSlides &&
                this.options.adRefreshingMechanism === 'page'
            )
        ) {
            // Direction is either +1 if the user is browsing forward (i.e. using the "next" button), or -1 otherwise.
            let direction;
            if (previousSlide == null) {
                direction = 1;
            } else if (this.currentSlide === 0 && previousSlide === this.numberOfSlides - 1) {
                direction = 1;
            } else if (this.currentSlide === this.numberOfSlides - 1 && previousSlide === 0) {
                direction = -1;
            } else {
                direction = this.currentSlide - previousSlide;
                direction = Math.max(Math.min(direction, 1), -1);
            }

            let slideToPreload = this.currentSlide + direction;

            // If the loop-slides settings is activated, also preload the first/last slide when applicable.
            if (slideToPreload === -1 && this.options.loopSlides) {
                slideToPreload = this.numberOfSlides - 1;
            } else if (slideToPreload === this.numberOfSlides && this.options.loopSlides) {
                slideToPreload = 0;
            }

            if (
                slideToPreload >= 0 &&
                slideToPreload < this.numberOfSlides && !this.isSlideLoaded((slideToPreload))
            ) {
                this.loadSlides([slideToPreload]);
            }

        }
    };

    // Show (display) the current slide using the chosen animation.
    showSlide = () => {
        // Don't do anything if the current slide is already shown.
        if (this.currentlyDisplayedSlide === this.currentSlide) {
            return;
        }

        // Track the pageview if this isn't the first slide displayed.
        if (this.currentlyDisplayedSlide != null && this.slides[this.currentSlide]['permalink']) {
            // URL Path
            let path = this.slides[this.currentSlide]['permalink'].split('/');
            if (path.length >= 4) {
                path = '/' + path.slice(3).join('/');

                // Global Site Tag (gtag.js)
                // See: https://developers.google.com/analytics/devguides/collection/gtagjs/migration
                if (typeof gtag !== 'undefined') {
                    // Search for UA tag.
                    let tag = '';
                    for (let i = 0; i < window.dataLayer.length; i++) {
                        if (window.dataLayer[i].length >= 1 && window.dataLayer[i][0] === 'config') {
                            tag = window.dataLayer[i][1];
                            break;
                        }
                    }
                    gtag('config', tag, {
                        'page_path': path
                    });
                }
                    // Google Analytics (ga.js, deprecated, but preferred if available)
                    // If using both old and new versions, the new version sometimes does not work.
                // See http://stackoverflow.com/questions/18696998/ga-or-gaq-push-for-google-analytics-event-tracking
                else if (typeof _gaq !== 'undefined' && typeof _gaq.push !== 'undefined') {
                    _gaq.push(['_trackPageview', path]);
                }
                // Google Analytics by Yoast, which renames the "ga" variable.
                else if (typeof __gaTracker !== 'undefined') {
                    __gaTracker('set', 'page', path);
                    __gaTracker('send', 'pageview', path);
                }
                // Google Analytics (Analytics.js).
                else if (typeof ga !== 'undefined') {
                    ga('set', 'page', path);
                    ga('send', 'pageview', path);
                }
                // Google Analytics Traditional.
                else if (typeof pageTracker !== 'undefined' && typeof pageTracker._trackPageview !== 'undefined') {
                    pageTracker._trackPageview(path);
                }

                // Piwik
                if (typeof piwikTracker !== 'undefined' && typeof piwikTracker.trackPageView !== 'undefined') {
                    piwikTracker.trackPageView(path);
                }

                // StatCounter
                if (typeof sc_project !== 'undefined' && typeof sc_security !== 'undefined') {
                    const img = new Image();
                    img.src = '//c.statcounter.com/' + sc_project + '/0/' + sc_security + '/1/';
                }

                // Quantcast
                if (typeof _qacct !== 'undefined') {
                    const img = new Image();
                    img.src = '//pixel.quantserve.com/pixel/' + _qacct + '.gif';
                }
            }
        }

        const previousIndex = this.currentlyDisplayedSlide;
        this.currentlyDisplayedSlide = this.currentSlide;

        this.createSlideContentFromSource(this.slides[this.currentSlide]);

        // Change dropdown navigations.
        this.navigationDropdowns.forEach(dropdown => {
            dropdown.value = this.currentSlide + 1;
        });

        // Change the slide while applying a certain effect/animation.
        const animationsQueue = window.tps.transitions[this.options.transitionEffect](this, previousIndex, this.currentlyDisplayedSlide);

        // Execute all "attachAnimation" methods before starting any animation.
        // Otherwise, we might get a race condition when one animation finishes before others are started,
        // thus triggering unwanted events before all animations have ended.
        for (let i = 0; i < animationsQueue.length; i++) {
            this.incrementSemaphore();
        }

        // This will be called after all animations finish, before any other items in the queue.
        if (previousIndex !== null) {
            this.asyncQueue.unshift({name: ''}, () => {
                this.onRemovedSlide(previousIndex);
            });
        }

        // Start all animations.
        for (let i = 0; i < animationsQueue.length; i++) {
            animationsQueue[i]();
        }
    };

    createSlideContentFromSource = (slide) => {
        if (!('content' in slide) && ('source' in slide)) {
            const div = document.createElement('div');
            div.innerHTML = slide.source;
            slide.content = div;

            if (false === this.options.useSlideSources) {
                delete slide.source;
            }
        }
    };

    isSlideLoaded = (index) => {
        if (!(index in this.slides)) {
            return false;
        }

        // Only if this isn't the first slide.
        if (this.currentlyDisplayedSlide !== null && this.options.useSlideSources && !('source' in this.slides[index])) {
            return false;
        }

        if (!(('content' in this.slides[index]) || ('source' in this.slides[index]))) {
            return false;
        }

        return true;
    };

    // Function that is called right after a slide has been removed from the DOM.
    onRemovedSlide = (slideId) => {
        if (this.options.useSlideSources) {
            this.slides[slideId].content.remove();
            delete this.slides[slideId].content;
        }

        // Refresh ads using JavaScript.
        if (this.options.refreshAds) {
            this.slidesSinceLastAdRefresh++;

            if (this.slidesSinceLastAdRefresh >= this.options.refreshAdsEveryNSlides) {
                this.slidesSinceLastAdRefresh = 0;

                if (this.options.adRefreshingMechanism == 'javascript') {
                    let p = null;

                    if (typeof pubService === 'undefined') {
                        if (typeof googletag !== 'undefined' && typeof googletag.pubads === 'function') {
                            p = googletag.pubads();
                        }
                    } else {
                        p = pubService;
                    }

                    if (p && typeof p.refresh === 'function') {
                        p.refresh();
                    }

                    try {
                        document.dispatchEvent(new CustomEvent('theiaPostSlider.refreshAds'));
                        if (window.jQuery) {
                            window.jQuery(document).trigger('theiaPostSlider.refreshAds');
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }
            }
        }
    };

    // Update the title text.
    updateTitleText = () => {
        let shortCodeTitle = this.slides[this.currentSlide].shortCodeTitle;
        if (!shortCodeTitle) {
            shortCodeTitle = '<span class="_helper">' + this.options['helperText'] + '</span>';
        }
        for (let i = 0; i < this.navEl.length; i++) {
            this.navEl[i].title.innerHTML = shortCodeTitle;
        }
    };

    // Update the navigation bar's text and buttons.
    updateNavigationBars = () => {
        for (let i = 0; i < this.navEl.length; i++) {
            let navEl = this.navEl[i];
            let navText;
            if (this.numberOfSlides == 1) {
                navText = '';
            } else {
                navText = this.options.navText;
                navText = navText.replace('%{currentSlide}', this.currentSlide + 1);
                navText = navText.replace('%{totalSlides}', this.numberOfSlides);
            }
            navEl.text.innerHTML = navText;

            // Set number class.
            navEl.container.classList.add('_slide_number_' + this.currentSlide);

            // Set first and last classes.
            navEl.container.classList.remove('_first_slide', '_last_slide', '_middle_slide');
            if (this.currentSlide === 0) {
                navEl.container.classList.add('_first_slide');
            } else if (this.currentSlide === this.numberOfSlides - 1) {
                navEl.container.classList.add('_last_slide');
            } else {
                navEl.container.classList.add('_middle_slide');
            }

            // Update buttons.
            this.updateNavigationBarButton(navEl, false);
            this.updateNavigationBarButton(navEl, true);
        }
    };

    // Update a button from a navigation bar.
    updateNavigationBarButton = (navEl, direction) => {
        let width,
            html1 = '',
            html2 = '',
            html3 = '',
            href,
            directionName = direction ? 'next' : 'prev',
            buttonEl = navEl[directionName];

        if (this.options.themeType == 'font') {
            width = 0;
            html2 = this.options[directionName + 'FontIcon'];

            if (direction == false) {
                if (this.options.prevText_post) {
                    if (this.currentSlide == 0) {
                        html3 = this.options.prevText_post;
                    } else {
                        html3 = this.options.prevText;
                    }
                } else {
                    html3 = this.options.prevText;
                }
            } else {
                if (this.options.nextText_post) {
                    if (this.currentSlide == this.numberOfSlides - 1) {
                        html1 = this.options.nextText_post;
                    } else {
                        html1 = this.options.nextText;
                    }
                } else {
                    html1 = this.options.nextText;
                }
            }
        } else {
            width = this.options.buttonWidth;

            if (
                (direction == false && this.options.prevPost && this.currentSlide == 0) ||
                (direction == true && this.options.nextPost && this.currentSlide == this.numberOfSlides - 1)
            ) {
                html2 = this.options[directionName + 'Text_post'];
                href = direction ? this.options.nextPost : this.options.prevPost;
            } else {
                html2 = this.options[directionName + 'Text'];
            }
        }

        // Set link.
        {
            let jumpToSlide = null;
            if (direction == false && this.options.loopSlides && this.currentSlide == 0) {
                jumpToSlide = this.numberOfSlides - 1;
            } else if (direction == true && this.options.loopSlides && this.currentSlide == this.numberOfSlides - 1) {
                jumpToSlide = 0;
            } else if (
                (direction == false && this.options.prevPost && this.currentSlide == 0) ||
                (direction == true && this.options.nextPost && this.currentSlide == this.numberOfSlides - 1)
            ) {
                buttonEl.classList.add('_another_post');
                href = direction ? this.options.nextPost : this.options.prevPost;
            } else {
                buttonEl.classList.remove('_another_post');
                jumpToSlide = this.currentSlide + (direction ? 1 : -1);
            }

            if (jumpToSlide !== null) {
                if (this.needToRefreshPage()) {
                    let
                        slide = this.slides[jumpToSlide];
                    if (slide) {
                        href = slide.permalink;
                    }
                } else {
                    href = '#';
                }
            }
        }

        buttonEl.querySelector('._1').innerHTML = html1;
        buttonEl.querySelectorAll('._2').forEach(e => {
            e.style.width = width > 0 ? width : '';
            e.innerHTML = html2;
        })
        buttonEl.querySelector('._3').innerHTML = html3;

        // Disable or enable
        if (
            (direction === false && this.options.prevPost == null && !this.options.loopSlides && this.currentSlide == 0) ||
            (direction === true && this.options.nextPost == null && !this.options.loopSlides && this.currentSlide == this.numberOfSlides - 1)
        ) {
            buttonEl.classList.add('_disabled');
            href = '#';
        } else {
            buttonEl.classList.remove('_disabled');
        }

        buttonEl.href = href;
    };

    // Handler for previous and next buttons.
    onNavClick = (direction, event) => {
        if (typeof event.currentTarget.href === 'undefined') {
            return;
        }

        // Jump to another page.
        // Some 3rd party plugins misbehave and turn the '#' into '', namely https://github.com/medialize/URI.js with "Blackout Image Gallery".
        const href = event.currentTarget.getAttribute('href');
        if (href !== '#' && href) {
            this.showLoading();
            this.saveScrollTop();
            return;
        }

        if (direction === 'prev') {
            this.setPrev();
        } else {
            this.setNext();
        }

        event.currentTarget.focus();
        if (event) {
            event.preventDefault();
        }
    };

    saveScrollTop = () => {
        if (!this.options.scrollAfterRefresh) {
            return;
        }

        if (!isHtml5StorageAvailable()) {
            return;
        }

        localStorage.setItem('scrollTop', JSON.stringify({
            postId: this.currentPostId,
            scrollTop: window.pageYOffset
        }));
    };

    loadScrollTop = () => {
        if (!this.options.scrollAfterRefresh || !isHtml5StorageAvailable()) {
            return;
        }

        const data = JSON.parse(localStorage.getItem('scrollTop'));
        if (data && data.postId === this.currentPostId) {
            const scrollTop = Math.min(data.scrollTop, this.slideContainer.offsetTop);
            const htmlStyle = document.documentElement.style;
            const previousScrollBehavior = htmlStyle.scrollBehavior;
            htmlStyle.scrollBehavior = 'inherit';
            window.scrollTo(0, scrollTop);
            htmlStyle.scrollBehavior = previousScrollBehavior;
        }

        localStorage.removeItem('scrollTop');
    };

    // Go to the previous slide.
    setPrev = () => {
        if (this.currentSlide === 0) {
            if (this.options.loopSlides) {
                this.setSlide(this.numberOfSlides - 1);
            } else if (this.options.prevPost) {
                this.showPost(this.options.prevPost);
            }
        } else {
            this.setSlide(this.currentSlide - 1);
        }
    };

    // Go to the next slide.
    setNext = () => {
        if (this.currentSlide === this.numberOfSlides - 1) {
            if (this.options.loopSlides) {
                this.setSlide(0);
            } else if (this.options.nextPost) {
                this.showPost(this.options.nextPost);
            }
        } else {
            this.setSlide(this.currentSlide + 1);
        }
    };

    // Go to another post. This method will be called when navigating using the keyboard shortcuts.
    showPost = function (postUrl) {
        document.location = postUrl;
    };

    // Set the transition properties (used in Live Preview).
    setTransition = (options) => {
        const defaults = {
            'effect': this.options.transitionEffect,
            'speed': this.options.transitionSpeed
        };
        options = Object.assign({}, defaults, options);
        this.options.transitionEffect = options.effect;
        this.options.transitionSpeed = options.speed;
    };

    // Set the navigation bar's text template (used in Live Preview).
    setNavText = (text) => {
        this.options.navText = text;
        this.updateNavigationBars();
    };

    // Set the title for all slides (used in Live Preview).
    setTitleText = (text) => {
        for (let i = 0; i < this.slides.length; i++) {
            this.slides[i].shortCodeTitle = '';
        }
        this.options.helperText = text;
        this.updateTitleText();
    };

    showLoading = () => {
        this.isLoading = true;
        for (let i = 0; i < this.navEl.length; i++) {
            const div = document.createElement('div');
            div.classList.add('_loading');
            const c = this.navEl[i].container;
            c.append(div)
            c.querySelectorAll('._buttons > a').forEach(a => a.classList.add('_disabled'));
        }
    };

    hideLoading = () => {
        this.isLoading = false;
        for (let i = 0; i < this.navEl.length; i++) {
            const loading = this.navEl[i].container.querySelector('._loading');
            loading.parentNode.removeChild(loading);
        }
        this.updateNavigationBars();
    };

    needToRefreshPage = () => {
        return (
            this.currentSlide !== null &&
            this.options.refreshAds &&
            this.slidesSinceLastAdRefresh + 1 >= this.options.refreshAdsEveryNSlides &&
            this.options.adRefreshingMechanism === 'page'
        );
    };
}
