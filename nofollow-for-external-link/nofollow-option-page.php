<div class="wrap">
	<h2>Nofollow for external link Options</h2>
	<div class="content_wrapper">
		<div class="left">
			<form method="post" action="options.php" enctype="multipart/form-data">
				<?php settings_fields( 'cn-nf-settings-group' ); ?>
				<table class="form-table">

					<tr valign="top">
					<th scope="row">Apply nofollow to Menu</th>
					<td><input <?php echo ($cn_nf_apply_to_menu == 1)?'checked="checked"':''; ?>  type="checkbox" name="cn_nf_apply_to_menu" id="cn_nf_apply_to_menu" value="1" /><br />
					<em>If you check this box then <code>rel="nofollow"</code> and <code>target="_blank"</code> will be added to all external links of your <a href="nav-menus.php">Theme Menus</a></em></td>
					</tr>

					<tr valign="top">
					<th scope="row">Exclude Domains</th>
					<td><textarea name="cn_nf_exclude_domains" id="cn_nf_exclude_domains" class="large-text" placeholder="mydomain.com, my-domain.org, another-domain.net"><?php echo $cn_nf_exclude_domains?></textarea>
		            <br /><em>Domain name <strong>must be</strong> comma(,) separated. <!--<br />Example: facebook.com, google.com, youtube.com-->Don't need to add <code>http://</code> or <code>https://</code><br /><code>rel="nofollow"</code> will not added to "Exclude Domains"</em></td>
					</tr>
				</table>
				<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>
	    </div>
	    <div class="right">
	    <?php cn_nf_admin_sidebar(); ?>
	    </div>
    </div>
</div>