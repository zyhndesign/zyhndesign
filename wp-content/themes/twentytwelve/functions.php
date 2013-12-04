<?php
/**
 * Twenty Twelve functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 625;

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_setup() {
	/*
	 * Makes Twenty Twelve available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'twentytwelve' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentytwelve', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentytwelve' ) );

	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 500, 500 ,true ); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'twentytwelve_setup' );

/**
 * Adds support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Enqueues scripts and styles for front-end.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/*
	 * Adds JavaScript for handling the navigation menu hide-and-show behavior.
	 */
	wp_enqueue_script( 'twentytwelve-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true );

	/*
	 * Loads our special font CSS file.
	 *
	 * The use of Open Sans by default is localized. For languages that use
	 * characters not supported by the font, the font can be disabled.
	 *
	 * To disable in a child theme, use wp_dequeue_style()
	 * function mytheme_dequeue_fonts() {
	 *     wp_dequeue_style( 'twentytwelve-fonts' );
	 * }
	 * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
	 */

	/* translators: If there are characters in your language that are not supported
	   by Open Sans, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'twentytwelve' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language, translate
		   this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'twentytwelve' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		wp_enqueue_style( 'twentytwelve-fonts', add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ), array(), null );
	}

	/*
	 * Loads our main stylesheet.
	 */
	wp_enqueue_style( 'twentytwelve-style', get_stylesheet_uri() );

	/*
	 * Loads the Internet Explorer specific stylesheet.
	 */
	wp_enqueue_style( 'twentytwelve-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentytwelve-style' ), '20121010' );
	$wp_styles->add_data( 'twentytwelve-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'twentytwelve_scripts_styles' );

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Twenty Twelve 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function twentytwelve_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'twentytwelve_wp_title', 10, 2 );

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentytwelve_page_menu_args' );

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentytwelve' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Front Page Widget Area', 'twentytwelve' ),
		'id' => 'sidebar-3',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentytwelve_widgets_init' );

if ( ! function_exists( 'twentytwelve_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
			<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve' ) ); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'twentytwelve_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentytwelve_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'twentytwelve' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'twentytwelve' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'twentytwelve' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwelve' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'twentytwelve' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'twentytwelve' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'twentytwelve_entry_meta' ) ) :
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentytwelve_entry_meta() to override in a child theme.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/**
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Twenty Twelve 1.0
 *
 * @param array Existing class values.
 * @return array Filtered class values.
 */
function twentytwelve_body_class( $classes ) {
	$background_color = get_background_color();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_color ) )
		$classes[] = 'custom-background-empty';
	elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
		$classes[] = 'custom-background-white';

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'twentytwelve-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'twentytwelve_body_class' );

/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'twentytwelve_content_width' );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since Twenty Twelve 1.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function twentytwelve_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'twentytwelve_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_customize_preview_js() {
	wp_enqueue_script( 'twentytwelve-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20120827', true );
}
add_action( 'customize_preview_init', 'twentytwelve_customize_preview_js' );



/*--------------------------------------------------自定义功能代码===============================================*/
/*
 * 自定义删除文件夹函数
 * */
function zy_deldir($dir)
{
    //如果不设置编码，删除中文文件会出错
    //先删除目录下的文件：
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                if (!unlink($fullpath)) {
                    return false;
                }
            } else {
                //嵌套调用
                if (!zy_deldir($fullpath, true)) {
                    return false;
                }
            }
        }
    }
    closedir($dh);


    if (!rmdir($dir)) {
        return false;
    }

    return true;
}

/*============================================加载自定义资源=====================================================*/
/*
 * 加载自定义的资源文件
 * */
function zy_load_resource($hook)
{

    //只有添加幻灯片、编辑文章页面、添加音乐页面才加在这几个js
    if ("post-new.php" == $hook || "post.php" == $hook) {

        global $user_ID; //当前用户id

        //图文混排或者音乐文件修改，修改的时候会带有post的id
        //添加一个自定义的js变量,把模版地址刷给页面，自定义的js可以直接使用
        $zy_template_url = get_template_directory_uri();

        echo "<script type='text/javascript'>
            zy_config={
                zy_template_url:'$zy_template_url',
                zy_user_id:'$user_ID'
            };
            Object.freeze(zy_config);
        </script>";

        //引入文章页面的js
        wp_enqueue_script("zy_post_js", get_template_directory_uri() . '/js/src/zy_post.js');
        //引入自定义的css
        wp_enqueue_style("zy_post_css", get_template_directory_uri() . '/css/app/zy_post.css');
    }
}

//admin_head,admin_print_scripts一般都只是输出，函数中用echo
add_action('admin_enqueue_scripts', 'zy_load_resource');


/*===============================================================图文混排页面代码===============================*/
/*---------------------------------------------------添加右边栏输入项部分-------------------------------------------*/
/*
 * 添加背景html
 * */
function zy_post_background_box($post)
{
    //获取原来的缩略图

    $zy_old_background = get_post_meta($post->ID, "zy_background", true);

    if ($zy_old_background) {

        $zy_old_background = json_decode($zy_old_background, true);

        $zy_background_filename = $zy_old_background["filename"];

        echo "<input type='hidden' name='zy_old_background' value='$zy_background_filename'>";
    }

    $edit_time = get_post_meta($post->ID, "_edit_lock", true);
    if ($edit_time) {
        echo "<input type='hidden' name='_edit_lock' value='$edit_time'>";
    }

    ?>

    <div id='zy_background_container'>

        <div class="zy_post_div"><input id="zy_upload_background_button" type="button" class="zy_post_button"
                                        value="上传">

            <input id="zy_upload_background_clear" type="button" class="zy_post_button" value="清除">

            <span style="display: block">限jpg、png、mp4，分辨率1280*720</span>

            <span id="zy_background_percent" class="zy_background_percent"></span>

        </div>

        <?php

        if ($zy_old_background) {

            $filepath = $zy_old_background["filepath"];

            if ($zy_old_background["type"] == "mp4") {

                echo "<video id='zy_background_content'  class='zy_background' controls><source src='$filepath' type='video/mp4' /></video>";

            } else {

                echo "<img id='zy_background_content' class='zy_background' src='$filepath'>";

            }

        } else {

            echo "<img id='zy_background_content'  class='zy_background' src='" . get_template_directory_uri() . "/images/app/defaultBackground.png'>";

        }

        ?>

        <input type="hidden" value="<?php echo $zy_old_background["filename"]; ?>" name="zy_background"
               id="zy_background">
    </div>

<?php
}

/*
 *添加字段到图文混排页面右边
 * */
function zy_add_box()
{
    add_meta_box("zy_background_id", "背景", "zy_post_background_box", 'post', 'side');
}

add_action("add_meta_boxes", 'zy_add_box');


/*--------------------------------------------------------图文混排保存数据部分---------------------------------*/

/*
 * 存储背景图数据函数
 * */
function zy_save_background($post_id)
{
    $filename = $_POST["zy_background"];
    global $user_ID;
    $dir = wp_upload_dir();
    $from_dir = $dir["basedir"] . "/tmp/" . $user_ID;
    $target_dir = $dir["basedir"] . "/" . $post_id;

    if (!empty($filename)) {
        //创建目标文件夹,当背景为空时，不创建
        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir)) {
                return false;
            }
        }
    }


    //分为新建和修改两种类型
    if (isset($_POST["zy_old_background"])) {
        $old_filename = $_POST["zy_old_background"];
        //如果是修改了文件
        if (!empty($filename)) {
            $pathinfo = pathinfo($filename);
            $filetype = $pathinfo["extension"]; //获取后缀


            if ($old_filename != $filename) {
                //删除原有文件
                if (is_file($target_dir . "/" . $old_filename)) {
                    if (!unlink($target_dir . "/" . $old_filename)) {
                        return false;
                    }
                }
                //移动文件
                if (is_file($from_dir . "/" . $filename)) {
                    if (!rename($from_dir . "/" . $filename, $target_dir . "/" . $filename)) {
                        return false;
                    }
                }
                $filepath = $dir["baseurl"] . "/" . $post_id . "/" . $filename;
                //组装数据库数据
                $json = '{"filename":"' . $filename . '","filepath":"' . $filepath . '","type":"' . $filetype . '"}';
                if (!update_post_meta($post_id, "zy_background", $json)) {
                    return false;
                }
            } else {
                //如果出现同名文件，要看一下tmp中是否存在文件，如果存在，则直接移动过去覆盖原来的文件
                if (is_file($from_dir . "/" . $filename)) {
                    //移动文件
                    if (!rename($from_dir . "/" . $filename, $target_dir . "/" . $filename)) {
                        return false;
                    }
                }
            }
        } else {

            //删除原有文件
            if (is_file($target_dir . "/" . $old_filename)) {
                if (!unlink($target_dir . "/" . $old_filename)) {
                    return false;
                }
            }
            //如果值为空，则直接删除meta，
            if (!delete_post_meta($post_id, "zy_background")) {
                return false;
            }
        }
    } else {
        //新增
        if (!empty($filename)) {
            $pathinfo = pathinfo($filename);
            $filetype = $pathinfo["extension"]; //获取后缀

            //移动文件
            if (is_file($from_dir . "/" . $filename)) {
                //移动文件
                if (!rename($from_dir . "/" . $filename, $target_dir . "/" . $filename)) {
                    return false;
                }
            }
            $filepath = $dir["baseurl"] . "/" . $post_id . "/" . $filename;
            //组装数据库数据
            $json = '{"filename":"' . $filename . '","filepath":"' . $filepath . '","type":"' . $filetype . '"}';
            if (!update_post_meta($post_id, "zy_background", $json)) {
                return false;
            }
        }
    }

    //返回值
    return true;
}

/*
 * 保存自定义数据,所有的数据在一个函数保存
 * */
function zy_save_own_data($post_id)
{
    /* *
     * 由于有快速编辑，同样会进入，但是保存的时候会出错，所以要判断一下如果是快速编辑则不进入
     * 保存数据，快速编辑的时候是没有背景数据过来的。
     * */
    if (isset($_POST["zy_background"])) {
        //设置页面编码
        header("content-type:text/html;charset=utf-8");

        /*存储背景数据*/
        if (!zy_save_background($post_id)) {
            //提示错误
            die("保存背景数据出错，请联系开发人员");
        }

    }

}

add_action('publish_post', 'zy_save_own_data');
//add_action('pre_post_update','zy_data_save');

//禁用自动保存草稿
function zy_disable_autosave(){
    wp_deregister_script("autosave");
}
add_action("wp_print_scripts","zy_disable_autosave");

/*
 * 删除数据库多余的记录
 * */
//禁用修订版本
remove_action("post_updated", "wp_save_post_revision");
function zy_delete_autodraft($post_id)
{
    global $wpdb;
    //在发布文章的时候删除掉除自己外的其他垃圾文章，除自己外是因为当没填写任何内容发布时，状态也是auto-draft
    $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'");
}

add_action("publish_post", "zy_delete_autodraft");


/*===========================================================文章锁定的控制==================================*/
/*
 * 文章处于锁定阶段的判断
 * */
function zy_check_lock($post_id){

    //如果提交的edit_lock和数据库中保存的不一样，那么要阻止提交
    $current_edit_lock=get_post_meta($post_id,"_edit_lock",true);
    $edit_lock=$_POST["_edit_lock"];
    if($current_edit_lock!=$edit_lock&&$edit_lock){
        header("content-type:text/html; charset=utf-8");
        die("其他人以先于你提交更改，请重新编辑后再提交，<a href='".site_url()."/wp-admin/edit.php'>返回</a>");
    }
}
add_action("pre_post_update","zy_check_lock");

/*========================================================ajax接口===============================*/
function zy_action_uploadfile()
{

    $dir = wp_upload_dir();
    //存储的时候存储到文件系统，返回的时候要返回路径
    $user_id = $_POST["user_id"];
    $file_use_type = isset($_POST["file_type"]) ? $_POST["file_type"] : ""; //文件代表的类型，如果是content_img表示是要显示到内容中的图片，需要压缩


    $filename = $_FILES["file"]["name"];
    $pathinfo = pathinfo($filename);
    $filetype = $pathinfo["extension"]; //获取后缀


    //判断背景图是否为1280宽
    if ($filetype != "mp4" && $file_use_type == "zy_background") {
        $attr = getimagesize($_FILES["file"]["tmp_name"]);
        if ($attr[0] != 1280 && $attr[1] != 720) {
            //如果不是1：1的图片报错
            $obj = array("message" => "图片宽度不是1280或者高度不是720！");
            wp_send_json_error($obj);
        }
    }

    $tmp_dir = $dir["basedir"] . "/tmp";
    $target_dir = $tmp_dir . "/" . $user_id;

    //创建文件夹
    if (!is_dir($tmp_dir)) {
        if (!mkdir($tmp_dir)) {
            $obj = array("message" => "创建临时文件夹失败！");
            wp_send_json_error($obj);
        }
    }
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir)) {
            $obj = array("message" => "创建文件夹失败！");
            wp_send_json_error($obj);
        }
    }


    //此处需要文件转码才能支持中文
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . "/" . $filename)) {

        $obj = array("url" => $dir["baseurl"] . "/tmp/" . $user_id . "/" . $filename, "filename" => $filename);
        wp_send_json_success($obj);

    } else {
        $obj = array("message" => "文件上传失败，请稍后重试");
        wp_send_json_error($obj);
    }
}

/*
 * 处理文件上传的ajax函数
 * */
add_action('wp_ajax_uploadfile', 'zy_action_uploadfile');
//火狐里面这个地方不会带登陆标志过来，需要加下面这句
add_action('wp_ajax_nopriv_uploadfile', 'zy_action_uploadfile');

/*===================================================数据清理=====================================*/
/**
 * 清除上传时产生的临时文件
 */
function zy_delete_tmp(){
    global $user_ID;
    $currentTimeS=time();
    $target_dir=wp_upload_dir();
    $target_dir=$target_dir["basedir"]."/tmp/".$user_ID;
    if(is_dir($target_dir)){
        $fileTimeS=filemtime($target_dir);
        if($currentTimeS-$fileTimeS>12*60*60){
            zy_deldir($target_dir);
        }
    }
}
add_action("admin_init","zy_delete_tmp");

/*
 * 删除时的操作函数
 * */
function zy_delete_post($post_id)
{
    //设置页面编码
    header("content-type:text/html; charset=utf-8");

    //删除文件
    $targetDir = wp_upload_dir();

    if (is_dir($targetDir["basedir"] . "/" . $post_id)) {
        //这里删除可能不会成功，所以出错后应该手动删除文件夹
        if (!zy_deldir($targetDir["basedir"] . "/" . $post_id)) {
            die("删除文件失败，请将文章id" . $post_id . "告诉开发人员！");
        }
    }
}

add_action('deleted_post', 'zy_delete_post');



