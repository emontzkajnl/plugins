<?php
/*
Plugin Name: Delete Bad Behavior Log
Plugin URI: http://www.blogdemy.com/wordpress-plugins/delete-bad-behavior-log-wordpress-plugin/
Description: Delete HTTP Log of Bad Behavior Plugin
Version: 0.5
Author: Parasmani
Author URI: http://www.blogdemy.com
*/

add_action('admin_menu', 'bd_delete_bad_behavior_menu');

function bd_delete_bad_behavior_menu() {
	add_options_page('Delete Bad Behavior Log', 'Delete Bad Behavior Log', 8, 'bd-delete-bad-behavior-log', 'bd_delete_bad_behavior_log_page');
}

function bd_delete_bad_behavior_log_page() {

	if (isset($_POST['bd_delete_bad_behavior_log'])) {
		$truncate_query = "TRUNCATE TABLE wp_bad_behavior";
		$result = mysql_query($truncate_query);
        }

	$status_query = 'SHOW TABLE STATUS FROM '. DB_NAME.' WHERE NAME = \'wp_bad_behavior\'';
	$result = mysql_query($status_query);
	if (mysql_num_rows($result)){
		while ($row = mysql_fetch_array($result))
		{
			$num_rows = $row['Rows'];
			$row_data = $row['Avg_row_length'];
			$total_row_data = $num_rows * $row_data;
			$total_index_data = $row['Index_length'];

			$total_db_space = $total_row_data + $total_index_data;
			$total_space = $total_db_space / 1024 ;
			$total_space = round($total_space);

		}
	}
?>
	<h2>Delete Bad Behavior Log</h2>
	<table border="1">
	<thead>
		<tr>
			<th>Table Name</th>
			<th>Size</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>wp_bad_behavior</td>
			<td><?php echo $total_space . " Kb"?></td>
			<td><?php bd_create_form_btn(); ?></td>
		</tr>
	</tbody>
	</table>
<?php
	if (isset($_POST['bd_delete_bad_behavior_log'])) {
		echo "<p> Log Deleted.";
        }
	else
		echo "<p> Click on \"Empty Log\" button to remove log entries.";
?>
        <hr />
<!--	<p> If you find this plugin useful, you can help by doing following.
	<ul>
		<li>Rate it on wordpress plugin directory</li>
		<li>Share it on social media</li>
	</ul>	-->
	<p> Visit <a href="http://www.blogdemy.com">blogdemy.com</a>
<?php
}

function bd_create_form_btn() {
?>
        <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
		<input type="submit" name="bd_delete_bad_behavior_log" style="background:#ff7777" value="<?php echo 'Empty Log'; ?>" />
	</form>
<?php
}
?>
