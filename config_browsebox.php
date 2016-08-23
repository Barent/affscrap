<?php

function init_browsebox_config(){

    function my_custom_post_product() {
      $labels = array(
        'name'               => _x( 'Products', 'post type general name' ),
        'singular_name'      => _x( 'Product', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'book' ),
        'add_new_item'       => __( 'Add New Product' ),
        'edit_item'          => __( 'Edit Product' ),
        'new_item'           => __( 'New Product' ),
        'all_items'          => __( 'All Products' ),
        'view_item'          => __( 'View Product' ),
        'search_items'       => __( 'Search Products' ),
        'not_found'          => __( 'No products found' ),
        'not_found_in_trash' => __( 'No products found in the Trash' ), 
        'parent_item_colon'  => '',
        'menu_name'          => 'Products'
      );
      $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our products and product specific data',
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'has_archive'   => true,
      );
      register_post_type( 'product', $args ); 
    }

    add_action( 'init', 'my_custom_post_product' );

    function my_taxonomies_product() {
      $labels = array(
        'name'              => _x( 'Product Categories', 'taxonomy general name' ),
        'singular_name'     => _x( 'Product Category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Product Categories' ),
        'all_items'         => __( 'All Product Categories' ),
        'parent_item'       => __( 'Parent Product Category' ),
        'parent_item_colon' => __( 'Parent Product Category:' ),
        'edit_item'         => __( 'Edit Product Category' ), 
        'update_item'       => __( 'Update Product Category' ),
        'add_new_item'      => __( 'Add New Product Category' ),
        'new_item_name'     => __( 'New Product Category' ),
        'menu_name'         => __( 'Product Categories' ),
      );
      $args = array(
        'labels' => $labels,
        'hierarchical' => true,
      );
      register_taxonomy( 'product_category', 'product', $args );
    }
    add_action( 'init', 'my_taxonomies_product', 0 );
   
    if(!function_exists('my_custom_post_product')){
        $args = array( 'post_type' => 'product');
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post();
            the_title();
            echo '<div class="entry-content">';
            the_content();
            echo '</div>';
        endwhile;
    }

    // Show posts of 'post', 'page' and 'movie' post types on home page
    add_action( 'pre_get_posts', 'add_my_post_types_to_query' );
     
    function add_my_post_types_to_query( $query ) {
        if ( is_home() && $query->is_main_query() )
            $query->set( 'post_type', array( 'post', 'product' ) );
        return $query;
    }

  	add_filter( 'manage_product_posts_columns', 'set_custom_edit_product_columns' );
	add_action( 'manage_product_posts_custom_column' , 'custom_product_column', 10, 2 );

	function set_custom_edit_product_columns($columns) {
	    //unset( $columns['author'] );
	    $columns['product_shortcode'] = __( 'Shortcode', 'localhost' );
	    //$columns['publisher'] = __( 'Publisher', 'localhost' );

	    return $columns;
	}

	function custom_product_column( $column, $post_id ) {
	    switch ( $column ) {

	        case 'product_shortcode' :
	            $terms = get_the_term_list( $post_id , 'product_shortcode' , '' , ',' , '' );
	            if ( is_string( $terms ) )
	                echo $terms;
	            else
	                _e( 'Unable to get shortcode(s)', 'localhost' );
	            break;

	        //case 'publisher' :
	        //    echo get_post_meta( $post_id , 'publisher' , true ); 
	        //    break;

	    }
	}

}
?>