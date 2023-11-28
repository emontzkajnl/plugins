<?php
/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

global $poll;

if($_SERVER['REQUEST_METHOD'] == "POST"){
	if( isset( $_POST['import_settings'] ) ){
		if(!wp_verify_nonce($_POST['poller_master_import_field'], 'poller_master_import')){
			die( __( 'Sorry, but this request is invalid', 'poller_master' ) );
		}
		else{
			if( !empty( $_POST['import_settings_json'] ) ){
				$info = $poll->import_options_and_polls( $_POST['import_settings_json'] );
				if( $info === true ){
					$poll->throw_info( __( 'Settings have been imported successfully.', 'poller_master' ), 'success' );
				}
				else{
					$poll->throw_info( $info, 'error' );
				}
			}
			else{
				$poll->throw_info( __( 'No settings provided for the import action.', 'poller_master' ), 'error' );
			}
		}
	}
	else{
		if(!wp_verify_nonce($_POST['poller_master_export_field'], 'poller_master_export')){
			die( __( 'Sorry, but this request is invalid', 'poller_master' ) );
		}
		else{
			$data = $poll->export_options_and_polls();
		}
	}
}
?>
<h2><?php _e( 'Import / Export Poller Master Polls And Options', 'poller_master' ); ?></h2>
<br />
<hr />
<br />
<form method="post" action="<?php echo $GLOBALS['PHP_SELF'] . '?page='.$poll->poller_master_expimp_page; ?>">	
	<h3><?php _e( 'Import Poller Master Polls And Options', 'poller_master' ); ?></h3>
	<label><?php _e( 'Input JSON string in the textarea bellow', 'poller_master' ); ?><br />
		<textarea name="import_settings_json" style="height: 250px; width: 98%;"></textarea><br />
	</label>
	<?php wp_nonce_field('poller_master_import','poller_master_import_field'); ?>
	<input type="submit" name="import_settings" value="<?php _e( 'Import Options And Polls', 'poller_master' ); ?>">
</form>
<br />
<hr />
<br />
<form method="post" action="<?php echo $GLOBALS['PHP_SELF'] . '?page='.$poll->poller_master_expimp_page; ?>">
	<h3><?php _e( 'Export Poller Master Polls And Options', 'poller_master' ); ?></h3>
	<?php if( !empty( $data ) ): ?>
		<label><?php _e( 'Copy the string bellow to back-up the data.', 'poller_master' ); ?><br />
			<textarea name="import_settings_json" style="height: 250px; width: 98%;"><?php echo $data; ?></textarea><br />
		</label>
	<?php endif; ?>
	<?php wp_nonce_field('poller_master_export','poller_master_export_field'); ?>
	<input type="submit" name="export_settings" value="<?php _e( 'Export Options And Polls', 'poller_master' ); ?>">
</form>