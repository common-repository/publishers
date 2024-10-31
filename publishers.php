<?php
/*
Plugin Name: Publishers
Plugin URI: https://calmestghost.com/publishers
Description: Companion plugin for the Publishers theme: https://wordpress.org/themes/publishers/.
Version: 1.0.1
Requires at least: 5.0
Author: Bryan Hadaway
Author URI: https://calmestghost.com/
License: GPL
License URI: https://www.gnu.org/licenses/gpl.html
*/

// deny direct access
if ( !defined( 'ABSPATH' ) ) {
	http_response_code( 404 );
	die();
}

// enqueue styles and scripts
add_action( 'wp_enqueue_scripts', 'publishers_enqueues' );
function publishers_enqueues() {
	wp_register_script( 'jquery-cookie', plugin_dir_url( __FILE__ ) . '/js/jquery-cookie.js' );
	wp_enqueue_script( 'jquery-cookie' );
}

// add SEO, social media, and schema tags
add_action( 'wp_head', 'publishers_head' );
function publishers_head() {
	?>
	<meta name="description" content="<?php bloginfo( 'description' ); ?>" />
	<meta name="keywords" content="<?php echo implode( ', ', wp_get_post_tags( get_the_ID(), array( 'fields' => 'names' ) ) ); ?>" />
	<meta property="og:image" content="<?php if ( has_post_thumbnail() ) { the_post_thumbnail_url( 'full' ); } ?>" />
	<meta name="twitter:card" content="photo" />
	<meta name="twitter:site" content="<?php bloginfo( 'name' ); ?>" />
	<meta name="twitter:title" content="<?php the_title(); ?>" />
	<meta name="twitter:description" content="<?php echo wp_strip_all_tags( get_the_excerpt(), true ); ?>" />
	<meta name="twitter:image" content="<?php if ( has_post_thumbnail() ) { the_post_thumbnail_url( 'full' ); } ?>" />
	<meta name="twitter:url" content="<?php the_permalink(); ?>" />
	<meta name="twitter:widgets:theme" content="light" />
	<meta name="twitter:widgets:link-color" content="blue" />
	<meta name="twitter:widgets:border-color" content="#fff" />
	<script type="application/ld+json"> 
	{
	"@context": "https://www.schema.org/",
	"@type": "WebSite",
	"name": "<?php bloginfo( 'name' ); ?>",
	"url": "<?php echo esc_url( home_url() ); ?>/"
	}
	</script>
	<script type="application/ld+json"> 
	{
	"@context": "https://www.schema.org/",
	"@type": "Organization",
	"name": "<?php bloginfo( 'name' ); ?>",
	"url": "<?php echo esc_url( home_url() ); ?>/",
	"logo": "<?php if ( has_custom_logo() ) { $custom_logo_id = get_theme_mod( 'custom_logo' ); $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' ); echo esc_url( $logo[0] ); } ?>",
	"image": "<?php if ( has_site_icon() ) { echo get_site_icon_url(); } ?>",
	"description": "<?php bloginfo( 'description' ); ?>"
	}
	</script>
	<?php
}

// add footer code
add_action( 'wp_footer', 'publishers_foot' );
function publishers_foot() {
	?>
	<script>
	jQuery(document).ready(function($) {
	if ($.cookie("dark-mode") == "yes") {
	$("body").addClass("dark-mode");
	}
	$(".lights").on("keypress click", function(e) {
	if (e.which == 13 || e.type === "click") {
	e.preventDefault();
	$("body").toggleClass("dark-mode");
	$.cookie("dark-mode", $("body").hasClass("dark-mode") ? "yes" : "no", { path: "/" });
	}
	});
	});
	</script>
	<?php
}

// extend where shortcodes can be used
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'term_description', 'do_shortcode' );

// non-member shortcode [non-member]...[/non-member]
add_shortcode( 'non-member', 'publishers_nonmember_check_shortcode' );
function publishers_nonmember_check_shortcode( $atts, $content = null ) {
	if ( !is_user_logged_in() && !is_null( $content ) && !is_feed() )
	return do_shortcode( $content );
	return '';
}

// member shortcode [member]...[/member]
add_shortcode( 'member', 'publishers_member_check_shortcode' );
function publishers_member_check_shortcode( $atts, $content = null ) {
	if ( is_user_logged_in() && !is_null( $content ) && !is_feed() )
	return do_shortcode( $content );
	return __( '<p>You must be <a href="<?php echo esc_url( home_url() ); ?>/login/">logged in</a> to perform this action.</p>', 'publishers' );
}

// access shortcode [access]...[/access]
add_shortcode( 'access', 'publishers_access_check_shortcode' );
function publishers_access_check_shortcode( $attr, $content = null ) {
	extract( shortcode_atts( array( 'capability' => 'read' ), $attr ) );
	if ( current_user_can( $capability ) && !is_null( $content ) && !is_feed() )
	return $content;
	return '';
}

// share buttons shortcode [share]
add_shortcode( 'share', 'publishers_share_shortcode' );
function publishers_share_shortcode() {
	return '
		<div class="share">
			<a href="https://www.facebook.com/sharer/sharer.php?t=' . get_the_title() . '&u=' . get_permalink() . '" title="Share on Facebook" class="facebook" target="_blank"><span class="icon"><svg viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg"><path fill="currentColor" d="m22.676 0h-21.352c-.731 0-1.324.593-1.324 1.324v21.352c0 .732.593 1.324 1.324 1.324h11.494v-9.294h-3.129v-3.621h3.129v-2.675c0-3.099 1.894-4.785 4.659-4.785 1.325 0 2.464.097 2.796.141v3.24h-1.921c-1.5 0-1.792.721-1.792 1.771v2.311h3.584l-.465 3.63h-3.119v9.282h6.115c.733 0 1.325-.592 1.325-1.324v-21.352c0-.731-.592-1.324-1.324-1.324" /></svg></span></a>
			<a href="https://twitter.com/intent/tweet?text=' . get_the_title() . '&url=' . get_permalink() . '" title="Share on Twitter" class="twitter" target="_blank"><span class="icon"><svg viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg"><path fill="currentColor" d="m23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124-4.09-.193-7.715-2.157-10.141-5.126-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548z" /></svg></span></a>
			<a href="mailto:?subject=' . get_the_title() . '&body=' . get_permalink() . '" title="Share over Email" class="email" target="_blank"><span class="icon"><svg viewBox="0 0 24 24" xmlns="https://www.w3.org/2000/svg"><path fill="currentColor" d="M21.386 2.614H2.614A2.345 2.345 0 0 0 .279 4.961l-.01 14.078a2.353 2.353 0 0 0 2.346 2.347h18.771a2.354 2.354 0 0 0 2.347-2.347V4.961a2.356 2.356 0 0 0-2.347-2.347zm0 4.694L12 13.174 2.614 7.308V4.961L12 10.827l9.386-5.866v2.347z" /></svg></span></a>
			<a href="javascript:window.print()" title="Print" class="print"><span class="icon"><svg viewbox="0 0 24 24" xmlns="https://www.w3.org/2000/svg"><path fill="currentColor" d="M18,3H6V7H18M19,12A1,1 0 0,1 18,11A1,1 0 0,1 19,10A1,1 0 0,1 20,11A1,1 0 0,1 19,12M16,19H8V14H16M19,8H5A3,3 0 0,0 2,11V17H6V21H18V17H22V11A3,3 0 0,0 19,8Z" /></svg></span></a>
		</div>
		<style>
		.share, .share *{box-sizing:border-box;-webkit-tap-highlight-color:transparent;transition:all 0.5s ease;padding:0;border:0;margin:0}
		.share{font-size:0;margin:30px auto}
		.share a{display:inline-block;width:25%;font-family:arial;font-size:16px !important;color:#fff !important;text-align:center;text-decoration:none;line-height:0;padding:15px 0;background:#000}
		.share a.facebook{background:#3B5998}
		.share a.twitter{background:#1DA1F2}
		.share a.email{background:#222}
		.share a.print{background:#7f7f7f}
		.share a:hover, .share a:focus{opacity:0.8}
		.share .icon{display:inline-block;width:20px;height:20px}
		.share .text{position:relative;top:-4px;margin-left:10px}
		</style>
	';
}

// extend user profile fields
add_filter( 'user_contactmethods', 'publishers_modify_contact_methods' );
function publishers_modify_contact_methods( $profile_fields ) {
	$profile_fields['publicemail'] = 'Public Email';
	$profile_fields['facebook']	   = 'Facebook URL';
	$profile_fields['twitter']	   = 'Twitter URL';
	$profile_fields['instagram']   = 'Instagram URL';
	$profile_fields['pinterest']   = 'Pinterest URL';
	$profile_fields['twitch']	   = 'Twitch URL';
	$profile_fields['youtube']     = 'YouTube URL';
	unset( $profile_fields['googleplus'] );
	unset( $profile_fields['yim'] );
	unset( $profile_fields['jabber'] );
	unset( $profile_fields['aim'] );
	return $profile_fields;
}

// add user role class
add_action( 'init', function() {
	if ( is_user_logged_in() ) {
		add_filter( 'body_class', 'publishers_role_class' );
		add_filter( 'admin_body_class', 'publishers_role_class' );
	}
});
function publishers_role_class( $classes ) {
	$current_user = new WP_User( get_current_user_id() );
	$user_role = array_shift( $current_user -> roles );
	if ( is_admin() ) {
		$classes .= 'role-' . $user_role;
	} else {
		$classes[] = 'role-' . $user_role;
	}
	return $classes;
}

// enhance post editor
add_action( 'media_buttons', 'publishers_youtube_button', 15 );
function publishers_youtube_button() {
	echo '<a href="' . plugin_dir_url( __FILE__ ) . 'embeds/youtube.php" title="' . __( 'Add YouTube', 'publishers' ) . '" target="_blank" id="insert-youtube-button" class="button" style="padding:0 7px"><span class="dashicons dashicons-video-alt3" style="line-height:16px;vertical-align:middle"></span>' . __( ' Add YouTube', 'publishers' ) . '</a>';
}
add_action( 'media_buttons', 'publishers_giphy_button', 16 );
function publishers_giphy_button() {
	echo '<a href="' . plugin_dir_url( __FILE__ ) . 'embeds/giphy.php" title="' . __( 'Add GIPHY', 'publishers' ) . '" target="_blank" id="insert-giphy-button" class="button" style="padding:0 7px"><span class="dashicons dashicons-images-alt2" style="line-height:16px;vertical-align:middle"></span>' . __( ' Add GIPHY', 'publishers' ) . '</a>';
}
add_action( 'admin_print_footer_scripts', 'publishers_add_quicktags', 100 );
function publishers_add_quicktags() {
	if ( wp_script_is( 'quicktags' ) ) {
		?>
		<script>
		QTags.addButton( 'eg_h2', 'h2', '<h2>', '</h2>', 'h2', 'Header two', 30 );
		QTags.addButton( 'eg_h3', 'h3', '<h3>', '</h3>', 'h3', 'Header three', 31 );
		QTags.addButton( 'eg_big', 'big', '<big>', '</big>', 'big', 'Big text', 32 );
		QTags.addButton( 'eg_small', 'small', '<small>', '</small>', 'small', 'Small text', 33 );
		QTags.addButton( 'eg_hr', 'hr', '<hr />', '', 'hr', 'Horizontal rule', 100 );
		QTags.addButton( 'eg_hrs', 'hr special', '<hr class="special" />', '', 'hrs', 'Special horizontal rule', 101 );
		</script>
		<?php
	}
}
add_filter( 'quicktags_settings', 'publishers_quicktags', 10, 2 );
function publishers_quicktags( $qtInit, $editor_id = 'content' ) {
	$qtInit['buttons'] = 'strong,em,link,block,del,ins,ul,ol,li';
	return $qtInit;
}

// add subtitle option to posts
if ( is_admin() ) {
	add_action( 'edit_form_after_title', 'publishers_move_subtitle' );
	function publishers_move_subtitle() {
		global $post, $wp_meta_boxes;
		do_meta_boxes( get_current_screen(), 'subtitle', $post );
		unset( $wp_meta_boxes['post']['subtitle'] );
	}
	add_action( 'add_meta_boxes', 'publishers_meta_box_add' );
	function publishers_meta_box_add() {
		add_meta_box( 'subtitle', 'Subtitle', 'publishers_post_meta_box', 'post', 'subtitle', 'high' );
	}
	function publishers_post_meta_box( $post ) {
		$values = get_post_custom( $post->ID );
		if ( isset( $values['publishers_custom'] ) ) {
			$publishers_custom_subtitle = esc_html( $values['publishers_custom'][0] );
		}
		wp_nonce_field( 'publishers_meta_box_nonce', 'meta_box_nonce' );
		?>
		<style>
		#post #subtitle{background:none;border:0;box-shadow:none}
		#post #subtitle .inside{padding:0;margin:0}
		#post #subtitle button, #post #subtitle h2{display:none}
		#post #wp-content-editor-tools{padding-top:0}
		#subtitle input{width:100%}
		</style>
		<p><input name="publishers_custom" type="text" placeholder="<?php _e( 'Add subtitle (optional)', 'publishers' ); ?>" value="<?php if ( $publishers_custom = get_post_meta( $post->ID, 'publishers_custom', true ) ) { echo esc_html( $publishers_custom_subtitle ); } ?>" /></p>
		<?php
	}
	add_action( 'save_post', 'publishers_meta_box_save' );
	function publishers_meta_box_save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'publishers_meta_box_nonce' ) ) return;
		if ( !current_user_can( 'edit_post', $post_id ) ) return;
		if ( isset( $_POST['publishers_custom'] ) ) {
			$publishers_custom = sanitize_text_field( $_POST['publishers_custom'] );
			update_post_meta( $post_id, 'publishers_custom', $publishers_custom );
		}
	}
}

// add ability to feature posts on homepage
add_action( 'init', function() {
	if ( current_user_can( 'publish_posts' ) ) {
		add_action( 'add_meta_boxes', 'publishers_featured_meta' );
		function publishers_featured_meta() {
			add_meta_box( 'featured', __( 'Featured Posts', 'publishers' ), 'publishers_meta_callback', 'post' );
		}
		function publishers_meta_callback( $post ) {
			$featured = get_post_meta( $post->ID );
			?>
			<p><label><input type="checkbox" name="meta-checkbox" id="meta-checkbox" value="yes" <?php if ( isset( $featured['meta-checkbox'] ) ) checked( $featured['meta-checkbox'][0], 'yes' ); ?> /> <?php _e( 'Feature this post?', 'publishers' )?></label></p>
			<?php
		}
		add_action( 'save_post', 'publishers_meta_save' );
		function publishers_meta_save( $post_id ) {
			$is_autosave = wp_is_post_autosave( $post_id );
			$is_revision = wp_is_post_revision( $post_id );
			$is_valid_nonce = ( isset( $_POST[ 'publishers_nonce' ] ) && wp_verify_nonce( $_POST[ 'publishers_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
			if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
				return;
			}
			if ( isset( $_POST[ 'meta-checkbox' ] ) ) {
				update_post_meta( $post_id, 'meta-checkbox', 'yes' );
			} else {
				update_post_meta( $post_id, 'meta-checkbox', '' );
			}
		}
	}
});

// add editor notes to post editor
add_action( 'add_meta_boxes', 'publishers_add_notes_box' );
function publishers_add_notes_box() {
	$screens = ['post'];
	foreach ( $screens as $screen ) {
		add_meta_box( 'publishers_notes', __( 'Editor Notes', 'publishers' ), 'publishers_notes_box_html', $screen, 'side', 'high' );
	}
}
function publishers_notes_box_html( $post ) {
	?>
	<div id="inside">
		<p><a href="<?php echo esc_url( home_url() ); ?>/guide/" target="_blank"><?php _e( 'Guide', 'publishers' ); ?></a> // <a href="mailto:<?php echo get_bloginfo( 'admin_email' ); ?>" target="_blank"><?php _e( 'Need Help?', 'publishers' ); ?></a></p>
		<style>
		#titlediv .inside:before{display:block;color:#666;font-style:italic;white-space:pre;content:'<?php _e( 'Use title case', 'publishers' ); ?>'}
		#submitdiv .inside:after{display:block;color:#666;font-style:italic;white-space:pre;padding:0 10px 10px;background:#f5f5f5;content:'<?php _e( 'Publishing can take time, please be patient \A Never draft a post that is already live', 'publishers' ); ?>'}
		#tagsdiv-post_tag .howto:after, .components-form-token-field:after{display:block;color:#666;font-style:italic;white-space:pre;content:'<?php _e( 'Use all lowercase', 'publishers' ); ?>'}
		#postimagediv .inside:after, .editor-post-featured-image:after{display:block;color:#666;font-style:italic;white-space:pre;content:'<?php _e( '1920x1080 pixels required \A Filename should be all lowercase \A Separate keywords with a hyphen', 'publishers' ); ?>'}
		#tagsdiv-post_tag .howto{font-style:italic}
		.components-form-token-field:after, .editor-post-featured-image:after{margin-top:10px}
		</style>
		<script>
		jQuery(document).ready(function ($) {
			$('#new-tag-post_tag, #components-form-token-input-0').keyup(function() {
				this.value = this.value.toLowerCase();
			});
		});
		</script>
	</div>
	<?php
}

// automatically add titles and alts for uploaded images
add_action( 'add_attachment', 'publishers_add_image_alt' );
function publishers_add_image_alt( $post_ID ) {
	if ( wp_attachment_is_image( $post_ID ) ) {
		$image_title = get_post( $post_ID )->post_title;
		$image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ', $image_title );
		$image_title = ucwords( strtolower( $image_title ) );
		$image_meta  = array(
			'ID' => $post_ID,
			'post_title' => $image_title,
		);
		$except = array( 'a', 'an', 'the', 'for', 'and', 'nor', 'but', 'or', 'yet', 'so', 'such', 'as', 'at', 'around', 'by', 'after', 'along', 'for', 'from', 'of', 'on', 'to', 'with', 'without' );
		update_post_meta( $post_ID, '_wp_attachment_image_alt', sanitize_text_field( $image_title ) );
		wp_update_post( $image_meta );
	}
}

// allow HTML in term descriptions
foreach ( array( 'pre_term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_filter_kses' );
}
foreach ( array( 'term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_kses_data' );
}

// deny contributors from deleting files
add_action( 'init', function() {
	if ( current_user_can( 'contributor' ) && !current_user_can( 'upload_files' ) ) {
		add_action( 'admin_init', 'publishers_allow_contributor_uploads' );
		function publishers_allow_contributor_uploads() {
			$contributor = get_role( 'contributor' );
			$contributor -> add_cap( 'upload_files' );
		}
	}
	if ( current_user_can( 'contributor' ) ) {
		add_action( 'delete_attachment', 'publishers_disallow_contributor_delete', 11, 1 );
		function publishers_disallow_contributor_delete( $postID ) {
			exit( __( '<script>alert("You do not have permission to delete files.")</script>', 'publishers' ) );
		}
	}
});

// email notifications for pending posts
add_action( 'future_to_pending', 'publishers_pending_email' );
add_action( 'new_to_pending', 'publishers_pending_email' );
add_action( 'draft_to_pending', 'publishers_pending_email' );
add_action( 'auto-draft_to_pending', 'publishers_pending_email' );
function publishers_pending_email( $post ) {
	$email   = get_option( 'admin_email' );
	$title   = wp_strip_all_tags( get_the_title( $post->ID ) );
	$url 	 = get_permalink( $post->ID );
	$message = "{$url}";
	wp_mail( $email, __( "New Pending Post: {$title}", "publishers" ), $message );
}
add_action( 'init', function() {
	if ( current_user_can( 'administrator' ) ) {
		add_action( 'pending_to_publish', 'publishers_pending_approved' );
		function publishers_pending_approved( $post ) {
			$author  = get_userdata( $post->post_author );
			$email   = $author->user_email;
			$title   = wp_strip_all_tags( get_the_title( $post->ID ) );
			$url 	 = get_permalink( $post->ID );
			$message = "{$url}";
			wp_mail( $email, __( "Your Post Has Been Approved: {$title}", "publishers" ), $message );
		}
		add_action( 'pending_to_trash', 'publishers_pending_declined' );
		add_action( 'pending_to_draft', 'publishers_pending_declined' );
		function publishers_pending_declined( $post ) {
			$author  = get_userdata( $post->post_author );
			$email   = $author->user_email;
			$title   = wp_strip_all_tags( get_the_title( $post->ID ) );
			$message = __( "Sorry, your post has been declined. If you would like to request more information on why it was declined or purchase a sponsored post, you may respond to this email. Thank you.", "publishers" );
			wp_mail( $email, __( "Your Post Has Been Declined: {$title}", "publishers" ), $message );
		}
	}
});

// clean up admin and style
add_action( 'admin_head', 'publishers_admin_styles' );
function publishers_admin_styles() {
	echo '<style>
	#wp-admin-bar-wp-logo,
	#contextual-help-link-wrap,
	#wpfooter,
	form#your-profile > h2:first-of-type,
	form#your-profile table[role="presentation"]:first-of-type,
	label[for="commentstatusdiv-hide"],
	label[for="classic-editor-switch-editor-hide"],
	#commentstatusdiv,
	#classic-editor-switch-editor,
	form.compat-item {
		display:none !important;
	}
	</style>';
}
add_action( 'admin_init', 'publishers_remove_dashboard_meta' );
function publishers_remove_dashboard_meta() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_meta_box( 'commentsdiv', 'post', 'normal' );
	remove_meta_box( 'postcustom', 'post', 'normal' );
	remove_meta_box( 'postexcerpt', 'post', 'normal' );
	remove_meta_box( 'revisionsdiv', 'post', 'normal' );
	remove_meta_box( 'slugdiv', 'post', 'normal' );
	remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
	remove_meta_box( 'classic-editor-switch-editor', 'post', 'normal' );
}

// rewrite /author/ base to /writer/
add_action( 'init', 'publishers_author_base' );
function publishers_author_base() {
	global $wp_rewrite;
	$wp_rewrite->author_base = 'writer';
	$wp_rewrite->author_structure = '/' . $wp_rewrite->author_base . '/%author%';
}

// better image handling for new uploads
add_filter( 'wp_lazy_loading_enabled', '__return_false' );
add_filter( 'big_image_size_threshold', '__return_false' );
add_filter( 'max_srcset_image_width', 'publishers_disable_responsive_images' );
function publishers_disable_responsive_images() {
	return 1;
}
add_filter( 'intermediate_image_sizes_advanced', 'publishers_image_insert_override' );
function publishers_image_insert_override( $sizes ) {
	unset( $sizes['medium_large'] );
	unset( $sizes['1536x1536'] );
	unset( $sizes['2048x2048'] );
	return $sizes;
}

// add featured image to RSS feed
add_action( 'rss2_item', 'publishers_add_rss_image' );
function publishers_add_rss_image() {
	global $post;
	$output = '';
	if ( has_post_thumbnail( $post->ID ) ) {
		$thumbnail_ID = get_post_thumbnail_id( $post->ID, 'full' );
		$thumbnail = wp_get_attachment_image_src( $thumbnail_ID, 'full' );
		$output .= '<media:content xmlns:media="http://search.yahoo.com/mrss/" medium="image" type="image/jpeg"';
		$output .= ' url="'. $thumbnail[0] .'"';
		$output .= ' width="'. $thumbnail[1] .'"';
		$output .= ' height="'. $thumbnail[2] .'"';
		$output .= ' />';
	}
	echo $output;
}

// add IP address column to Users admin page
new PublishersIP();
class PublishersIP {
	public function __construct() {
		add_action( 'init', array( &$this, 'init' ) );
	}
	public function init() {
		add_action( 'user_register', array( $this, 'log_ip' ) );
		add_action( 'manage_users_custom_column', array( $this, 'manage_users_custom_column' ), 10, 3 );
		add_filter( 'pre_get_users', array( $this, 'columns_sortability' ), 10, 2 );
		add_filter( 'manage_users_sortable_columns', array( $this, 'manage_users_sortable_columns' ) );
		if ( is_multisite() ) {
			add_filter( 'wpmu_users_columns', array( $this, 'column_header_signup_ip' ) );
		} else {
			add_filter( 'manage_users_columns', array( $this, 'column_header_signup_ip' ) );
		}
	}
	public function log_ip( $user_id ) {
		$ip = $_SERVER['REMOTE_ADDR'];
		if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$http_x_headers = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
			$ip             = sanitize_text_field( $http_x_headers[0] );
		}
		update_user_meta( $user_id, 'signup_ip', $ip );
	}
	public function column_header_signup_ip( $column_headers ) {
		$column_headers['signup_ip'] = __( 'IP Address', 'publishers' );
		return $column_headers;
	}
	public function manage_users_sortable_columns( $columns ) {
		$columns['signup_ip'] = 'signup_ip';
		return $columns;
	}
	public function columns_sortability( $query ) {
		if ( 'signup_ip' == $query->get( 'orderby' ) ) {
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', 'signup_ip' );
		}
	}
	public function manage_users_custom_column( $value, $column_name, $user_id ) {
		if ( $column_name == 'signup_ip' ) {
			$ip    = get_user_meta( $user_id, 'signup_ip', true );
			$value = '<em>' . __( 'No IP Recorded', 'publishers' ) . '</em>';
			if ( isset( $ip ) && '' !== $ip && 'none' !== $ip ) {
				$value = $ip;
				if ( has_filter( 'ripm_show_ip' ) ) {
					$value = apply_filters( 'ripm_show_ip', $value );
				}
			} else {
				update_user_meta( $user_id, 'signup_ip', 'none' );
			}
		}
		return $value;
	}
}

// automatically log in new users
add_action( 'user_register', 'publishers_auto_login_new_user', 100 );
function publishers_auto_login_new_user( $user_id ) {
	if ( !current_user_can( 'administrator' ) ) {
		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id );
		wp_redirect( esc_url( home_url() ) . '/wp-admin/' );
		exit;
	}
}

// add registration date column to Users admin page
new PublishersRegDate();
class PublishersRegDate {
	public function __construct() {
		add_action( 'init', array( &$this, 'init' ) );
	}
	public function init() {
		add_filter( 'manage_users_columns', array( $this, 'users_columns' ) );
		add_action( 'manage_users_custom_column', array( $this, 'users_custom_column' ), 10, 3 );
		add_filter( 'manage_users_sortable_columns', array( $this, 'users_sortable_columns' ) );
		add_filter( 'request', array( $this, 'users_orderby_column' ) );
	}
	public static function users_columns( $columns ) {
		$columns['registerdate'] = _x( 'Registered', 'user', 'publishers' );
		return $columns;
	}
	public static function users_custom_column( $value, $column_name, $user_id ) {
		global $mode;
		$mode = empty( $_REQUEST['mode'] ) ? 'list' : $_REQUEST['mode'];
		if ( 'registerdate' != $column_name ) {
			return $value;
		} else {
			$user = get_userdata( $user_id );
			if ( is_multisite() && ( 'list' == $mode ) ) {
				$formatted_date = 'F jS, Y';
			} else {
				$formatted_date = 'F jS, Y \a\t g:i a';
			}
			$registered = strtotime( get_date_from_gmt( $user->user_registered ) );
			$registerdate = '<span>' . date_i18n( $formatted_date, $registered ) . '</span>' ;
			return $registerdate;
		}
	}
	public static function users_sortable_columns( $columns ) {
		$custom = array(
			'registerdate' => 'registered',
		);
		return wp_parse_args( $custom, $columns );
	}
	public static function users_orderby_column( $vars ) {
		if ( isset( $vars['orderby'] ) && 'registerdate' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'registerdate',
				'orderby' => 'meta_value'
			) );
		}
		return $vars;
	}
}