import { TheiaPostSlider } from './tps';

export function init() {
    window.tpsObjects = [];

    // Deprecated. Use tpsObjects instead.
    window.tpsInstance = undefined;

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-theia-post-slider-options]').forEach((e) => {
            if (e.dataset.theiaPostSliderLoaded) {
                return;
            }
            e.dataset.theiaPostSliderLoaded = 'true';

            const o = {};

            o.definitionElement = e;
            o.sliderOptions = e.dataset.theiaPostSliderOptions ? JSON.parse(e.dataset.theiaPostSliderOptions) : '';
            o.onChangeSlide = e.dataset.theiaPostSliderOnChangeSlide ? JSON.parse(e.dataset.theiaPostSliderOnChangeSlide) : '';

            document.addEventListener('theiaPostSlider.changeSlide', (event) => {
                eval(o.onChangeSlide);
            });

            o.tpsInstance = new TheiaPostSlider(o.sliderOptions);

            if (window.tpsObjects.length === 0) {
                window.tpsInstance = o.tpsInstance;
            }

            window.tpsObjects.push(o);
        });
    });
}
