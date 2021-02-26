<?php
  /*
  Plugin name: MLL Gallery
  Plugin URI: https://michelelukowski.com/
  Description: This is my custom gallery plugin for wordpress 
  Author: Michele Lukowski
  Author URI: https://michelelukowski.com/
  Version: 1.0
  Text Domain: mll
  license: none
*/
//plugin variables
$this_directory = plugin_dir_url('mll_gallery.php');
//plugin styles
wp_register_style('mll_gallery_style', '/wp-content/plugins/mll_gallery/assets/mll_gallery.css');
wp_enqueue_style('mll_gallery_style');
  
  Function mll_create_gallery_page() {
    //page function variables
    $categoryID = '1'; 
    $post_status = 'publish';
    $gallery_title = 'Portfolio'; //I will want this to be something that can be changed so I will have to look at that
    $gallery_slug = 'portfolio';
    $gallery_content = '[gallery_grid]';
    $gallery_page = get_page_by_title('Portfolio');
    
    if ($gallery_page == NULL) {
     $args = array(
      'post_type' => 'page',
      'post_title' => $gallery_title,
      'post_name' => $gallery_slug,
      'post_author' => 1,
      'post_content' => $gallery_content,
      'post_status' => $post_status,
      'page_template' => $this_directory.'nosidebar.php',
     );
     wp_insert_post( $args );
    } else {
     function mll_admin_message() { 
      $admin_message = '<p>MLL Gallery Plugin says page "Portfolio" was not created because it already exists.</p>';
      echo '<div class="notice notice-warning is-dismissible">'. $admin_message . '</div>';
     }
     add_action('admin_notices', 'mll_admin_message' );
    }
  }
  add_action('init', 'mll_create_gallery_page' );
  //custom gallery item post type
  function mll_register_gallery_post_type() {
    $labels = array(
      'name' => __('Gallery Posts', 'mll'),
      'singular_name' => __('Gallery Post', 'mll'),
      'add_new' => __('New Gallery Post', 'mll'),
      'add_new_item' => __('Add New Gallery Post', 'mll'),
      'edit_item' => __('Edit Gallery Post', 'mll'),
      'new_item' => __('New Gallery Post', 'mll'),
      'view_item' => __('View Gallery Post', 'mll'),
      'search_item' => __('Search Gallery Posts', 'mll'),
      'not_found' => __('No Gallery Posts found', 'mll'),
      'not_found_in_trash' => __('No Gallery Posts found in trash', 'mll'),
  );
  //$parent_page_permalink = get_permalink(get_page_by_title( 'Portfolio'));
  //$gallery_slug = str_replace( home_url(), '', $parent_page_permalink);
  $gallery_slug = 'portfolio';
    $args = array(
      'labels' => $labels,
      'has_archive' => true,
      'public' => true,
      'hierarchical' => false,
      'supports' => array(
        'title',
        //'editor',
        'custom-fields',
        'thumbnail',
        'page-atttributes'
      ),
      //'rewrite' => array( 'slug' => $gallery_slug),
      //'taxonomies' => array( 'category' ),
      'rewrite' => array( 'slug' => $gallery_slug),
      'show_in_rest' => true
    );
  register_post_type( 'mll_gallery_post', $args );
  }
  function mll_register_gallery_taxonomy() {
    $labels = array(
      'name' => __( 'Medium' , 'mll' ),
      'singular_name' => __( 'Medium', 'mll' ),
      'search_items' => __( 'Search Media' , 'mll' ),
      'all_items' => __( 'All Media' , 'mll' ),
      'edit_item' => __( 'Edit Medium' , 'mll' ),
      'update_item' => __( 'Update Medium' , 'mll' ),
      'add_new_item' => __( 'Add New Medium' , 'mll' ),
      'new_item_name' => __( 'New Medium Name' , 'mll' ),
      'menu_name' => __( 'Media' , 'mll' ),
    );
    
    $args = array(
      'labels' => $labels,
      'hierarchical' => true,
      'sort' => true,
      'args' => array( 'orderby' => 'term_order' ),
      'rewrite' => array( 'slug' => 'media' ),
      'show_admin_column' => true,
      'show_in_rest' => true

  );
   
  register_taxonomy('mll_media', array('mll_gallery_post'), $args);
  }
  add_action('init', 'mll_register_gallery_post_type' );
  add_action( 'init', 'mll_register_gallery_taxonomy' );
  function mll_list_child_pages($atts) { //specific to gallery posts
          global $post;
          //I need to determine from the attributes supplied with the shortcode, which type of items to display.
          extract(shortcode_atts(array(
            'mll_media' => $atts,
          ), $atts));
          //define args
          $args = array(
              'post_type' => 'mll_gallery_post',
              'mll_media' => $atts,
              'order' => 'DESC',
              'numberposts' => -1,
          );
          $gallery_items = get_posts ($args);
          if ( $gallery_items ) { ?>
          <div class="gallery-of-posts">
          <?php //loop of posts
          foreach ($gallery_items as $post ){
              setup_postdata( $post ); ?>
                <article id="<?php the_ID(); ?>" class="child-listing" <?php post_class() ?>>
                <?php if (has_post_thumbnail() ) { ?>
                <div class="gallery-post-image">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'medium' ); ?>
                    </a>
                </div>
<!--Uncomment the section below to include titles for the images-->
<!--                <a class="gallery-post-title-link" href="<?php the_permalink(); ?>">
                <h3 class="gallery-post-title"><?php the_title(); ?></h3>
                </a>
-->
                <?php } ?>
                </article>
              <?php } ?>
            </div>
        <?php }
      }
      add_shortcode('gallery_grid', 'mll_list_child_pages');
  //more code here to get the page template for the main gallery page
?>