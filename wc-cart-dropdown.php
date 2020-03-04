<?php
/*
Plugin Name: WC Cart Dropdown
Plugin URI: https://github.com/avantegarde/wc-cart-dropdown-widget
Description: Simple cart dropdown for WooCommerce
Author: avantegarde
Author URI: https://github.com/avantegarde
Version: 1.0.0
Text Domain: wc_cart_dropdown_widget
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Plugin Activation
 */
function spm_activate() {
	//spm_properties_post_type();
	//flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'spm_activate' );

/**
 * Plugin Deactivation
 */
function spm_deactivate() {
	//flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'spm_deactivate' );

/**
 * Enqueue Styles
 */
function spm_enqueue_custom_scripts(){
	// CSS
	wp_enqueue_style('wc-cart-dropdown-css', plugin_dir_url( __FILE__ ) . 'includes/css/style.css');
	// JS
	//wp_enqueue_script('wc-cart-dropdown-js', plugin_dir_url( __FILE__ ) . 'includes/js/script.js', array('jquery'), 1.0, true);
}
add_action('wp_enqueue_scripts', 'spm_enqueue_custom_scripts');

// Register and load the widget
function cc_load_widget() {
	register_widget( 'cc_wc_cart_drop_widget' );
}
add_action( 'widgets_init', 'cc_load_widget' );

// Creating the widget 
class cc_wc_cart_drop_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'cc_wc_cart_drop_widget', 
			// Widget name will appear in UI
			__('WC Cart Dropdown', 'wc_cart_dropdown_widget'), 
			// Widget description
			array( 'description' => __( 'Simple Cart Dropdown for WooCommerce', 'wc_cart_dropdown_widget' ), ) 
		);
	}

	// Creating widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$my_account_location = $instance['my_account'] ? $instance['my_account'] : 'outside';

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
			// This is where you run the code and display the output
			//echo __( $title, 'wc_cart_dropdown_widget' );
		}
		// START: WC Cart Dropdown
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) : ?>
			<div class="cc-wc-cart-drop-widget">
					<div class="header-cart-wrap">
							<i class="fas fa-shopping-cart header-cart-icon desktop-icon" aria-hidden="true"></i>
							<a class="mobile-cart-link" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
								<i class="fas fa-shopping-cart header-cart-icon" aria-hidden="true"></i>
							</a>
							<a class="cart-customlocation" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
									<?php echo WC()->cart->get_cart_total(); ?> - <?php echo sprintf ( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?>
							</a>
							<div class="cart-dropdown">
								<div class="widget_shopping_cart_content"><?php woocommerce_mini_cart();?></div>
								<?php if($my_account_location === 'inside') : ?>
									<div class="my-account-inside">
											<p class="woocommerce-mini-cart__buttons buttons">
												<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account',''); ?>" class="button wc-forward my-account-button"><?php _e('My Account',''); ?></a>
											</p>
									</div>
								<?php endif; ?>
							</div>
					</div>
					<?php if($my_account_location === 'outside') : ?>
						<div class="alt-items">
								<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account',''); ?>" class="my-account-button"><?php _e('My Account',''); ?></a>
								<span class="header-menu-divider">|</span>
						</div>
					<?php endif; ?>
			</div>
		<?php else :
			$title = '<p style="font-size:12px;">Please install WooCommerce for this widget to work.</p>';
			echo __( $title, 'wc_cart_dropdown_widget' );
		endif;
		// END: WC Cart Dropdown

		echo $args['after_widget'];
	}

	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Cart', 'wc_cart_dropdown_widget' );
		}
		if ( isset( $instance[ 'my_account' ] ) ) {
			$my_account = $instance[ 'my_account' ];
		}
		else {
			$my_account = __( 'outside', 'wc_cart_dropdown_widget' );
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p><strong>My Account link location:</strong></p>
		<p>
			<label>
				<input type="radio" value="nolink" name="<?php echo $this->get_field_name( 'my_account' ); ?>" <?php checked( $my_account, 'nolink' ); ?> id="<?php echo $this->get_field_id( 'my_account' ); ?>" />
				<?php esc_attr_e( 'No Link', 'wc_cart_dropdown_widget' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input type="radio" value="outside" name="<?php echo $this->get_field_name( 'my_account' ); ?>" <?php checked( $my_account, 'outside' ); ?> id="<?php echo $this->get_field_id( 'my_account' ); ?>" />
				<?php esc_attr_e( 'Outside Dropdown', 'wc_cart_dropdown_widget' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input type="radio" value="inside" name="<?php echo $this->get_field_name( 'my_account' ); ?>" <?php checked( $my_account, 'inside' ); ?> id="<?php echo $this->get_field_id( 'my_account' ); ?>" />
				<?php esc_attr_e( 'Inside Dropdown', 'wc_cart_dropdown_widget' ); ?>
			</label>
		</p>
		<?php 
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		extract( $new_instance );
		$instance = array();
		$instance['title'] = ( ! empty( $title ) ) ? strip_tags( $title ) : '';
		$instance['my_account'] = ( !empty( $my_account ) ) ? sanitize_text_field( $my_account ) : null;
		return $instance;
	}
} // Class cc_wc_cart_drop_widget ends here