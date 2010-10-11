<?php
/*
Plugin Name: Smart WYSIWYG Blocks Of Content
Plugin URI: http://cnjcbs.com/wordpress-plugins/smart-wysiwyg-blocks-of-content
Description:
Author: Coen Jacobs
Version: 0.2.1
Author URI: http://cnjcbs.com
*/

class SWBOC_Widget extends WP_Widget {
	function SWBOC_Widget() {
		$widget_ops = array( 'classname' => 'SWBOC_Widget', 'description' => 'Widget to show a Smart WYSIWYG Block of Content' );
		$this->WP_Widget( 'swboc', 'SWBOC Widget', $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		echo $before_widget;
		
		$swboc_id = esc_attr($instance['swboc_id']);
		$swboc_title = esc_attr($instance['title']);
		
		echo $before_title.$swboc_title.$after_title;
		
		$args = array(
			'post__in' => array($swboc_id),
			'post_type' => 'Smart Block',
		);
		
		query_posts($args);
		
		while ( have_posts() ) : the_post();
			the_content();
		endwhile;

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$this->title = esc_attr($instance['title']);
		$updated_instance = $new_instance;
		return $updated_instance;
	}

	function form( $instance ) {
		$swboc_id = esc_attr($instance['swboc_id']);
		$swboc_title = esc_attr($instance['title']); ?>
		
		<label for="<?php echo $this->get_field_id('title'); ?>">Title:
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $swboc_title; ?>" /></label>
		
		<label for="<?php echo $this->get_field_id('swboc_id'); ?>">Smart block:
		<select class="widefat" id="<?php echo $this->get_field_id('swboc_id'); ?>" name="<?php echo $this->get_field_name('swboc_id'); ?>">
		
			<?php query_posts('post_type=Smart Block&orderby=ID&order=ASC'); ?>
			
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
				$currentID = get_the_ID();
				if($currentID == $swboc_id)
					$extra = 'SELECTED';
				else
					$extra = '';
				
				echo '<option value="'.$currentID.'" '.$extra.'>'.get_the_title().'</option>';
			endwhile; else:
				echo '<option value="NULL">Create blocks first</option>';
			endif; ?>
			
			<?php wp_reset_query(); ?>
		</select></label>

		<?php
	}
}

add_action( 'widgets_init', create_function( '', "register_widget('SWBOC_Widget');" ) );

add_action( 'init', 'create_swboc_type' );

function create_swboc_type() {
	register_post_type( 'Smart Block',
		array(
			'labels' => array(
				'name' => __( 'Smart Blocks' ),
				'singular_name' => __( 'Smart Block' ),
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Smart Block' ),
				'edit' => __( 'Edit' ),
				'edit_item' => __( 'Edit Smart Block' ),
				'new_item' => __( 'New Smart Block' ),
				'view' => __( 'View Smart Block' ),
				'view_item' => __( 'View SSmart Block' ),
				'search_items' => __( 'Search Smart Blocks' ),
				'not_found' => __( 'No Smart Blocks found' ),
				'not_found_in_trash' => __( 'No Smart Blocks found in Trash' ),
				'parent' => __( 'Parent Smart Block' ),
			),
			'public' => true,
			'description' => __( 'A Smart Block is a effective way to store content that you will use more than once.'),
			'show_ui' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'menu_position' => 20,
			'hierarchical' => false,
			'query_var' => true,
			'supports' => array( 'title', 'editor'),
			'can_export' => true,
		)
	);
}

?>