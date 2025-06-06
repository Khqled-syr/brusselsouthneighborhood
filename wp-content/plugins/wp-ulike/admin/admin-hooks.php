<?php
/**
 * Admin Hooks
 * 
 * @package    wp-ulike
 * @author     TechnoWich 2025
 * @link       https://wpulike.com
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die('No Naughty Business Please !');
}

/**
 * Add WP ULike CopyRight in footer
 *
 * @author       	Alimir
 * @param           string $text
 * @since           2.0
 * @return			string
 */
function wp_ulike_copyright( $text ) {
	if( isset($_GET["page"]) && stripos( sanitize_text_field( wp_unslash( $_GET["page"] ) ), "wp-ulike") !== false ) {
		return sprintf(
			'%s <a href="%s" title="TechnoWich" target="_blank">%s</a>',
			esc_html__( 'Proudly Powered By', 'wp-ulike' ),
			'https://technowich.com/?utm_source=footer-link&utm_campaign=wp-ulike&utm_medium=wp-dash',
			esc_html__( 'TechnoWich', 'wp-ulike' )
		);
	}

	return $text;
}
add_filter( 'admin_footer_text', 'wp_ulike_copyright');


/**
 * Filters a screen option value before it is set.
 *
 * @param string $status
 * @param string $option The option name.
 * @param string $value The number of rows to use.
 * @return string
 */
function wp_ulike_logs_per_page_set_option( $status, $option, $value ) {

	if ( 'wp_ulike_logs_per_page' == $option ) {
		return $value;
	}

	return $status;
}
add_filter( 'set-screen-option', 'wp_ulike_logs_per_page_set_option', 10, 3 );

 /**
  * The Filter is used at the very end of the get_avatar() function
  *
  * @param string $avatar Image tag for the user's avatar.
  * @return string $avatar
  */
function wp_ulike_remove_photo_class($avatar) {
	return str_replace(' photo', ' gravatar', $avatar);
}
add_filter('get_avatar', 'wp_ulike_remove_photo_class');

/**
 * On user logged out
 *
 * @return void
 */
function wp_ulike_on_logout_hook() {
	if ( ! is_super_admin() ) {
		return;
	}
	// Refresh new votes
	wp_ulike_update_meta_data( 1, 'statistics', 'calculate_new_votes', 0 );
}
add_action('wp_logout', 'wp_ulike_on_logout_hook');

/**
 *  Undocumented function
 *
 * @since 3.6.0
 * @param integer $count
 * @return integer $count
 */
function wp_ulike_update_menu_badge_count( $count ) {
	if( 0 !== ( $count_new_likes = wp_ulike_get_number_of_new_likes() ) ){
		$count += $count_new_likes;
	}
	return $count;
}
add_filter( 'wp_ulike_menu_badge_count', 'wp_ulike_update_menu_badge_count' );


/**
 * Update the admin sub menu title
 *
 * @since 3.6.0
 * @param string $title
 * @param string $menu_slug
 * @return string $title
 */
function wp_ulike_update_admin_sub_menu_title( $title, $menu_slug ) {
	if( ( 0 !== ( $count_new_likes = wp_ulike_get_number_of_new_likes() ) ) && $menu_slug === 'wp-ulike-statistics' ){
		$title .=  wp_ulike_badge_count_format( $count_new_likes );
	}
	return $title;
}
add_filter( 'wp_ulike_admin_sub_menu_title', 'wp_ulike_update_admin_sub_menu_title', 10, 2 );

/**
 * Admin notice controller
 *
 * @return void
 */
function wp_ulike_notice_manager(){

	// Display notices only for admin users
	if( !current_user_can( 'manage_options' ) ){
		return;
	}

	$count_logs  = wp_ulike_count_all_logs();
	$screen      = get_current_screen();
	$notice_list = [];

	if( $count_logs > 150 ){
		$notice_list[ 'wp_ulike_leave_a_review' ] = new wp_ulike_notices([
			'id'          => 'wp_ulike_leave_a_review',
			'title'       => esc_html__( 'Congratulations! You\'ve Earned Over a Hundred Likes', 'wp-ulike' ) . ' &#127775;',
			'description' => esc_html__( "It's truly wonderful to witness your active engagement with the WP ULike plugin. We sincerely hope your experience has been nothing short of excellent! If that's the case, could we kindly ask for your valuable input in the form of a positive review? Your constructive feedback not only uplifts and fuels the plugin but also serves as a guiding light for those seeking its benefits. Let's unite in spreading the positive impact and help more individuals uncover the power of WP ULike!" , 'wp-ulike' ),
			'skin'        => 'info',
			'has_close'   => true,
			'buttons'     => array(
				array(
					'label'      => esc_html__( "Sure, I'd love to!", 'wp-ulike' ),
					'link'       => 'https://wordpress.org/support/plugin/wp-ulike/reviews/?filter=5'
				),
				array(
					'label'      => esc_html__('Not Now', 'wp-ulike'),
					'type'       => 'skip',
					'color_name' => 'info',
					'expiration' => WEEK_IN_SECONDS * 2
				),
				array(
					'label'      => esc_html__('No thanks and never ask me again', 'wp-ulike'),
					'type'       => 'skip',
					'color_name' => 'error',
					'expiration' => YEAR_IN_SECONDS * 10
				)
			),
			'image'     => array(
				'width' => '100',
				'src'   => WP_ULIKE_ASSETS_URL . '/img/svg/rating.svg'
			)
		]);
	}

	if( ! defined( 'WP_ULIKE_PRO_VERSION' ) && strpos( $screen->base, WP_ULIKE_SLUG ) !== false ){
		if( get_locale() === 'fa_IR' ){
			$notice_list[ 'wp_ulike_fa_IR_banner' ] = new wp_ulike_notices([
				'id'          => 'wp_ulike_fa_IR_banner',
				'title'       => esc_html__( 'Good news for Persian WordPress users. :)', 'wp-ulike' ),
				'description' => esc_html__( "Following the request of our friends in Persian WordPress to access the premium version in Iran, we made the necessary arrangements and our new website has become available. From now on, you can use our new articles and premium service by visiting this website." , 'wp-ulike' ),
				'skin'        => 'default',
				'has_close'   => true,
				'buttons'     => array(
					array(
						'label'      => esc_html__( "Get More Information", 'wp-ulike' ),
						'link'       => 'https://wpulike.ir/?utm_source=fa-IR-banner&utm_campaign=gopro&utm_medium=wp-dash'
					),
					array(
						'label'      => esc_html__('No thanks and never ask me again', 'wp-ulike'),
						'type'       => 'skip',
						'color_name' => 'error',
						'expiration' => YEAR_IN_SECONDS * 10
					)
				),
				'image'     => array(
					'width' => '100',
					'src'   => WP_ULIKE_ASSETS_URL . '/img/svg/news.svg'
				)
			]);
		}
		$notice_list[ 'wp_ulike_pro_user_profiles_banner' ] = new wp_ulike_notices([
			'id'          => 'wp_ulike_pro_user_profiles_banner',
			'title'       => esc_html__( 'Unlock the Power of WP ULike: Crafting Ultimate User Profiles', 'wp-ulike' ),
			'description' => esc_html__( "Discover the seamless path to curating captivating WordPress user profiles with the WP ULike Profile Builder. This revolutionary tool empowers you to sculpt professional profiles effortlessly, showcasing them on your website's front-end sans the hassle of coding or intricate maneuvers. Unleash the potential and redefine user engagement today!" , 'wp-ulike' ),
			'skin'        => 'default',
			'has_close'   => true,
			'buttons'     => array(
				array(
					'label'      => esc_html__( "Get More Information", 'wp-ulike' ),
					'link'       => WP_ULIKE_PLUGIN_URI . 'blog/wordpress-ultimate-profile-builder/?utm_source=settings-page-banner&utm_campaign=gopro&utm_medium=wp-dash'
				),
				array(
					'label'      => esc_html__('No thanks and never ask me again', 'wp-ulike'),
					'type'       => 'skip',
					'color_name' => 'error',
					'expiration' => YEAR_IN_SECONDS * 10
				)
			),
			'image'     => array(
				'width' => '100',
				'src'   => WP_ULIKE_ASSETS_URL . '/img/svg/profiles.svg'
			)
		]);
		$notice_list[ 'wp_ulike_seo_tools_banner' ] = new wp_ulike_notices([
			'id'          => 'wp_ulike_seo_tools_banner',
			'title'       => esc_html__( 'Elevate Your SEO with Schema Rich Snippets', 'wp-ulike' ),
			'description' => esc_html__( "Empower your website's SEO potential through Schema Rich Snippets in WP ULike Pro. With the inclusion of +13 Schema Markups, your site gains the ability to establish robust connections with search engines. Fluent communication in search engine language allows you to precisely convey your content's nature. Upgrade now for a refined SEO experience!" , 'wp-ulike' ),
			'skin'        => 'default',
			'has_close'   => true,
			'buttons'     => array(
				array(
					'label'      => esc_html__( "Get More Information", 'wp-ulike' ),
					'link'       => WP_ULIKE_PLUGIN_URI . 'blog/wordpress-rich-snippets-generator/?utm_source=seo-tools-banner&utm_campaign=gopro&utm_medium=wp-dash'
				),
				array(
					'label'      => esc_html__('No thanks and never ask me again', 'wp-ulike'),
					'type'       => 'skip',
					'color_name' => 'error',
					'expiration' => YEAR_IN_SECONDS * 10
				)
			),
			'image'     => array(
				'width' => '100',
				'src'   => WP_ULIKE_ASSETS_URL . '/img/svg/seo.svg'
			)
		]);
	}

    $notice_list = apply_filters( 'wp_ulike_admin_notices_instances', $notice_list );

    foreach ( $notice_list as $notice ) {
        if( $notice instanceof wp_ulike_notices ){
            $notice->render();
        }
    }
}
add_action( 'admin_notices', 'wp_ulike_notice_manager' );

/**
 * This makes a new admin menu page to introduce premium version
 *
 * @param array $submenus
 * @return array
 */
function wp_ulike_go_pro_admin_menu( $submenus ){
	if( is_array( $submenus ) && ! defined( 'WP_ULIKE_PRO_VERSION' ) ){
		$submenus['go_pro'] = array(
			'title'       =>  sprintf( '<span class="wp-ulike-gopro-menu-link"><strong>%s</strong></span>', esc_html__( 'Go Pro', 'wp-ulike' )),
			'parent_slug' => 'wp-ulike-settings',
 			'capability'  => 'manage_options',
			'path'        => WP_ULIKE_ADMIN_DIR . '/includes/templates/go-pro.php',
			'menu_slug'   => 'wp-ulike-go-pro',
			'load_screen' => false
		);
	}

	return $submenus;
}
add_filter( 'wp_ulike_admin_pages', 'wp_ulike_go_pro_admin_menu', 10, 1 );

/**
 * Disable admin notices
 * @param array $notice_list
 * @return array|null
 */
function wp_ulike_hide_admin_notifications( $notice_list ){
	$screen = get_current_screen();
	$hide_admin_notice = wp_ulike_get_option( 'disable_admin_notice', false );
	return wp_ulike_is_true( $hide_admin_notice ) && strpos( $screen->base, WP_ULIKE_SLUG ) === false ? array() : $notice_list;
}
add_filter( 'wp_ulike_admin_notices_instances', 'wp_ulike_hide_admin_notifications', 20, 1 );


/**
 * Upgarde old option values
 *
 * @return void
 */
function wp_ulike_upgrade_deprecated_options_value(){

	$is_deprecated_enabled     = wp_ulike_get_option( 'enable_deprecated_options' );
	$deprecated_options_status = get_option( 'wp_ulike_deprecated_options_status', false );

	if( ! wp_ulike_is_true( $is_deprecated_enabled ) || $deprecated_options_status ){
		return;
	}

	$get_general_options    = get_option( 'wp_ulike_general', array() );
	$get_posts_options      = get_option( 'wp_ulike_posts', array() );
	$get_comments_options   = get_option( 'wp_ulike_comments', array() );
	$get_buddypress_options = get_option( 'wp_ulike_buddypress', array() );
	$get_bbpress_options    = get_option( 'wp_ulike_bbpress', array() );
	$get_customize_options  = get_option( 'wp_ulike_customize', array() );

	$final_options_stack    = array();

	// Update general options
	if( !empty( $get_posts_options ) ){
		$final_options_stack = array (
			'enable_kilobyte_format'    => !empty($get_general_options['format_number']) ? $get_general_options['format_number'] : false,
			'enable_toast_notice'       => !empty($get_general_options['notifications']) ? $get_general_options['notifications'] : true,
			'enable_anonymise_ip'       => !empty($get_general_options['anonymise']) ? $get_general_options['anonymise'] : false,
			'disable_admin_notice'      => !empty($get_general_options['hide_admin_notice']) ? $get_general_options['hide_admin_notice'] : true,
			'enable_meta_values'        => !empty($get_general_options['enable_meta_values']) ? $get_general_options['enable_meta_values'] : false,
			'already_registered_notice' => !empty($get_general_options['permission_text']) ? $get_general_options['permission_text'] : 'You have already registered a vote.',
			'login_required_notice'     => !empty($get_general_options['login_text']) ? $get_general_options['permission_text'] : 'You Should Login To Submit Your Like',
			'like_notice'               => !empty($get_general_options['like_notice']) ? $get_general_options['like_notice'] : 'Thanks! You liked This.',
			'unlike_notice'             => !empty($get_general_options['unlike_notice']) ? $get_general_options['unlike_notice'] : 'Sorry! You unliked this.',
			'dislike_notice'            => !empty($get_general_options['dislike_notice']) ? $get_general_options['dislike_notice'] : 'Sorry! You disliked this.',
			'undislike_notice'          => !empty($get_general_options['undislike_notice']) ? $get_general_options['undislike_notice'] : 'Thanks! You undisliked This.',
			'custom_css'                => !empty($get_customize_options['custom_css']) ? $get_customize_options['custom_css'] : '',
			'enable_deprecated_options' => true,
		);
	}

	// Update posts options
	if( !empty( $get_posts_options ) ){
		$final_options_stack['posts_group'] = array (
			'template'    => !empty($get_posts_options['theme']) ? $get_posts_options['theme'] : 'wpulike-default',
			'button_type' => !empty($get_general_options['button_type']) ? $get_general_options['button_type'] : 'image',
			'text_group'  => array (
				'like'      => !empty($get_general_options['button_text']) ? $get_general_options['button_text'] : 'Like',
				'unlike'    => !empty($get_general_options['button_text_u']) ? $get_general_options['button_text_u'] : 'Liked',
				'dislike'   => !empty($get_general_options['dislike_text']) ? $get_general_options['dislike_text'] : 'Dislike',
				'undislike' => !empty($get_general_options['undislike_text']) ? $get_general_options['undislike_text'] : 'Disliked'
			),
			'image_group' => array (
				'like'      => !empty($get_general_options['button_url']) ? $get_general_options['button_url'] : '',
				'unlike'    => !empty($get_general_options['button_url_u']) ? $get_general_options['button_url_u'] : '',
				'dislike'   => !empty($get_general_options['button_texbutton_urlt']) ? $get_general_options['button_url'] : '',
				'undislike' => !empty($get_general_options['button_url_u']) ? $get_general_options['button_url_u'] : '',
			),
			'enable_auto_display'         => !empty($get_posts_options['auto_display']) ? $get_posts_options['auto_display'] : false,
			'auto_display_position'       => !empty($get_posts_options['auto_display_position']) ? $get_posts_options['auto_display_position'] : 'bottom',
			'auto_display_filter'         => !empty($get_posts_options['auto_display_filter']) ? wp_ulike_convert_old_options_array( $get_posts_options['auto_display_filter'] ) : '',
			'logging_method'              => !empty($get_posts_options['logging_method']) ? $get_posts_options['logging_method'] : 'by_username',
			'enable_only_logged_in_users' => !empty($get_posts_options['only_registered_users']) ? $get_posts_options['only_registered_users'] : false,
			'logged_out_display_type'     => !empty($get_general_options['login_type']) ? $get_general_options['login_type'] : 'button',
			'enable_likers_box'           => !empty($get_posts_options['users_liked_box']) ? $get_posts_options['users_liked_box'] : false,
			'disable_likers_pophover'     => !empty($get_posts_options['disable_likers_pophover']) ? $get_posts_options['disable_likers_pophover'] : false,
			'likers_gravatar_size'        => !empty($get_posts_options['users_liked_box_avatar_size']) ? $get_posts_options['users_liked_box_avatar_size'] : 64,
			'likers_count'                => !empty($get_posts_options['number_of_users']) ? $get_posts_options['number_of_users'] : 10,
			'likers_template'             => !empty($get_posts_options['likers_template']) ? $get_posts_options['likers_template'] : '<div class="wp-ulike-likers-list">%START_WHILE%<span class="wp-ulike-liker"><a href="#" title="%USER_NAME%">%USER_AVATAR%</a></span>%END_WHILE%</div>',
		);
	}
	// Update comments options
	if( !empty( $get_comments_options ) ){
		$final_options_stack['comments_group'] = array (
			'template'    => !empty($get_comments_options['theme']) ? $get_comments_options['theme'] : 'wpulike-default',
			'button_type' => !empty($get_general_options['button_type']) ? $get_general_options['button_type'] : 'image',
			'text_group'  => array (
				'like'      => !empty($get_general_options['button_text']) ? $get_general_options['button_text'] : 'Like',
				'unlike'    => !empty($get_general_options['button_text_u']) ? $get_general_options['button_text_u'] : 'Liked',
				'dislike'   => !empty($get_general_options['dislike_text']) ? $get_general_options['dislike_text'] : 'Dislike',
				'undislike' => !empty($get_general_options['undislike_text']) ? $get_general_options['undislike_text'] : 'Disliked'
			),
			'image_group' => array (
				'like'      => !empty($get_general_options['button_url']) ? $get_general_options['button_url'] : '',
				'unlike'    => !empty($get_general_options['button_url_u']) ? $get_general_options['button_url_u'] : '',
				'dislike'   => !empty($get_general_options['button_texbutton_urlt']) ? $get_general_options['button_url'] : '',
				'undislike' => !empty($get_general_options['button_url_u']) ? $get_general_options['button_url_u'] : '',
			),
			'enable_auto_display'         => !empty($get_comments_options['auto_display']) ? $get_comments_options['auto_display'] : false,
			'auto_display_position'       => !empty($get_comments_options['auto_display_position']) ? $get_comments_options['auto_display_position'] : 'bottom',
			'logging_method'              => !empty($get_comments_options['logging_method']) ? $get_comments_options['logging_method'] : 'by_username',
			'enable_only_logged_in_users' => !empty($get_comments_options['only_registered_users']) ? $get_comments_options['only_registered_users'] : false,
			'logged_out_display_type'     => !empty($get_general_options['login_type']) ? $get_general_options['login_type'] : 'button',
			'enable_likers_box'           => !empty($get_comments_options['users_liked_box']) ? $get_comments_options['users_liked_box'] : false,
			'disable_likers_pophover'     => !empty($get_comments_options['disable_likers_pophover']) ? $get_comments_options['disable_likers_pophover'] : false,
			'likers_gravatar_size'        => !empty($get_comments_options['users_liked_box_avatar_size']) ? $get_comments_options['users_liked_box_avatar_size'] : 64,
			'likers_count'                => !empty($get_comments_options['number_of_users']) ? $get_comments_options['number_of_users'] : 10,
			'likers_template'             => !empty($get_comments_options['likers_template']) ? $get_comments_options['likers_template'] : '<div class="wp-ulike-likers-list">%START_WHILE%<span class="wp-ulike-liker"><a href="#" title="%USER_NAME%">%USER_AVATAR%</a></span>%END_WHILE%</div>',
		);
	}
	// Update buddyPress options
	if( !empty( $get_buddypress_options ) ){
		$final_options_stack['buddypress_group'] = array (
			'template'    => !empty($get_buddypress_options['theme']) ? $get_buddypress_options['theme'] : 'wpulike-default',
			'button_type' => !empty($get_general_options['button_type']) ? $get_general_options['button_type'] : 'image',
			'text_group'  => array (
				'like'      => !empty($get_general_options['button_text']) ? $get_general_options['button_text'] : 'Like',
				'unlike'    => !empty($get_general_options['button_text_u']) ? $get_general_options['button_text_u'] : 'Liked',
				'dislike'   => !empty($get_general_options['dislike_text']) ? $get_general_options['dislike_text'] : 'Dislike',
				'undislike' => !empty($get_general_options['undislike_text']) ? $get_general_options['undislike_text'] : 'Disliked'
			),
			'image_group' => array (
				'like'      => !empty($get_general_options['button_url']) ? $get_general_options['button_url'] : '',
				'unlike'    => !empty($get_general_options['button_url_u']) ? $get_general_options['button_url_u'] : '',
				'dislike'   => !empty($get_general_options['button_texbutton_urlt']) ? $get_general_options['button_url'] : '',
				'undislike' => !empty($get_general_options['button_url_u']) ? $get_general_options['button_url_u'] : '',
			),
			'enable_auto_display'            => !empty($get_buddypress_options['auto_display']) ? $get_buddypress_options['auto_display'] : false,
			'auto_display_position'          => !empty($get_buddypress_options['auto_display_position']) ? $get_buddypress_options['auto_display_position'] : 'content',
			'logging_method'                 => !empty($get_buddypress_options['logging_method']) ? $get_buddypress_options['logging_method'] : 'by_username',
			'enable_only_logged_in_users'    => !empty($get_buddypress_options['only_registered_users']) ? $get_buddypress_options['only_registered_users'] : false,
			'logged_out_display_type'        => !empty($get_general_options['login_type']) ? $get_general_options['login_type'] : 'button',
			'enable_likers_box'              => !empty($get_buddypress_options['users_liked_box']) ? $get_buddypress_options['users_liked_box'] : false,
			'disable_likers_pophover'        => !empty($get_buddypress_options['disable_likers_pophover']) ? $get_buddypress_options['disable_likers_pophover'] : false,
			'likers_gravatar_size'           => !empty($get_buddypress_options['users_liked_box_avatar_size']) ? $get_buddypress_options['users_liked_box_avatar_size'] : 64,
			'likers_count'                   => !empty($get_buddypress_options['number_of_users']) ? $get_buddypress_options['number_of_users'] : 10,
			'likers_template'                => !empty($get_buddypress_options['likers_template']) ? $get_buddypress_options['likers_template'] : '<div class="wp-ulike-likers-list">%START_WHILE%<span class="wp-ulike-liker"><a href="#" title="%USER_NAME%">%USER_AVATAR%</a></span>%END_WHILE%</div>',
			'enable_comments'                => !empty($get_buddypress_options['activity_comment']) ? $get_buddypress_options['activity_comment'] : false,
			'enable_add_bp_activity'         => !empty($get_buddypress_options['new_likes_activity']) ? $get_buddypress_options['new_likes_activity'] : false,
			'posts_notification_template'    => !empty($get_buddypress_options['bp_post_activity_add_header']) ? $get_buddypress_options['bp_post_activity_add_header'] : '<strong>%POST_LIKER%</strong> liked <a href="%POST_PERMALINK%" title="%POST_TITLE%">%POST_TITLE%</a>. (So far, This post has <span class="badge">%POST_COUNT%</span> likes)',
			'comments_notification_template' => !empty($get_buddypress_options['bp_comment_activity_add_header']) ? $get_buddypress_options['bp_comment_activity_add_header'] : '<strong>%POST_LIKER%</strong> liked <a href="%POST_PERMALINK%" title="%POST_TITLE%">%POST_TITLE%</a>. (So far, This post has <span class="badge">%POST_COUNT%</span> likes)',
			'enable_add_notification'        => !empty($get_buddypress_options['custom_notification']) ? $get_buddypress_options['custom_notification'] : false
		);
	}
	// Update bbPress options
	if( !empty( $get_bbpress_options ) ){
		$final_options_stack['bbpress_group'] = array (
			'template'    => !empty($get_bbpress_options['theme']) ? $get_bbpress_options['theme'] : 'wpulike-default',
			'button_type' => !empty($get_general_options['button_type']) ? $get_general_options['button_type'] : 'image',
			'text_group'  => array (
				'like'      => !empty($get_general_options['button_text']) ? $get_general_options['button_text'] : 'Like',
				'unlike'    => !empty($get_general_options['button_text_u']) ? $get_general_options['button_text_u'] : 'Liked',
				'dislike'   => !empty($get_general_options['dislike_text']) ? $get_general_options['dislike_text'] : 'Dislike',
				'undislike' => !empty($get_general_options['undislike_text']) ? $get_general_options['undislike_text'] : 'Disliked'
			),
			'image_group' => array (
				'like'      => !empty($get_general_options['button_url']) ? $get_general_options['button_url'] : '',
				'unlike'    => !empty($get_general_options['button_url_u']) ? $get_general_options['button_url_u'] : '',
				'dislike'   => !empty($get_general_options['button_texbutton_urlt']) ? $get_general_options['button_url'] : '',
				'undislike' => !empty($get_general_options['button_url_u']) ? $get_general_options['button_url_u'] : '',
			),
			'enable_auto_display'         => !empty($get_bbpress_options['auto_display']) ? $get_bbpress_options['auto_display'] : false,
			'auto_display_position'       => !empty($get_bbpress_options['auto_display_position']) ? $get_bbpress_options['auto_display_position'] : 'bottom',
			'logging_method'              => !empty($get_bbpress_options['logging_method']) ? $get_bbpress_options['logging_method'] : 'by_username',
			'enable_only_logged_in_users' => !empty($get_bbpress_options['only_registered_users']) ? $get_bbpress_options['only_registered_users'] : false,
			'logged_out_display_type'     => !empty($get_general_options['login_type']) ? $get_general_options['login_type'] : 'button',
			'enable_likers_box'           => !empty($get_bbpress_options['users_liked_box']) ? $get_bbpress_options['users_liked_box'] : false,
			'disable_likers_pophover'     => !empty($get_bbpress_options['disable_likers_pophover']) ? $get_bbpress_options['disable_likers_pophover'] : false,
			'likers_gravatar_size'        => !empty($get_bbpress_options['users_liked_box_avatar_size']) ? $get_bbpress_options['users_liked_box_avatar_size'] : 64,
			'likers_count'                => !empty($get_bbpress_options['number_of_users']) ? $get_bbpress_options['number_of_users'] : 10,
			'likers_template'             => !empty($get_bbpress_options['likers_template']) ? $get_bbpress_options['likers_template'] : '<div class="wp-ulike-likers-list">%START_WHILE%<span class="wp-ulike-liker"><a href="#" title="%USER_NAME%">%USER_AVATAR%</a></span>%END_WHILE%</div>'
		);
	}

	// Update flag option
	update_option( 'wp_ulike_deprecated_options_status', true );
	// Update option values
	update_option( 'wp_ulike_settings', $final_options_stack  );
}
// add_action( 'admin_init', 'wp_ulike_upgrade_deprecated_options_value' );


/**
 * Display custom column content
 *
 * @param   array  		$column
 * @param   integer  	$post_id
 *
 * @return  void
 */
function wp_ulike_manage_posts_custom_column( $column, $post_id ) {
    if ( $column === 'wp-ulike-thumbs-up' ){
		$is_distinct = wp_ulike_setting_repo::isDistinct('post');
		$post_id     = wp_ulike_get_the_id( $post_id );
        echo sprintf( '<span class="wp-ulike-counter-box">%d</span>',  wp_ulike_get_counter_value( $post_id, 'post', 'like', $is_distinct ) );
    }
}
add_action( 'manage_posts_custom_column' , 'wp_ulike_manage_posts_custom_column', 10, 2 );
add_action( 'manage_pages_custom_column' , 'wp_ulike_manage_posts_custom_column', 10, 2 );

/**
 * Add custom column to post list
 *
 * @param   array  $columns
 *
 * @return  array
 */
function wp_ulike_manage_posts_columns( $columns ) {
	// Get settings list
	$post_types = wp_ulike_get_option( 'enable_admin_posts_columns', array() );
	// Get current post type
	$current_post_type = isset( $_GET['post_type'] ) && $_GET['post_type'] === 'page' ? 'page' : get_post_type( wp_ulike_get_the_id() );

	if( ! empty( $post_types ) && false !== $current_post_type ){
		if( in_array( $current_post_type, $post_types ) ){
			$columns = apply_filters( 'wp_ulike_manage_posts_columns', array_merge( $columns,
			array( 'wp-ulike-thumbs-up' => '<i class="dashicons dashicons-thumbs-up"></i> ' . esc_html__('Like','wp-ulike') ) ), $current_post_type );
			// add sortable columns
			add_filter( 'manage_edit-' . $current_post_type . '_sortable_columns', function( $columns ){
				$columns['wp-ulike-thumbs-up'] = 'likes';
				return $columns;
			} );
		}
    }

    return $columns;
}
add_filter( 'manage_posts_columns' , 'wp_ulike_manage_posts_columns', 10 );
add_filter( 'manage_pages_columns' , 'wp_ulike_manage_posts_columns', 10 );


/**
 * Manage the query of sortable columns
 *
 * @param object $query
 * @return void
 */
function wp_ulike_manage_sortable_columns_order( $query ) {
	if ( ! is_admin() ){
		return;
	}

	if ( ! empty( $query->query['orderby'] ) && 'likes' == $query->query['orderby'] ) {
		$post__in = wp_ulike_get_popular_items_ids(array(
			'rel_type' => $query->get('post_type'),
			'status'   => 'like',
			"order"    => $query->get('order'),
			"offset"   => $query->get('paged'),
			"limit"    => $query->get('posts_per_page')
		));

		$query->set( 'offset', 0 );
		$query->set( 'post__in', $post__in );
		$query->set( 'orderby', 'post__in' );
	}

	do_action( 'wp_ulike_manage_sortable_columns_order', $query );
}
add_action( 'pre_get_posts', 'wp_ulike_manage_sortable_columns_order', 10, 1 );

/**
 * Count founded posts on manage columns
 *
 * @param integer $found_posts
 * @param object $query
 * @return integer
 */
function wp_ulike_manage_columns_found_posts( $found_posts, $query ){
	if ( ! is_admin() ){
		return $found_posts;
	}

	if ( ! empty( $query->query['orderby'] ) && 'likes' == $query->query['orderby'] ) {
		$found_posts = wp_ulike_get_popular_items_total_number(array(
			"rel_type" => $query->get('post_type'),
			"status"   => 'like'
		));
	}

	return $found_posts;
}
add_filter( 'found_posts', 'wp_ulike_manage_columns_found_posts', 10, 2 );

/**
 * Add comment columns
 *
 * @param array $columns
 * @return array
 */
function wp_ulike_comment_columns( $columns ) {
	if( wp_ulike_get_option( 'comments_group|enable_admin_columns', false ) ){
		$columns['wp-ulike-thumbs-up'] = '<i class="dashicons dashicons-thumbs-up"></i> ' . esc_html__('Like','wp-ulike');
	}

	return $columns;
}
add_filter( 'manage_edit-comments_columns', 'wp_ulike_comment_columns' );

/**
 * Set sortable columns for comments
 *
 * @param array $columns
 * @return array
 */
function wp_ulike_comments_sortable_columns( $columns ) {
	if( wp_ulike_get_option( 'comments_group|enable_admin_columns', false ) ){
    	$columns['wp-ulike-thumbs-up'] = 'likes';
	}

    return $columns;
}
add_filter( 'manage_edit-comments_sortable_columns', 'wp_ulike_comments_sortable_columns' );

/**
 * Display column content for comment
 *
 * @param string $column
 * @param integer $comment_ID
 * @return void
 */
function wp_ulike_comment_column_content( $column, $comment_ID ) {
    if ( $column === 'wp-ulike-thumbs-up' ){
		$is_distinct = wp_ulike_setting_repo::isDistinct('comment');
        echo sprintf( '<span class="wp-ulike-counter-box">%d</span>',  wp_ulike_get_counter_value( $comment_ID, 'comment', 'like', $is_distinct ) );
    }
}
add_filter( 'manage_comments_custom_column', 'wp_ulike_comment_column_content', 10, 2 );

/**
 * Manage the query of sortable columns for comments
 *
 * @param object $query
 * @return void
 */
function wp_ulike_manage_comment_sortable_columns_order( $query ) {
	if ( ! is_admin() ){
		return;
	}

	if ( ! empty( $query->query_vars['orderby'] ) && 'likes' == $query->query_vars['orderby'] ) {
		$comment__in = wp_ulike_get_popular_items_ids(array(
			'type'     => 'comment',
			'status'   => 'like',
			"order"    => $query->query_vars['order'],
			"offset"   => $query->query_vars['paged'],
			"limit"    => $query->query_vars['number']
		));

		$query->query_vars['comment__in'] = $comment__in;
		$query->query_vars['orderby']     = 'comment__in';
	}

	do_action( 'wp_ulike_manage_comment_sortable_columns_order', $query );
}
add_action( 'pre_get_comments', 'wp_ulike_manage_comment_sortable_columns_order', 10, 1 );


function wp_ulike_panel_customization_section( $options ) {
	if( wp_ulike_setting_repo::isCodeSnippetsDisabled() ){
		return $options;
	}

	$options[] = array(
		'id'    => 'php_snippets',
		'type'  => 'code_editor',
		'settings' => array(
			'theme'  => 'mbo',
			'mode'   => 'php',
		),
		'title'    => esc_html__('PHP Snippets','wp-ulike'),
		'sanitize' => 'wp_ulike_html_entity_decode',
		'desc'     => esc_html__('Add PHP snippets without opening and closing tags (&lt;?php and ?&gt;). If you have lots of snippets, you may want to consider using Code Snippets plugin.', 'wp-ulike')
	);
	$options[] = array(
		'id'    => 'js_snippets',
		'type'  => 'code_editor',
		'settings' => array(
			'theme'  => 'mbo',
			'mode'   => 'javascript',
		),
		'title'    => esc_html__('Javascript Snippets','wp-ulike'),
		'sanitize' => 'wp_ulike_html_entity_decode',
		'desc'     => esc_html__('This code will output immediately before the closing &lt;/body&gt; tag in the document source. (Scripts must not be property wrapped in &lt;script&gt; tag.)', 'wp-ulike')
	);

	return $options;
}
add_filter( 'wp_ulike_panel_customization', 'wp_ulike_panel_customization_section', 10, 1 );