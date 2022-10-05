<?php
/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

global $poll;

if($_SERVER['REQUEST_METHOD'] == "POST"){
	if(!wp_verify_nonce($_POST['save_options_field'], 'save_options')){
		die( __( 'Sorry, but this request is invalid', 'poller_master' ) );
	}
	else{
		poller_master_reset_tables();
		$poll->throw_info( __( 'Tables and options have been successfully reset.', 'poller_master' ), 'success' );
	}
}
?>
<form method="post" action="<?php echo $GLOBALS['PHP_SELF'] . '?page='.$poll->poller_master_reset_page; ?>">
	<h2><?php _e( 'Reset Poller Master', 'poller_master' ); ?></h2>
	<p><?php _e( 'This can not be undone and this action will empty the plugin\'s tables and restore its options to defaults.', 'poller_master' ); ?></p><br />
	<?php wp_nonce_field('save_options','save_options_field'); ?>
	<input type="submit" class="button action" name="submit" value="<?php _e( 'Reset', 'poller_master' ); ?>">
</form>