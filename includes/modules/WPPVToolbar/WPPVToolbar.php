<?php

class WPPV_Toobar extends ET_Builder_Module {

	public $slug       = 'wppv_toolbar';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => '',
		'author'     => '',
		'author_uri' => '',
	);

	

	public function init() {
		$this->name = esc_html__( 'WP Pay Per View Toolbar', 'wppv-divi' );
	}

	public function get_fields() {
		return array(
			'product'          => 
				ET_Builder_Module_Helper_Woocommerce_Modules::get_field(
					'product',
					array(
						'default'          => ET_Builder_Module_Helper_Woocommerce_Modules::get_product_default(),
					)
				),
			'__toolbar_html'    => array(
				'type'                => 'computed',
				'computed_callback'   => array(
					'WPPV_Toobar',
					'get_toolbar_react_html',
				),
				'computed_depends_on' => array(
					'product'
				),
				'computed_minimum'    => array(
					'product'
				),
			),
		);
	}

	public static function get_product_id( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$defaults = array(
			'product'          => 'current'
		);
		$args     = wp_parse_args( $args, $defaults );
		return ET_Builder_Module_Helper_Woocommerce_Modules::get_product_id( $args['product'] );
	}

	public static function get_toolbar_html($args = array(), $conditional_tags = array(), $current_page = array()) {
		$product_id = self::get_product_id( $args );
		
		if(intval($product_id) > 0)
		{
			$shortcode = '[wppv_play_toolbar product_id="'.$product_id.'"]';
			$html = do_shortcode($shortcode);
			$subst = '$0 et_pb_button';
			$re = '/class="(wppv_player_btn_play)/m';
			$html = preg_replace($re, $subst, $html);
			return $html;
		}
		return 'Please choose a product.';
	}

	public static function get_toolbar_react_html($args = array(), $conditional_tags = array(), $current_page = array()) {
		$html = self::get_toolbar_html( $args );
		return $html;
	}

	public function render( $attrs, $content = null, $render_slug ) {
		wppv_log('wppv_divi render'.print_r($this->props, true));
		$html = self::get_toolbar_html( $this->props );
		return $html;
	}
}

new WPPV_Toobar;
