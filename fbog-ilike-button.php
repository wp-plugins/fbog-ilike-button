<?php
/*
Plugin Name: Facebook OpenGraph I Like Button
Plugin URI: http://raduboncea.ro/scripts/i-like-button-plugin/
Description: Integrates the facebook open graph I Like Button feature.  [<a href="options-general.php?page=fbog-ilike-button.php">Settings</a>]
Version: 1.2
Author: Radu Boncea
Author URI: http://raduboncea.ro/about/
*/


/*
* the output function which generates the iframe/xfbml based on options
*/
function FB_ILIKE_BUTTON($output_buffering=false) {
	
	if($output_buffering)ob_start();
	$linkurl		= get_permalink($post->ID);
	$linkurl_enc	= rawurlencode($linkurl);
	
	$_fb_layout_style = 'standard';
	$_fb_show_faces = 'true';
	if(get_option('FB_ILIKE_BUTTON_layout_style')!='') $_fb_layout_style = get_option('FB_ILIKE_BUTTON_layout_style');
	if(get_option('FB_ILIKE_BUTTON_show_faces')=='false') $_fb_show_faces = 'false';
	
	if(get_option('FB_ILIKE_BUTTON_width')=='') $_fb_width = '450';
    	else $_fb_width = get_option('FB_ILIKE_BUTTON_width');
	if(get_option('FB_ILIKE_BUTTON_height')=='') $_fb_height = '60';
    	else $_fb_height = get_option('FB_ILIKE_BUTTON_height');
	
    if(get_option('FB_ILIKE_BUTTON_action')=='') $_fb_action = 'like';
    	else $_fb_action = get_option('FB_ILIKE_BUTTON_action');
   	
    if(get_option('FB_ILIKE_BUTTON_colorscheme')=='') $_fb_colorscheme = 'light';
    	else $_fb_colorscheme = get_option('FB_ILIKE_BUTTON_colorscheme');
    
    if(get_option('FB_ILIKE_BUTTON_font')=='') $_fb_font = 'verdana';
    	else $_fb_font = get_option('FB_ILIKE_BUTTON_font');	
   	
    if(
		get_option('FB_ILIKE_BUTTON_platform')=='xfbml' &&
		(get_option('FB_ILIKE_BUTTON_appid')!='')
	){
		//generate the xfbml
		$fb_script = "
			<fb:like href=\"{$linkurl}\" layout=\"{$_fb_layout_style}\" show_faces=\"{$_fb_show_faces}\" width=\"{$_fb_width}\" font=\"{$_fb_font}\" action=\"{$_fb_action}\" colorscheme=\"{$_fb_colorscheme}\" />
		";
	}else{
		//generate the iframe
		$fb_script = "<iframe src=\"http://www.facebook.com/plugins/like.php?href={$linkurl_enc}&amp;layout={$_fb_layout_style}&amp;show_faces={$_fb_show_faces}&amp;width={$_fb_width}&amp;action={$_fb_action}&amp;font={$_fb_font}&amp;colorscheme={$_fb_colorscheme}\" scrolling=\"no\" frameborder=\"0\" allowTransparency=\"true\" style=\"border:none; overflow:hidden; width:{$_fb_width}px; height:{$_fb_height}px\"></iframe>";
	}
	
	echo $fb_script;
	
	if($output_buffering) {
		$ilike_button = ob_get_contents();
		ob_end_clean();
		return $ilike_button;
	}
}
	
/*
 * the function which inserts the iframe/xfbml generated by FB_ILIKE_BUTTON into $content
 * action registered
 */
function FB_ILIKE_BUTTON_show($content) {
	if ( 
		( (strpos($content, '<!--fbilike-->')===false) ) && (														
			( !is_page() && get_option('FB_ILIKE_BUTTON_display_in_posts')=='-1' ) || 								// All posts
			( !is_page() && !is_single() && get_option('FB_ILIKE_BUTTON_display_in_posts_on_front_page')=='-1' ) ||  // Front page posts
			( is_page() && get_option('FB_ILIKE_BUTTON_display_in_pages')=='-1' ) ||									// Pages
			( (strpos($content, '<!--nofbilike-->')!==false ) )												
		)
	)	
	return $content;
	
	
	
	if(get_option('FB_ILIKE_BUTTON_div_style')=='') $_fb_div_style = 'float:left;margin-right:20px;';
    	else $_fb_div_style = get_option('FB_ILIKE_BUTTON_div_style');
	
    $_content = '<div id="fbilike" style="'.$_fb_div_style.'">'.FB_ILIKE_BUTTON(true).'</div>';
	
	if(get_option('FB_ILIKE_BUTTON_disposition')=='top'){
		$content = $_content.$content;
	}else{
		$content .= $_content;
	}
		
	return $content;
}
add_filter('the_content', 'FB_ILIKE_BUTTON_show');


/*
 * Adding XFBML SDK Loader to footer
 * Haven't found a convenient way to add it after <body>
 */
function FB_ILIKE_BUTTON_insert_xfbml_sdk(){
	
	if(
		get_option('FB_ILIKE_BUTTON_platform')=='xfbml' &&
		(get_option('FB_ILIKE_BUTTON_appid')!='')
	){
	echo 
			"	<!-- XFBML LOADER -->
				<div id=\"fb-root\"></div>
			<script>
			  window.fbAsyncInit = function() {
			    FB.init({appId: '".get_option('FB_ILIKE_BUTTON_appid')."', status: true, cookie: true,
			             xfbml: true});
			  };
			  (function() {
			    var e = document.createElement('script'); e.async = true;
			    e.src = document.location.protocol +
			      '//connect.facebook.net/en_US/all.js';
			    document.getElementById('fb-root').appendChild(e);
			  }());
			</script>
			";
	}
}
add_filter ('get_footer','FB_ILIKE_BUTTON_insert_xfbml_sdk');

/*****************************
		OPTIONS
******************************/


function FB_ILIKE_BUTTON_options_page() {
	if( isset($_POST[ 'FB_ILIKE_BUTTON_saveit' ]) ) {
		 update_option( 'FB_ILIKE_BUTTON_display_in_posts_on_front_page', ($_POST['FB_ILIKE_BUTTON_display_in_posts_on_front_page']=='1') ? '1':'-1' );
		 update_option( 'FB_ILIKE_BUTTON_display_in_posts', ($_POST['FB_ILIKE_BUTTON_display_in_posts']=='1') ? '1':'-1' );
		 update_option( 'FB_ILIKE_BUTTON_display_in_pages', ($_POST['FB_ILIKE_BUTTON_display_in_pages']=='1') ? '1':'-1' );
		 update_option( 'FB_ILIKE_BUTTON_disposition', $_POST['FB_ILIKE_BUTTON_disposition'] );
		 update_option( 'FB_ILIKE_BUTTON_layout_style', $_POST['FB_ILIKE_BUTTON_layout_style'] );
		 update_option( 'FB_ILIKE_BUTTON_show_faces', ($_POST['FB_ILIKE_BUTTON_show_faces']=='true') ? 'true':'false' );
		 update_option( 'FB_ILIKE_BUTTON_height', trim($_POST['FB_ILIKE_BUTTON_height']) );
		 update_option( 'FB_ILIKE_BUTTON_width', trim($_POST['FB_ILIKE_BUTTON_width']) );
		 update_option( 'FB_ILIKE_BUTTON_action', $_POST['FB_ILIKE_BUTTON_action'] );
		 update_option( 'FB_ILIKE_BUTTON_colorscheme', $_POST['FB_ILIKE_BUTTON_colorscheme'] );
		 update_option( 'FB_ILIKE_BUTTON_font', $_POST['FB_ILIKE_BUTTON_font'] );
		 update_option( 'FB_ILIKE_BUTTON_div_style', $_POST['FB_ILIKE_BUTTON_div_style'] );
		 update_option( 'FB_ILIKE_BUTTON_platform', $_POST['FB_ILIKE_BUTTON_platform'] );
		 update_option( 'FB_ILIKE_BUTTON_appid', $_POST['FB_ILIKE_BUTTON_appid'] );
		 
		 
	?>
    <div class="updated fade"><p><strong><?php _e('Settings saved.', 'FB_ILIKE_BUTTON_trans_domain' ); ?></strong></p></div>
	<?php } ?>
	
	<div class="wrap">
		<h2><?php echo __( 'Facebook Open Graph: I Like Button Settings', 'FB_ILIKE_BUTTON_trans_domain' ); ?></h2>
	    <form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<?php wp_nonce_field('update-options'); ?>
		<input type="hidden" name="FB_ILIKE_BUTTON_saveit" />
		<table class="form-table">
			<tr valign="top">
	            <th scope="row">Choose Platform</th>
	            <td>
	            	<fieldset>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_platform" type="radio" <?php if(get_option('FB_ILIKE_BUTTON_platform')=='iframe' || get_option('FB_ILIKE_BUTTON_platform')=='') echo ' checked="checked"'; ?> value="iframe"/>
		                	&nbsp;&nbsp;&raquo;&nbsp;IFRAME&nbsp;&nbsp;(default)
		                </label><br/>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_platform" type="radio" <?php if(get_option('FB_ILIKE_BUTTON_platform')=='xfbml') echo ' checked="checked"'; ?> value="xfbml"/>
		                	&nbsp;&nbsp;&raquo;&nbsp;XFBML&nbsp;&nbsp;
		                	&nbsp;&nbsp;Facebook Application ID&nbsp;&raquo;&nbsp;<input size="16" type="text" name="FB_ILIKE_BUTTON_appid" value="<?php echo get_option('FB_ILIKE_BUTTON_appid');?>" />
		                	&nbsp;&nbsp;Don't have one? <a href="http://developers.facebook.com/setup/" target="_blank">Get it in 10 seconds.</a>
		                </label><br/>
		                XFBML is the recommended platform for geeks, yet it was launched recently and all kind of bugs might jump off your screen
	            	</fieldset>
	            </td>
	        </tr>
			<tr valign="top">
	            <th scope="row">Button Placement</th>
	            <td>
	            	<fieldset>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_display_in_posts" 
		                    	onclick="e=getElementsByName('FB_ILIKE_BUTTON_display_in_posts_on_front_page')[0];if(!this.checked){e.checked=false;e.disabled=true}else{e.checked=true;e.disabled=false}"
		                        onchange="e=getElementsByName('FB_ILIKE_BUTTON_display_in_posts_on_front_page')[0];if(!this.checked){e.checked=false;e.disabled=true}else{e.checked=true;e.disabled=false}"
		                        type="checkbox"<?php if(get_option('FB_ILIKE_BUTTON_display_in_posts')!='-1') echo ' checked="checked"'; ?> value="1"/>
		                	Display I Like button on posts
		                </label><br/>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_display_in_posts_on_front_page" type="checkbox"<?php 
								if(get_option('FB_ILIKE_BUTTON_display_in_posts_on_front_page')!='-1') echo ' checked="checked"';
								if(get_option('FB_ILIKE_BUTTON_display_in_posts')=='-1') echo ' disabled="disabled"';
								?> value="1"/>
		                    Display I Like button on the posts the front page
						</label><br/>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_display_in_pages" type="checkbox"<?php if(get_option('FB_ILIKE_BUTTON_display_in_pages')!='-1') echo ' checked="checked"'; ?> value="1"/>
		                    Display I Like button on pages
						</label>
	            	</fieldset>
	            </td>
	         </tr>
	         <tr valign="top">
	            <th scope="row">Disposition</th>
	            <td>
	            	<fieldset>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_disposition" type="radio" <?php if(get_option('FB_ILIKE_BUTTON_disposition')=='top') echo ' checked="checked"'; ?> value="top"/>
		                	Display at the top of the page/post
		                </label><br/>
		                <label>
		                	<input name="FB_ILIKE_BUTTON_disposition" type="radio" <?php if(get_option('FB_ILIKE_BUTTON_disposition')=='bottom' || get_option('FB_ILIKE_BUTTON_disposition')!='top') echo ' checked="checked"'; ?> value="bottom"/>
		                	Display at the bottom of the page/post
		                </label>
	            	</fieldset>
	            </td>
	         </tr>
	         <tr valign="top">
	            <th scope="row">Layout Options</th>
	            <td>
	            	<fieldset>
	            		<label>
	            		<select name="FB_ILIKE_BUTTON_layout_style">
	            			<option value="standard" <?php if(get_option('FB_ILIKE_BUTTON_layout_style')=='standard' || get_option('FB_ILIKE_BUTTON_layout_style')!='button_count') echo ' selected'; ?>>standard</option>
	            			<option value="button_count" <?php if(get_option('FB_ILIKE_BUTTON_layout_style')=='button_count') echo ' selected'; ?>>button count</option>
	            		</select>
	            		Style
	            		</label><br/>
	            		<label>
	            		<input name="FB_ILIKE_BUTTON_show_faces" 
		                        type="checkbox"<?php if(get_option('FB_ILIKE_BUTTON_show_faces')!='false') echo ' checked="checked"'; ?> value="true"/>
		                	Show faces
		                </label><br/>
		                <label>
		                	<?php 
		                	if(get_option('FB_ILIKE_BUTTON_width')=='') $_fb_width = '450';
		                		else $_fb_width = get_option('FB_ILIKE_BUTTON_width');
							
		                	if(get_option('FB_ILIKE_BUTTON_height')=='') $_fb_height = '60';
		                		else $_fb_height = get_option('FB_ILIKE_BUTTON_height');
		                	?>
		               		<input size="4" name="FB_ILIKE_BUTTON_width" type="text" value="<?=$_fb_width?>" />px
		               		&nbsp;&nbsp;X&nbsp;&nbsp;
		               		<input size="4" name="FB_ILIKE_BUTTON_height" type="text" value="<?=$_fb_height?>" />px
		               		&nbsp;&nbsp;&nbsp;&nbsp; Width x Height
		                </label><br/>
		                <label>
		                	<?php 
		                	if(get_option('FB_ILIKE_BUTTON_div_style')=='') $_fb_div_sytle = 'float:left;margin-right:20px;';
		                		else $_fb_div_sytle = get_option('FB_ILIKE_BUTTON_div_style');
							
		                	?>
		                	<input size="32" name="FB_ILIKE_BUTTON_div_style" type="text" value="<?=$_fb_div_sytle?>" />
		                	Container Style &raquo; the container id is #fbilike so you could also style the container using css
		                </label><br/>
		                <label>
	            		<select name="FB_ILIKE_BUTTON_action">
	            			<option value="like" <?php if(get_option('FB_ILIKE_BUTTON_action')=='like' || get_option('FB_ILIKE_BUTTON_action')!='recommend') echo ' selected'; ?>>like</option>
	            			<option value="recommend" <?php if(get_option('FB_ILIKE_BUTTON_action')=='recommend') echo ' selected'; ?>>recommend</option>
	            		</select>
	            		Verb to display
	            		</label><br/>
	            		<label>
	            		<select name="FB_ILIKE_BUTTON_colorscheme">
	            			<option value="light" <?php if(get_option('FB_ILIKE_BUTTON_colorscheme')=='light' || get_option('FB_ILIKE_BUTTON_colorscheme')!='dark') echo ' selected'; ?>>light</option>
	            			<option value="dark" <?php if(get_option('FB_ILIKE_BUTTON_colorscheme')=='dark') echo ' selected'; ?>>dark</option>
	            		</select>
	            		Color Scheme
	            		</label><br/>
	            		<?php 
	            		$_fb_fonts = array(
	            			'tahoma'=>'tahoma',
	            			'arial'=>'arial',
	            			'verdana'=>'verdana',
	            			'lucida+grande'=>'lucida grande',
	            			'segoe+ui'=>'segoe ui',
	            			'trebuchet+ms'=>'trebuchet ms'
	            			);
	            			if(get_option('FB_ILIKE_BUTTON_font')=='') $_fb_font = 'verdana';
	            			else $_fb_font = get_option('FB_ILIKE_BUTTON_font');
	            		?>
	            		<label>
	            		<select name="FB_ILIKE_BUTTON_font">
	            			<?php 
	            			foreach ($_fb_fonts as $k=>$v) {
	            				?>
	            				<option value="<?=$k?>" <?php if($_fb_font==$k) echo ' selected'; ?>><?=$v?></option>
	            				<?php
	            			}
	            			?>
	            		</select>
	            		Font
	            		</label><br/>
	            	</fieldset>
	            </td>
	         </tr>
	         
	         </table>
	        <p class="submit">
	            <input type="submit" name="Submit" value="<?php _e('Save Changes', 'FB_ILIKE_BUTTON_trans_domain' ) ?>" />
	        </p>
	    </form>
    </div>
<?php
}


function FB_ILIKE_BUTTON_plugin_menu() {
	if( current_user_can('manage_options') ) {
		add_options_page(
			'Facebook OpenGraph: I Like Button Settings'
			, 'FB I Like Button'
			, 'administrator' 
			, basename(__FILE__)
			, 'FB_ILIKE_BUTTON_options_page'
		);
	}
}

add_action('admin_menu', 'FB_ILIKE_BUTTON_plugin_menu');
?>