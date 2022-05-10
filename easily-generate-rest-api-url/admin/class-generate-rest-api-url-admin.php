<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       google.com
 * @since      1.0.0
 *
 * @package    Generate_Rest_Api_Url
 * @subpackage Generate_Rest_Api_Url/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Generate_Rest_Api_Url
 * @subpackage Generate_Rest_Api_Url/admin
 * @author     kinjal dalwadi <kinjal.dalwadi@yahoo.com>
 */
class Generate_Rest_Api_Url_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Generate_Rest_Api_Url_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Generate_Rest_Api_Url_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/generate-rest-api-url-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Generate_Rest_Api_Url_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Generate_Rest_Api_Url_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/generate-rest-api-url-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
     * add plugin main menu in admin menu
     *
     */
    public function generate_restapi_code_custom_menu() {
		
		 //add admin main Crea plugins menu

        add_submenu_page('options-general.php', GRAU_PLUGIN_PAGE_MENU_TITLE, __(GRAU_PLUGIN_NAME, GRAU_PLUGIN_SLUG), 'manage_options', GRAU_PLUGIN_PAGE_MENU_SLUG, 'custom_restapi_setting_html');
		
	
	
	 /**
         * function for plugin admin menu callback html function
         */
        function custom_restapi_setting_html() {
            global $wpdb;
            $current_user = wp_get_current_user();

            //get settings option
            $get_analytic_settings = get_option(GRAU_PLUGIN_GLOBAL_SETTING_KEY);
            $get_analytic_settings = maybe_unserialize($get_analytic_settings);
            ?>

            <!--create customrestapi_codes settings html-->	
            <div class="customrestapi_codes-containar">
                    <fieldset class="fs_global">
                    <legend><div class="customrestapi_codes-header"><h2><?php echo __(GRAU_PLUGIN_HEADER_NAME, GRAU_PLUGIN_SLUG); ?></h2></div></legend>
                    <div class="customrestapi_codes-contain">

                        <form id="customrestapi_codes_plugin_form_id" method="post" action="<?php echo get_admin_url(); ?>admin-post.php">
                            <input type='hidden' name='action' value='submit-form' />
                            <input type='hidden' name='action-which' value='add' />

                            <table class="form-table">
                                <tbody>
								  <tr>
                                        <th scope="row"><label  for="posttype"><?php echo __(GRAU_PLUGIN_ADDITIONAL_STYLE_POSTTYPE, GRAU_PLUGIN_SLUG); ?></label></th>
										<td>
								<?php 
								
								$args_posttype = array(
								   'public'   => true,
								   '_builtin' => false
								);

								$output_posttype = 'object'; // names or objects, note names is the default
								$operators = 'and'; // 'and' or 'or'

								$post_types = get_post_types( $args_posttype, $output_posttype, $operators ); 
								$select_posttype = "<select name='posttype_selects' id='posttype_selects' class='postform'>";
								$select_posttype.= "<option value='null'>Select Post type</option>";
								$select_posttype.= "<option value='posts'>Posts</option>";
								
								foreach ( $post_types  as $post_type ) {
										
										$select_posttype.= "<option value='".$post_type->name."'>".$post_type->label."</option>"; 
										
								}
								
								$select_posttype.= "</select>";
								echo $select_posttype;
								?></td>
								</tr>
                                    <tr>
                                        <th scope="row"><label  for="per page"><?php echo __(GRAU_PLUGIN_ADDITIONAL_STYLE_TITLE, GRAU_PLUGIN_SLUG); ?></label></th>
                                       <td><input type="text" name="add_custom_service_style" id="add_custom_service_style" class="code" style="width: 15%; font-size: 17px;" maxlength="30" value="<?php echo esc_html(!empty($get_analytic_settings['include_customrestapi_codes'])) ? ($get_analytic_settings['include_customrestapi_codes']) : '5' ; ?>" /></td>
                                    </tr>	
									<tr>
										<th scope="row"><label for="category"><?php echo __(GRAU_PLUGIN_ADDITIONAL_STYLE_CATEGORY_TITLE, GRAU_PLUGIN_SLUG); ?></label></th>
										 <td><?php
 
										  $categories = get_categories('taxonomy=category');
										 
										  $select = "<select name='cat' id='cat' class='postform'>";
										  $select.= "<option value='null'>Select Category</option>";
										  foreach($categories as $category){
											if($category->count > 0){
												$select.= "<option value='".$category->term_id."'>".$category->name."</option>";
											}
										  }
										  $select.= "</select>";
										  echo $select;
										?></td>
				                   	</tr>
									
									<tr>
										<th scope="row"><label for="tags"><?php echo __(GRAU_PLUGIN_ADDITIONAL_STYLE_TAG, GRAU_PLUGIN_SLUG); ?></label></th>
										 <td>
										 <?php
 
										  $post_tags = get_categories('taxonomy=post_tag');
										
										  $select_tags = "<select name='tags_posts' id='tags_posts' class='postform'>";
										  $select_tags.= "<option value='null'>Select Tag</option>";
										  foreach($post_tags as $posttag){
											if($posttag->count > 0){
												$select_tags.= "<option value='".$posttag->term_id."'>".$posttag->name."</option>";
											}
										  }
										  $select_tags.= "</select>";
										  echo $select_tags;
										?></td>
				                   	</tr>
									
									<tr>
									<th scope="row"><label for="exclude post"><?php echo __(GRAU_PLUGIN_ADDITIONAL_STYLE_POST_EXCLUDE, GRAU_PLUGIN_SLUG); ?></label></th>
									<td>
									<?php 
										$args=array(
											'post_type' => 'post',
											'post_status' => 'publish',
											'posts_per_page' => -1,
										);
										$my_query = null;
										$my_query = new WP_Query($args);
										if( $my_query->have_posts() ) {
										?>
										<select name="excludepostlists" id="excludepostlists">
										<option value='null'><?php echo esc_attr( __( 'Select Post' ) ); ?></option>
										<?php
										while ($my_query->have_posts()) : $my_query->the_post(); ?>
										<option value="<?php the_ID() ?>"><?php the_title(); ?></option>
										<?php
										endwhile;?>
										</select>
										<?php
										}
										?>
										
									</td>
									</tr>	
									
									<tr>
									<th scope="row"><label for="include post"><?php echo __(GRAU_PLUGIN_ADDITIONAL_STYLE_POST_INCLUDE, GRAU_PLUGIN_SLUG); ?></label></th>
									<td>
									<?php 
										$args_include = array(
											'post_type' => 'post',
											'post_status' => 'publish',
											'posts_per_page' => -1,
										);
										$my_include_query = null;
										$my_include_query = new WP_Query($args_include);
										if( $my_include_query->have_posts() ) {
										?>
										<select name="includepostlists" id="includepostlists">
										<option value='null'><?php echo esc_attr( __( 'Select Post' ) ); ?></option>
										<?php
										while ($my_include_query->have_posts()) : $my_include_query->the_post(); ?>
										<option value="<?php the_ID() ?>"><?php the_title(); ?></option>
										<?php
										endwhile; ?>
										</select>
										<?php } ?>
									</td>
									</tr>	
									<tr>
										<th scope="row"><label for="order"><?php echo __(GRAU_PLUGIN_ADDITIONAL_STYLE_ORDER, GRAU_PLUGIN_SLUG); ?></label></th>
										 <td>
										 <select name="postorders" id="postorders">
										 <option value="null"><?php echo esc_attr( __( 'Select Order' ) ); ?></option>
										 <option <?php if(get_option('order_list_api') == 'desc'){ echo 'selected';} ?> value="desc">DESC</option>
										 <option  <?php if(get_option('order_list_api') == 'asc'){ echo 'selected';} ?> value="asc">ASC</option>
										 </select>
										 </td>
				                   	</tr>
									<tr>
										<th scope="row"><label for="order by"><?php echo __(GRAU_PLUGIN_ADDITIONAL_STYLE_ORDER_BY, GRAU_PLUGIN_SLUG); ?></label></th>
										 <td>
										 <select name="postorderby" id="postorderby">
										 <option value="null"><?php echo esc_attr( __( 'Select Order by' ) ); ?></option>
										 <option <?php if(get_option('orderby_list_api')=='author'){ echo 'selected'; }?> value="author">Author</option>
										 <option <?php if(get_option('orderby_list_api')=='date'){ echo 'selected'; }?> value="date">Date</option>
										 <option <?php if(get_option('orderby_list_api')=='id'){ echo 'selected'; }?> value="id">Id</option>
										 <option <?php if(get_option('orderby_list_api')=='slug'){ echo 'selected'; }?> value="slug">Slug</option>
										 <option <?php if(get_option('orderby_list_api')=='title'){ echo 'selected'; }?> value="title">Title</option>
										 </select>
										 </td>
				                   	</tr>
									<tr>
										<th scope="row"><label for="author"><?php echo __(GRAU_PLUGIN_ADDITIONAL_AUTHOR, GRAU_PLUGIN_SLUG); ?></label></th>
										<td>
										<select name="author-dropdown" id="author-dropdown">
											<option value="null"><?php echo esc_attr( __( 'Select Author' ) ); ?></option> 
											<?php 
											// loop through the users
											$users = get_users('role=author');
											foreach ($users as $user) 
											{
												if(count_user_posts( $user->id ) >0)
												{
												  // We need to add our url to the authors page
												  echo '<option value="'.( $user->id ).'">';
												  // Display name of the auther you could use another like nice_name
												  echo $user->display_name;
												  echo '</option>'; 
												} 
											}
											?>
											</select> 
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="status"><?php echo __(GRAU_PLUGIN_ADDITIONAL_STYLE_POST_STATUS, GRAU_PLUGIN_SLUG); ?></label></th>
										 <td>
										 <select name="poststatus" id="poststatus">
										 <option value="null"><?php echo esc_attr( __( 'Select Status' ) ); ?></option>
										 <option <?php if(get_option('status_list_api')== 'publish'){ echo 'selected'; } ?> value="publish">Publish</option>
										 <option <?php if(get_option('status_list_api')== 'future'){ echo 'selected'; } ?> value="future">Future</option>
										 <option <?php if(get_option('status_list_api')== 'draft'){ echo 'selected'; } ?> value="draft">Draft</option>
										 <option <?php if(get_option('status_list_api')== 'pending'){ echo 'selected'; } ?> value="pending">Pending</option>
										 <option <?php if(get_option('status_list_api')== 'private'){ echo 'selected'; } ?>  value="private">Private</option>
										 </select>
										 </td>
				                   	</tr>
									<tr>
                                        <th scope="row"><label  for="per page"><?php echo __(GRAU_PLUGIN_ADDITIONAL_STYLE_SEARCH, GRAU_PLUGIN_SLUG); ?></label></th>
                                       <td><input type="text" name="search_texts" id="search_texts" class="code" style="width: 15%; font-size: 17px;" maxlength="30" value="<?php if(!empty(get_option('searchres_list_api'))) { echo get_option('searchres_list_api'); } ?>" /></td>
                                    </tr>
									
                                </tbody>
                            </table>

                            <p class="submit"><input type="submit" value="<?php echo __(GRAU_PLUGIN_OPTION_SAVE_BTN, GRAU_PLUGIN_SLUG); ?>" class="button button-primary" id="submit_plugin" name="submit_plugin"></p>
                        </form>
						<p>URL</p>
						<?php 
						if(!empty($get_analytic_settings['include_customrestapi_codes'])){
							$result_per_page = '?per_page='.$get_analytic_settings['include_customrestapi_codes'];
						}
						/* Get specific single post for exclude */
						if((!empty(get_option('exclude_posts_list_api'))) && (get_option('exclude_posts_list_api')!='null')){
							$result_exclude_post_list_api = '&exclude='.get_option('exclude_posts_list_api');
						}
						
						/* Get specific single post for include */
						if((!empty(get_option('include_posts_list_api'))) && (get_option('include_posts_list_api')!='null')){
							$result_include_post_list_api = '&include='.get_option('include_posts_list_api');
						}
						
						/* Display selected category post */
						if((!empty(get_option('category_list_api'))) && ((get_option('category_list_api'))!='null')){
							$result_category_list_api = '&categories='.get_option('category_list_api');
						}
						/* Display selected tags post */
						if((!empty(get_option('tags_list_api'))) && ((get_option('tags_list_api'))!='null')){
							$result_tags_list_api = '&tags='.get_option('tags_list_api');
						}
						
						/* Select Order to display post */
						if((!empty(get_option('order_list_api'))) && ((get_option('order_list_api'))!='null')){
							$result_order_list_api = '&order='.get_option('order_list_api');
						}
						/* Select Orderby to display orderby post */
						if((!empty(get_option('orderby_list_api'))) && ((get_option('orderby_list_api'))!='null')){
							$result_orderby_list_api = '&orderby='.get_option('orderby_list_api');
						}
						/* Select Orderby to display author's post */
						if((!empty(get_option('author_list_api'))) && ((get_option('author_list_api'))!='null')){
							$result_author_list_api = '&author='.get_option('author_list_api');
						}
						/* Select Status of Post */
						if((!empty(get_option('status_list_api'))) && ((get_option('status_list_api'))!='null')){
							$result_status_list_api = '&status='.get_option('status_list_api');
						}
						/* Enter Search Text */
						if((!empty(get_option('searchres_list_api'))) && ((get_option('searchres_list_api'))!='null')){
							$result_search_list_api = '&search='.get_option('searchres_list_api');
						}
						
						/* Select Post Type */
						if((!empty(get_option('posttype_list_api'))) && ((get_option('posttype_list_api'))!='null')){
							$result_post_type_list_api = get_option('posttype_list_api');
						}else{
							$result_post_type_list_api = 'posts';
						}
						
						
						?>
						<a target="_blank" href="<?php echo site_url();?>/index.php/wp-json/wp/v2/<?php echo $result_post_type_list_api.$result_per_page.$result_category_list_api.$result_tags_list_api.$result_order_list_api.$result_orderby_list_api.$result_exclude_post_list_api.$result_include_post_list_api.$result_author_list_api.$result_status_list_api.$result_search_list_api;?>"><?php echo site_url();?>/index.php/wp-json/wp/v2/<?php echo $result_post_type_list_api.$result_per_page.$result_category_list_api.$result_tags_list_api.$result_order_list_api.$result_orderby_list_api.$result_exclude_post_list_api.$result_include_post_list_api.$result_author_list_api.$result_status_list_api.$result_search_list_api;?></a>
                    </div>

                </fieldset>	
			<?php
		}
		
		
		}
		 /**
         * function for add or update generate rest api codes admin settings
         *
         */
        public function restapi_code_setting_add_update() {
           global $wpdb, $wp, $post;
			  
            //get action
            $submitAction = !empty( $_POST['action'] ) ? $_POST['action']:'';
            $submitFormAction = !empty( $_POST['action-which'] ) ? $_POST['action-which']:'';
						
			$addCustomServiceStyle = sanitize_text_field(!empty($_POST['add_custom_service_style']) ? $_POST['add_custom_service_style'] : null);
			
            $customrestapi_codesSettingArray = array();
				
				
            //check action 
            if ($submitFormAction == 'add' && !empty($submitFormAction) && $submitFormAction != '' && $submitAction == 'submit-form') {
				$category__api_list = isset($_POST['cat']) ? $_POST['cat'] : '';
				$tag__api_list = isset($_POST['tags_posts']) ? $_POST['tags_posts'] : '';
				$exclude_post__api_list = isset($_POST['excludepostlists']) ? $_POST['excludepostlists'] : '';
				$include_post__api_list = isset($_POST['includepostlists']) ? $_POST['includepostlists'] : '';
				$order__api_list = isset($_POST['postorders']) ? $_POST['postorders'] : '';
				$orderby__api_list = isset($_POST['postorderby']) ? $_POST['postorderby'] : '';
				$author__api_list = isset($_POST['author-dropdown']) ? $_POST['author-dropdown'] : '';
				$status__api_list = isset($_POST['poststatus']) ? $_POST['poststatus'] : '';
				$search__api_list = isset($_POST['search_texts']) ? $_POST['search_texts'] : '';
				$posttype__api_list = isset($_POST['posttype_selects']) ? $_POST['posttype_selects'] : '';
				
                //create customrestapi_codes settings array
                $customrestapi_codesSettingArray['include_customrestapi_codes'] = $addCustomServiceStyle;

                //serialize customrestapi_codes settings array
                $customrestapi_codesSettingArray = maybe_serialize($customrestapi_codesSettingArray);
	
                //update customrestapi_codes setting array
                update_option(GRAU_PLUGIN_GLOBAL_SETTING_KEY, $customrestapi_codesSettingArray);
				
				delete_option('category_list_api');
				if( isset($category__api_list) && $category__api_list != null ){
					update_option('category_list_api',$category__api_list);
				}
				
				delete_option('exclude_posts_list_api');
				if( isset($exclude_post__api_list) && $exclude_post__api_list != null ){
					update_option('exclude_posts_list_api',$exclude_post__api_list);
				}
				
				delete_option('include_posts_list_api');
				if( isset($include_post__api_list) && $include_post__api_list != null ){
					update_option('include_posts_list_api',$include_post__api_list);
				}
			
				delete_option('order_list_api');
				if( isset($order__api_list) && $order__api_list != null ){
					update_option('order_list_api',$order__api_list);
				}
				
				delete_option('orderby_list_api');
				if( isset($orderby__api_list) && $orderby__api_list != null ){
					update_option('orderby_list_api',$orderby__api_list);
				}
				
				delete_option('author_list_api');
				if( isset($author__api_list) && $author__api_list != null ){
					update_option('author_list_api',$author__api_list);
				}
				
				delete_option('status_list_api');
				if( isset($status__api_list) && $status__api_list != null ){
					update_option('status_list_api',$status__api_list);
				}
				
				delete_option('searchres_list_api');
				if( isset($search__api_list) && $search__api_list != null ){
					update_option('searchres_list_api',$search__api_list);
				}
				
				delete_option('tags_list_api');
				if( isset($tag__api_list) && $tag__api_list != null ){
					update_option('tags_list_api',$tag__api_list);
				}
				
				delete_option('posttype_list_api');
				if( isset($posttype__api_list) && $posttype__api_list != null ){
					update_option('posttype_list_api',$posttype__api_list);
				}
				
				
            }
			
            //redirect whatsapp customrestapi_codes page
            wp_safe_redirect(site_url("/wp-admin/admin.php?page=" . GRAU_PLUGIN_PAGE_MENU_SLUG));
            exit();
		}
		

}
?>