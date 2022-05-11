<?php

namespace WeCodePixels\TheiaPostSlider\Admin;

use WeCodePixels\TheiaPostSlider\Options;

class VectorIconsPicker
{
    public static function render($optionGroup, array $options)
    {
        $icons = Options::get_vector_icons();

        ?>
        <div class="tps-vector-icons-picker">
            <select class="_icons-picker"
                    name="<?php echo $optionGroup ?>[theme_font_name]">
                <?php
                foreach ($icons as $name => $icon) {
                    $displayName = ucwords(str_replace('-', ' ', $name));

                    switch ($icon['type']) {
                        case 'font':
                            $leftIcon = Options::get_span_for_font_icon($icon['leftClass']);
                            $rightIcon = Options::get_span_for_font_icon($icon['rightClass']);
                            break;

                        case 'svg':
                        default:
                            $leftIcon = Options::get_svg_for_icon($name, 'left');
                            $rightIcon = Options::get_svg_for_icon($name, 'right');
                            break;
                    }

                    if ($name === 'None') {
                        $leftIcon = '';
                        $rightIcon = '';
                    }

                    ?>
                    <option data-type="svg" <?php echo $name == $options['theme_font_name'] ? ' selected' : '' ?>
                            value="<?php echo $name ?>"
                            data-left-icon="<?php echo htmlspecialchars($leftIcon) ?>"
                            data-right-icon="<?php echo htmlspecialchars($rightIcon) ?>">
                        <?php echo $displayName ?>
                    </option>
                    <?php
                }
                ?>
            </select>
            <input type="hidden"
                   id="tps_theme_vector_left_icon"
                   name="<?php echo $optionGroup ?>[theme_vector_left_icon]"
                   value="<?php echo htmlspecialchars($options['theme_vector_left_icon']); ?>">
            <input type="hidden"
                   id="tps_theme_vector_right_icon"
                   name="<?php echo $optionGroup ?>[theme_vector_right_icon]"
                   value="<?php echo htmlspecialchars($options['theme_vector_right_icon']); ?>">
            <p>
                <label>
                    <input type="checkbox" name="<?= $optionGroup ?>[theme_vector_custom]" id="theme_vector_custom"
                        <?= $options['theme_vector_custom'] ? 'checked' : '' ?>>
                    Use custom SVG vector files
                </label>
            </p>
            <div class="custom-icons-wrapper" style="display: <?= $options['theme_vector_custom'] ? '' : 'none' ?>">
                <p class="description">
                    You can find high quality vector icons on <a href="https://www.flaticon.com/search?word=arrows" target="_blank">Flaticon</a>.
                </p>
                <p class="description">
                    We recommend optimizing your files with a tool like
                    <a href="https://jakearchibald.github.io/svgomg/" target="_blank">SVGOMG</a>.
                </p>
                <p class="custom-icon">
                    <button class="button">Pick left icon</button>
                    <input type="hidden" name="<?= $optionGroup ?>[theme_vector_custom_left_icon]"
                           value="<?= esc_attr( $options['theme_vector_custom_left_icon'] ) ?>">
                    <input type="file" accept=".svg">
                    <img src="<?= self::getImageData( $options['theme_vector_custom_left_icon'] ) ?>" alt="">
                    <a class="remove" href="#"><span class="dashicons dashicons-trash"></span></a>
                </p>
                <p class="custom-icon">
                    <button class="button">Pick right icon</button>
                    <input type="hidden" name="<?= $optionGroup ?>[theme_vector_custom_right_icon]"
                           value="<?= esc_attr( $options['theme_vector_custom_right_icon'] ) ?>">
                    <input type="file" accept=".svg">
                    <img src="<?= self::getImageData( $options['theme_vector_custom_right_icon'] ) ?>" alt="">
                    <a class="remove" href="#"><span class="dashicons dashicons-trash"></span></a>
                </p>
            </div>
        </div>

        <script type="text/javascript">
            var slimVectorIconsPicker;
            var vectorIconsData = {};

            jQuery(document).ready(function ($) {
                var picker = $('.tps-vector-icons-picker ._icons-picker');
                var data = [];
                picker.find('option').each(function () {
                    vectorIconsData[$(this).attr('value')] = {
                        leftIcon: $(this).attr('data-left-icon'),
                        rightIcon: $(this).attr('data-right-icon')
                    };

                    data.push({
                        innerHTML: $(this).attr('data-left-icon') + $(this).attr('data-right-icon') + $(this).html(),
                        text: $(this).html(),
                        value: $(this).attr('value'),
                        selected: $(this).attr('selected')
                    });
                });

                slimVectorIconsPicker = new SlimSelect({
                    select: picker[0],
                    valuesUseText: false,
                    data: data
                });

                picker.on('change', function () {
                    // Update font codes.
                    var selected = vectorIconsData[slimVectorIconsPicker.selected()];
                    $('#tps_theme_vector_left_icon').attr('value', selected.leftIcon);
                    $('#tps_theme_vector_right_icon').attr('value', selected.rightIcon);
                    if (selected.leftIcon === '') {
                        $('.theiaPostSlider_nav ._buttons ._button span').each(function () {
                            if (!$(this).text().trim().length) {
                                $(this).css("margin", "0");
                            }
                        });
                    }
                });

                $('#theme_vector_custom').on('change', function () {
                    $('.custom-icons-wrapper').toggle(this.checked);
                    this.checked ? slimVectorIconsPicker.disable() : slimVectorIconsPicker.enable();
                    updateSlider();
                }).trigger('change');

                $('.custom-icon button').on('click', function (e) {
                    e.preventDefault();
                    $(this).parent().find('input[type=file]').click();
                });

                $('.custom-icon input[type=file]').on('change', function (e) {
                    var fileInput = $(this);
                    var input = $(this).parent().find('input[type=hidden]');
                    var img = $(this).parent().find('img');
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.addEventListener('load', function (e) {
                        img.attr('src', reader.result);
                        console.log(reader.result);
                        var needle = 'base64,';
                        var position = reader.result.indexOf(needle);
                        input.val(reader.result.slice(position + needle.length));
                        fileInput.val(null);
                        customIconsSync();
                    });
                    reader.readAsDataURL(file);
                });

                $('.custom-icon .remove').on('click', function (e) {
                    var input = $(this).parent().find('input[type=hidden]').val('');
                    var img = $(this).parent().find('img').attr('src', '');
                    customIconsSync();
                });

                function customIconsSync() {
                    $('.custom-icon').each(function () {
                        $(this).find('.remove').toggle(!!$(this).find('img').attr('src'));
                    });
                    updateSlider();
                }

                customIconsSync();
            });
        </script>
        <?php
    }

    private static function getImageData( $image ) {
        return $image ? 'data:image/svg+xml;base64,' . $image : '';
    }
}
