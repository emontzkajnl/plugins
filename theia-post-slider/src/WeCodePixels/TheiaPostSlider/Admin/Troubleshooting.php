<?php

/*
 * Copyright 2012-2021, Theia Post Slider, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaPostSlider\Admin;

class Troubleshooting
{
    public static function staticConstruct()
    {
        add_filter('pre_update_option_tps_troubleshooting', __NAMESPACE__ . '\\Troubleshooting::pre_update_option_tps_troubleshooting', 10, 3);
    }

    public static function pre_update_option_tps_troubleshooting($value, $old_value, $option)
    {
        $samplePost = array_key_exists('create_sample_post', $value) && $value['create_sample_post'];
        if ($samplePost) {
            $value['create_sample_post'] = '';
            $loremIpsum = "\n\n<em>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris eleifend ultricies aliquet. Etiam semper diam non pretium molestie. Nulla finibus vulputate nisl sed facilisis. Mauris ultricies sapien egestas nisl dignissim, ac volutpat neque sollicitudin. Vivamus et nulla a neque luctus egestas ut sit amet ex. Sed a lorem vitae tortor tincidunt finibus. Sed laoreet maximus purus vitae gravida. Vivamus et aliquet lacus. Nulla placerat leo quis felis euismod accumsan. Pellentesque aliquet mauris enim, vel ornare tortor vestibulum at. Nunc viverra elementum felis, vitae bibendum purus gravida ut.</em>";
            $content = '
                [tps_header]This is an optional header that will appear above all slides.[/tps_header]
                [tps_title]This is the optional title of the 1st slide[/tps_title]
                This is the content of the first slide. ' . $loremIpsum . '
                <!--nextpage-->
                [tps_title]This is the optional title of the 2nd slide[/tps_title]
                This is the content of the second slide. ' . $loremIpsum . '
                <!--nextpage-->
                [tps_title]This is the optional title of the 3rd slide[/tps_title]
                This is the content of the third slide. ' . $loremIpsum . '
                [tps_footer]This is an optional footer that will appear below all slides.[/tps_footer]
            ';
            $id = wp_insert_post([
                'post_title' => __('Theia Post Slider - Sample Post', 'theia-post-slider'),
                'post_content' => $content,
                'post_status' => 'draft',
            ]);
            update_post_meta($id, 'tps_options', [
                'enabled' => 'enabled'
            ]);
            $url = get_permalink($id);
            add_settings_error('a', 'b', sprintf(__('A sample post has been created. You can <a href="%s">view it now</a>.'), esc_attr($url)), 'success');
        }

        return $value;
    }

    public function echoPage() {
		?>
		<form method="post" action="options.php">
			<?php settings_fields( 'tps_options_troubleshooting' ); ?>
			<?php $options = get_option( 'tps_troubleshooting' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label><?php _e( "Getting started:", 'theia-post-slider' ); ?></label>
                    </th>
                    <td>
                        <button type="submit" class="button-primary" name="tps_troubleshooting[create_sample_post]" value="true">
                            <?= __('Create Sample Post', 'theia-post-slider') ?>
                        </button>
                        <p class="description">
                            <?= __('This will create a sample draft post so you can see the various tags and options that Theia Post Slider has to offer and how to use them.', 'theia-post-slider') ?>
                        </p>
                    </td>
                </tr>
            </table>

			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label><?php _e( "Compatibility options:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<label>
							<input type='hidden' value='false' name='tps_troubleshooting[remove_canonical_rel]'>
							<input type="checkbox"
							       name="tps_troubleshooting[remove_canonical_rel]"
							       value="true" <?php echo $options['remove_canonical_rel'] ? 'checked' : ''; ?>>
							Disable canonical URLs on slides.
							<p class="description">
								Canonical URLs are added by WordPress on each subsequent page, which could prevent
								your slides to be indexed by Google.
							</p>
						</label>
						<br>
						<label>
							<input type='hidden' value='false' name='tps_troubleshooting[do_not_check_for_multiple_instances]'>
							<input type="checkbox"
							       name="tps_troubleshooting[do_not_check_for_multiple_instances]"
							       value="true" <?php echo $options['do_not_check_for_multiple_instances'] ? 'checked' : ''; ?>>
							Do not check for multiple instances of the same post.
							<p class="description">
								Try this if the entire page refreshes on each slide when it shouldn't.
							</p>
						</label>
						<br>
						<label>
							<input type='hidden' value='false' name='tps_troubleshooting[do_not_cache_rendered_html]'>
							<input type="checkbox"
							       id="tps_troubleshooting_do_not_cache_rendered_html"
							       name="tps_troubleshooting[do_not_cache_rendered_html]"
							       onchange="updateForm()"
							       value="true" <?php echo $options['do_not_cache_rendered_html'] ? 'checked' : ''; ?>>
							Do not cache rendered HTML.
							<p class="description">
								Enable this if you use Twitter embeds, or any other plugin that generates iframes.
							</p>
						</label>
						<br>
						<label>
							<input type='hidden' value='false' name='tps_troubleshooting[try_to_fix_broken_html]'>
							<input type="checkbox"
							       id="try_to_fix_broken_html"
							       name="tps_troubleshooting[try_to_fix_broken_html]"
							       value="true" <?php echo $options['try_to_fix_broken_html'] ? 'checked' : ''; ?>>
							Try to fix broken HTML.
							<p class="description">
								This will use either <a href="https://php.net/manual/en/book.tidy.php">PHP Tidy</a> (if installed) or <a href="http://htmlpurifier.org/">HTMLPurifier</a> to fix posts with broken HTML. Note that this can cause your pages to load slower or have compatibility issues with other plugins. Therefore, we recommend disabling this option and manually validating your posts' code.
							</p>
						</label>
						<br>
						<label>
							<input type='hidden' value='false' name='tps_troubleshooting[disable_rocketscript]'>
							<input type="checkbox"
							       name="tps_troubleshooting[disable_rocketscript]"
							       value="true" <?php echo $options['disable_rocketscript'] ? 'checked' : ''; ?>>
							Disable CloudFlare's Rocket Loader (Rocketscript) for Theia Post Slider's JavaScript files, and for the jQuery library.
							<p class="description">
								This adds <strong>data-cfasync="false"</strong> to the aforementioned script tags.
							</p>
						</label>
                        <br>
                        <label>
                            <input type='hidden' value='false' name='tps_troubleshooting[add_header_to_excerpt]'>
                            <input type="checkbox"
                                   name="tps_troubleshooting[add_header_to_excerpt]"
                                   value="true" <?php echo $options['add_header_to_excerpt'] ? 'checked' : ''; ?>>
                            Include text from [tps_header] and [tps_footer] in excerpts.
                            <p class="description">
                                You might want to do this depending on the theme you are using.
                            </p>
                        </label>
					</td>
				</tr>
                <tr>
                    <th scope="row">
                        <label><?php _e( "Additional triggers:", 'theia-post-slider' ); ?></label>
                        <p class="description">
                            <?php _e('Try this when only the first slide is displayed correctly.', 'theia-post-slider') ?>
                        </p>
                    </th>
                    <td>
                        <label>
                            <input type='hidden' value='false' name='tps_troubleshooting[document_ready_js]'>
                            <input type="checkbox"
                                   name="tps_troubleshooting[document_ready_js]"
                                   value="true" <?php echo $options['document_ready_js'] ? 'checked' : ''; ?>>
                            Trigger <strong>jQuery(document).ready()</strong> upon navigating to another slide.
                        </label>
                        <br>
                        <label>
                            <input type='hidden' value='false' name='tps_troubleshooting[window_load_js]'>
                            <input type="checkbox"
                                   name="tps_troubleshooting[window_load_js]"
                                   value="true" <?php echo $options['window_load_js'] ? 'checked' : ''; ?>>
                            Trigger <strong>jQuery(window).load()</strong> upon navigating to another slide.
                        </label>
                        <br>
                        <label>
                            <input type='hidden' value='false' name='tps_troubleshooting[domcontentloaded_js]'>
                            <input type="checkbox"
                                   name="tps_troubleshooting[domcontentloaded_js]"
                                   value="true" <?php echo $options['domcontentloaded_js'] ? 'checked' : ''; ?>>
                            Trigger <strong>DOMContentLoaded</strong> upon navigating to another slide.
                        </label>
                        <br>
                        <label>
                            <input type='hidden' value='false' name='tps_troubleshooting[document_resize_js]'>
                            <input type="checkbox"
                                   name="tps_troubleshooting[document_resize_js]"
                                   value="true" <?php echo $options['document_resize_js'] ? 'checked' : ''; ?>>
                            Trigger <strong>jQuery(document).resize()</strong> upon navigating to another slide.
                        </label>
                        <br>
                        <label>
                            <input type='hidden' value='false' name='tps_troubleshooting[document_scroll_js]'>
                            <input type="checkbox"
                                   name="tps_troubleshooting[document_scroll_js]"
                                   value="true" <?php echo $options['document_scroll_js'] ? 'checked' : ''; ?>>
                            Trigger <strong>jQuery(document).scroll()</strong> upon navigating to another slide.
                        </label>
                        <br>
                        <label>
                            <p style="margin: 10px 0 5px 0">
                                Trigger <strong>a custom script</strong> upon navigating to another slide (enter JavaScript code directly without &lt;script&gt; tags):
                            </p>
                            <textarea class="large-text code"
                                      rows="5"
                                      id="tps_troubleshooting_document_custom_js"
                                      name="tps_troubleshooting[document_custom_js]"><?php echo htmlspecialchars($options['document_custom_js']); ?></textarea>
                        </label>
                    </td>
                </tr>
				<tr>
					<th scope="row">
						<label for="tps_troubleshooting_excludedWords"><?php _e( "Force a page refresh when navigating to slides that include any of the following keywords:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<textarea class="large-text code"
						          rows="5"
						          id="tps_troubleshooting_excludedWords"
						          name="tps_troubleshooting[excludedWords]"><?php echo htmlspecialchars( $options['excludedWords'] ); ?></textarea>
						<p class="description">
							Enter one keyword per line. Only works when "<b>Do not cache rendered HTML</b>" is enabled.
						</p>
					</td>
				</tr>
			</table>

			<h3><?php _e( "Danger zone", 'theia-post-slider' ); ?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="tps_the_content_early_priority"><?php _e( "Priority for the the_content action hook:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="number"
						       id="tps_the_content_early_priority"
						       name="tps_troubleshooting[the_content_early_priority]"
						       value="<?php echo $options['the_content_early_priority']; ?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_beginning_post_content_separator"><?php _e( "Beginning post content separator:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="text"
						       class="regular-text"
						       id="tps_beginning_post_content_separator"
						       name="tps_troubleshooting[beginning_post_content_separator]"
						       value="<?php echo htmlspecialchars( $options['beginning_post_content_separator'] ); ?>">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="tps_ending_post_content_separator"><?php _e( "Ending post content separator:", 'theia-post-slider' ); ?></label>
					</th>
					<td>
						<input type="text"
						       class="regular-text"
						       id="tps_ending_post_content_separator"
						       name="tps_troubleshooting[ending_post_content_separator]"
						       value="<?php echo htmlspecialchars( $options['ending_post_content_separator'] ); ?>">
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit"
				       class="button-primary"
				       value="<?php _e( 'Save All Changes', 'theia-post-slider' ) ?>" />
			</p>
		</form>
		<script type="text/javascript">
			function updateForm() {
				var $ = jQuery;

				var refreshAds = $('#tps_refresh_ads').attr('checked') == 'checked';
				$('#tps_refresh_ads_every_n_slides').attr('readonly', !refreshAds);
				$('#tps_ad_refreshing_mechanism_javascript').attr('disabled', !refreshAds);
				$('#tps_ad_refreshing_mechanism_page').attr('disabled', !refreshAds);

				var doNotCacheRenderedHtml = $('#tps_troubleshooting_do_not_cache_rendered_html').attr('checked') == 'checked';
				$('#tps_troubleshooting_excludedWords').attr('disabled', !doNotCacheRenderedHtml);
			}

			updateForm();
		</script>
		<?php
	}
}
