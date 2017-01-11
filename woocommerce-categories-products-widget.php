<?php
/*
Plugin Name: WooCommerce Categories Products Widget
Plugin URI: http://glowlogix.com/
Description: Show Product Categories and Product Titles in Widget
Author: Muhammad Usama Masood
Version: 1
Author URI: http://glowlogix.com/
*/
class RandomPostWidget extends WP_Widget
{
  		 function RandomPostWidget()
	 	 {
	    $widget_ops = array('classname' => 'RandomPostWidget', 'description' => 'Displays a random post with thumbnail' );
	    parent::__construct('RandomPostWidget', 'Random Post and Thumbnail', $widget_ops);
	  }
 
		  function form($instance)
		  {
		    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		    $title = $instance['title'];
?>
 	 <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;

	$terms = get_terms( array(
	    'taxonomy' => 'product_cat',
	    'hide_empty' => false,
	) );

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
	    echo '<ul>';
	    foreach ( $terms as $term ) {
	        echo '<li><h4 class="tight"><a href="' . esc_url( get_term_link( $term ) ) . '">' . $term->name . '</a></h4></li>';
		        $args = array(
				    'post_type' => 'product',
				    'posts_per_page' => 5,
				    'tax_query' => array(
				        array(
				            'taxonomy' => 'product_cat',
				            'field'    => 'slug',
				            'terms'    => $term->slug,
				        ),
				    ),
				);
				$products = new WP_Query( $args );
				while ( $products->have_posts() ) : 
					$products->the_post();
					echo '<li><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
				endwhile;
				wp_reset_postdata();
				
	    }
	    echo '</ul>';
	}
    echo $after_widget;
  }
 
	}
	add_action( 'widgets_init', create_function('', 'return register_widget("RandomPostWidget");') );?>
