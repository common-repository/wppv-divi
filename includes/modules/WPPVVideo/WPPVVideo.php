<?php

class WPPV_Video extends ET_Builder_Module {

	public $slug       = 'wppv_video';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => '',
		'author'     => '',
		'author_uri' => '',
	);

	

	public function init() {
		$this->name = esc_html__( 'WP Pay Per View Video', 'wppv-divi' );
		if(function_exists('wppv_init_plyr_player')) {
			wppv_init_plyr_player();
		} else {
			
		}
	}

	public function get_fields() {
		return array(
			'product'          => 
				ET_Builder_Module_Helper_Woocommerce_Modules::get_field(
					'product',
					array(
						'default'          => ET_Builder_Module_Helper_Woocommerce_Modules::get_product_default(),
						'computed_affects' => array(
							// '__product_id',
							// '__player_html'
						),
					)
				),
			// 'video' => array(
			// 	'label'            => esc_html__( 'Video of product', 'et_builder' ),
			// 	'type'             => 'select',
			// 	'option_category'  => 'configuration',
			// 	'options'          => array(
			// 		'default' => esc_html__( 'Default', 'et_builder' ),
			// 	),
			// 	'toggle_slug'      => 'main_content',
			// 	'description'      => esc_html__( 'Here you can select the videos.', 'et_builder' ),
			// 	'default'          => 'default',
			// 	'show_if_not'          => array(
			// 		'product' => '-1',
			// 	),
			// 	'computed_affects' => array(
			// 		'__player_html',
			// 	),
			// 	'computed_callback'   => array(
			// 		'WPPV_Video',
			// 		'get_videos_of_product',
			// 	),
			// ),
			'video' => array(
				'label'				=> esc_html__( 'Video in product', 'et_builder' ),
				'type'				=> 'wppv_video_select',
				'option_category'	=> 'basic_option',
                'description'		=> esc_html__( 'Text entered here will appear inside the module.', 'simp-simple-extension' ),
                'toggle_slug'		=> 'main_content',
				'show_if_not'		=> array(
					'product' => array(
						'filter',
						// 'current',
						'latest',
					)
				),
				'computed_depends_on' => array(
					'product',
					// '__product_id'
				),
				'computed_affects' => array(
					'__player_html'
				),
			),
			// '__product_id'    => array(
			// 	'type'                => 'computed',
			// 	'computed_callback'   => array(
			// 		'WPPV_Video',
			// 		'get_product_id',
			// 	),
			// 	'computed_depends_on' => array(
			// 		'product',
			// 	),
			// 	'computed_minimum'    => array(
			// 		'product',
			// 	),
			// ),
			// '__video_index'    => array(
			// 	'type'                => 'computed',
			// 	'computed_callback'   => array(
			// 		'WPPV_Video',
			// 		'get_product_id',
			// 	),
			// 	'computed_depends_on' => array(
			// 		'product',
			// 	),
			// 	'computed_minimum'    => array(
			// 		'product',
			// 	),
			// ),
			'__player_html'    => array(
				'type'                => 'computed',
				'computed_callback'   => array(
					'WPPV_Video',
					'get_player_react_html',
				),
				'computed_depends_on' => array(
					'product',
					'video',
				),
				'computed_minimum'    => array(
					'product',
					'video',
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

	public static function get_player_html($args = array(), $conditional_tags = array(), $current_page = array()) {
		$product_id = self::get_product_id( $args );
		$file_url = wppv_get_video_url($product_id, intval($args['video'])); //wppv_get_preview_video_url($product);
		if(!empty($file_url))
		{
			$vidoe_type = unvq_video_type($file_url);
			$video_info = wppv_get_video_info( $file_url );
			$shortcode = wppv_video_shortcode('', $product_id, $file_url, $product_id); //$each_download['download_id']

			if(!empty($shortcode)) {
				return do_shortcode($shortcode);
			}
		}
		return 'WP Pay Per View';
	}

	public static function get_player_react_html($args = array(), $conditional_tags = array(), $current_page = array()) {
		$html = self::get_player_html( $args );
		$html .= '<script>if(window.wppv_load_all_players){window.wppv_load_all_players();}</script>';
		return $html;
	}

	public function render( $attrs, $content = null, $render_slug ) {
		wppv_log('wppv_divi render'.print_r($this->props, true));
		$html = self::get_player_html( $this->props );
		return $html;
		// $file_url = wppv_get_video_url($product_id, 0); //wppv_get_preview_video_url($product);
		// if(!empty($file_url))
		// {
		// 	$vidoe_type = unvq_video_type($file_url);
		// 	$video_info = wppv_get_video_info( $file_url );
		// 	$shortcode = wppv_video_shortcode('', $product_id, $file_url, $product_id); //$each_download['download_id']

		// 	if(!empty($shortcode)) {
		// 		return do_shortcode($shortcode);
		// 	}
		// }
		// return 'WP Pay Per View';
		// return sprintf( '<h1>%1$s</h1>', $this->props['content'] );
	}
}

new WPPV_Video;
