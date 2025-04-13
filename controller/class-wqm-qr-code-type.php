<?php
/**
 * QR code generator main post type
 */

defined( 'ABSPATH' ) || exit;

class WQM_QR_Code_Type {

	/**
	 * The post type name
	 */
	const POST_TYPE = 'wqm_qr_code';

	public static function run() {
		add_action( 'init', array( self::class, 'register_post_type' ) );
		add_action( 'add_meta_boxes', array( self::class, 'add_meta_boxes' ) );
		add_action( 'save_post', array( self::class, 'save_meta_boxes' ), 10, 2 );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( self::class, 'add_qr_code_column' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array(
			self::class,
			'show_qr_code_column'
		), 10, 2 );
	}

	/**
	 * Register QR code post type
	 */
	public static function register_post_type() {
		$args = array(
			'labels'              => array(
				'name'                  => __( 'QR Codes', 'wp-qrcode-me-v-card' ),
				'singular_name'         => __( 'QR Code', 'wp-qrcode-me-v-card' ),
				'all_items'             => __( 'QR Codes', 'wp-qrcode-me-v-card' ),
				'menu_name'             => __( 'QR Codes', 'wp-qrcode-me-v-card' ),
				'add_new'               => __( 'Add New', 'wp-qrcode-me-v-card' ),
				'add_new_item'          => __( 'Add New QR Code', 'wp-qrcode-me-v-card' ),
				'edit'                  => __( 'Edit', 'wp-qrcode-me-v-card' ),
				'edit_item'             => __( 'Edit QR Code', 'wp-qrcode-me-v-card' ),
				'new_item'              => __( 'New QR Code', 'wp-qrcode-me-v-card' ),
				'view'                  => __( 'View QR Code', 'wp-qrcode-me-v-card' ),
				'view_item'             => __( 'View QR Code', 'wp-qrcode-me-v-card' ),
				'search_items'          => __( 'Search QR Code', 'wp-qrcode-me-v-card' ),
				'not_found'             => __( 'No QR Codes found', 'wp-qrcode-me-v-card' ),
				'not_found_in_trash'    => __( 'No QR Codes found in trash', 'wp-qrcode-me-v-card' ),
				'featured_image'        => __( 'QR Code Image', 'wp-qrcode-me-v-card' ),
				'set_featured_image'    => __( 'Set QR code image', 'wp-qrcode-me-v-card' ),
				'remove_featured_image' => __( 'Remove QR code image', 'wp-qrcode-me-v-card' ),
				'use_featured_image'    => __( 'Use as QR code image', 'wp-qrcode-me-v-card' ),
			),
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'hierarchical'        => false,
			'show_in_rest'        => true,
			'rewrite'             => array( 'slug' => 'wqm-qr-code' ),
			'supports'            => array( 'title', 'author', 'thumbnail' ),
			'menu_icon'           => 'dashicons-image-filter',
			'menu_position'       => 40,
			'capability_type'     => 'post',
			'has_archive'         => false,
		);

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Add metaboxes
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'wqm-qr-code',
			__( 'QR Code', 'wp-qrcode-me-v-card' ),
			array( self::class, 'render_qr_code_metabox' ),
			self::POST_TYPE,
			'normal'
		);

		add_meta_box(
			'wqm-qr-code-settings',
			__( 'Settings', 'wp-qrcode-me-v-card' ),
			array( self::class, 'render_qr_code_settings_metabox' ),
			self::POST_TYPE,
			'side'
		);
	}

	/**
	 * Get and prepare all metas from db for post type
	 *
	 * @param int $post_id Id of post to get metas
	 *
	 * @return array
	 */
	protected static function get_metas( $post_id ) {
		$metas = get_post_meta( $post_id );
		$data  = array();
		if ( ! empty( $metas ) ) {
			foreach ( $metas as $key => $values ) {
				$data[ $key ] = isset( $values[0] ) ? $values[0] : '';
			}
		}

		return $data;
	}

	/**
	 * Get metas for both settings
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	public static function get_card_metas( $post_id ) {
		$metas = self::get_metas( $post_id );

		return apply_filters( 'wqm_card_metas', array(
			'wqm_type'             => $metas['wqm_type'] ?? 'mecard',
			'wqm_product_name'     => $metas['wqm_product_name'] ?? '',
			'wqm_production_date'  => $metas['wqm_production_date'] ?? '',
			'wqm_ral'              => $metas['wqm_ral'] ?? '',
			'wqm_batch_number'     => $metas['wqm_batch_number'] ?? '',
			'wqm_product_code'     => $metas['wqm_product_code'] ?? '',
			'wqm_datasheet_url'    => $metas['wqm_datasheet_url'] ?? '',
		) );
	}

	/**
	 * Get qr code settings
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	public static function get_qr_code_settings_metas( $post_id ) {
		$metas = self::get_metas( $post_id );

		return apply_filters( 'wqm_card_settings_metas', array(
			'wqm_background_color' => $metas['wqm_background_color'] ?? '#FFFFFF',
			'wqm_foreground_color' => $metas['wqm_foreground_color'] ?? '#000000',
			'wqm_filename' => $metas['wqm_filename'] ?? '',
			'wqm_size'     => $metas['wqm_size'] ?? 200,
		) );
	}

	/**
	 * Show QR code metabox content
	 *
	 * @param WP_Post $post Post object
	 */
	public static function render_qr_code_metabox( $post ) {
		// Add nonce for security
		wp_nonce_field( self::POST_TYPE . '_card_fields', self::POST_TYPE . '_card_fields_nonce' );

		$metas  = self::get_card_metas( $post->ID );
		$types  = array(
			'mecard' => __( 'MeCard', 'wp-qrcode-me-v-card' ),
			'vcard'  => __( 'vCard', 'wp-qrcode-me-v-card' ),
		);

		// Product information fields
		$fields = array(
			'wqm_product_name' => array(
				'name'  => __( 'Product Name', 'wp-qrcode-me-v-card' ),
				'type'  => 'text',
				'value' => $metas['wqm_product_name'] ?? '',
			),
			'wqm_production_date' => array(
				'name'  => __( 'Production Date', 'wp-qrcode-me-v-card' ),
				'type'  => 'text',
				'value' => $metas['wqm_production_date'] ?? '',
				'placeholder' => '1404/01/21',
			),
			'wqm_ral' => array(
				'name'  => __( 'RAL', 'wp-qrcode-me-v-card' ),
				'type'  => 'text',
				'value' => $metas['wqm_ral'] ?? '',
			),
			'wqm_batch_number' => array(
				'name'  => __( 'Batch Number', 'wp-qrcode-me-v-card' ),
				'type'  => 'text',
				'value' => $metas['wqm_batch_number'] ?? '',
			),
			'wqm_product_code' => array(
				'name'  => __( 'Product Code', 'wp-qrcode-me-v-card' ),
				'type'  => 'text',
				'value' => $metas['wqm_product_code'] ?? '',
			),
			'wqm_datasheet_url' => array(
				'name'  => __( 'Datasheet Address', 'wp-qrcode-me-v-card' ),
				'type'  => 'file',
				'value' => $metas['wqm_datasheet_url'] ?? '',
			),
		);

		$data = array(
			'qr_code_id'   => $post->ID,
			'types'        => $types,
			'type_current' => $metas['wqm_type'] ?? 'mecard',
			'fields'       => $fields,
		);

		echo WQM_Common::render( 'qr-code-meta-box.php', $data );
	}

	/**
	 * Show QR Code settings metabox content
	 *
	 * @param WP_Post $post Post object
	 */
	public static function render_qr_code_settings_metabox( $post ) {
		// Add nonce for security
		wp_nonce_field( self::POST_TYPE . '_settings', self::POST_TYPE . '_settings_nonce' );

		$metas = self::get_qr_code_settings_metas( $post->ID );

		$data = array(
			'background_color' => $metas['wqm_background_color'] ?? '#FFFFFF',
			'foreground_color' => $metas['wqm_foreground_color'] ?? '#000000',
			'filename'         => $metas['wqm_filename'] ?? '',
			'size'             => $metas['wqm_size'] ?? 200,
		);

		echo WQM_Common::render( 'qr-code-settings-meta-box.php', $data );
	}

	/**
	 * Save QR code meta box
	 *
	 * @param int $post_id Post ID
	 * @param object $post Post object
	 *
	 * @return int
	 */
	public static function save_meta_boxes( $post_id, $post ) {
		// Save card fields
		if ( isset( $_POST[ self::POST_TYPE . '_card_fields_nonce' ] ) &&
		     wp_verify_nonce( $_POST[ self::POST_TYPE . '_card_fields_nonce' ], self::POST_TYPE . '_card_fields' ) ) {

			// Update card fields
			if ( isset( $_POST['wqm_type'] ) ) {
				update_post_meta( $post_id, 'wqm_type', sanitize_text_field( $_POST['wqm_type'] ) );
			}

			// Product information fields
			if ( isset( $_POST['wqm_product_name'] ) ) {
				update_post_meta( $post_id, 'wqm_product_name', sanitize_text_field( $_POST['wqm_product_name'] ) );
			}
			
			if ( isset( $_POST['wqm_production_date'] ) ) {
				update_post_meta( $post_id, 'wqm_production_date', sanitize_text_field( $_POST['wqm_production_date'] ) );
			}
			
			if ( isset( $_POST['wqm_ral'] ) ) {
				update_post_meta( $post_id, 'wqm_ral', sanitize_text_field( $_POST['wqm_ral'] ) );
			}
			
			if ( isset( $_POST['wqm_batch_number'] ) ) {
				update_post_meta( $post_id, 'wqm_batch_number', sanitize_text_field( $_POST['wqm_batch_number'] ) );
			}
			
			if ( isset( $_POST['wqm_product_code'] ) ) {
				update_post_meta( $post_id, 'wqm_product_code', sanitize_text_field( $_POST['wqm_product_code'] ) );
			}
			
			if ( isset( $_POST['wqm_datasheet_url'] ) ) {
				update_post_meta( $post_id, 'wqm_datasheet_url', esc_url_raw( $_POST['wqm_datasheet_url'] ) );
			}
		}

		// Save QR code settings
		if ( isset( $_POST[ self::POST_TYPE . '_settings_nonce' ] ) &&
		     wp_verify_nonce( $_POST[ self::POST_TYPE . '_settings_nonce' ], self::POST_TYPE . '_settings' ) ) {

			if ( isset( $_POST['wqm_background_color'] ) ) {
				update_post_meta( $post_id, 'wqm_background_color', sanitize_text_field( $_POST['wqm_background_color'] ) );
			}

			if ( isset( $_POST['wqm_foreground_color'] ) ) {
				update_post_meta( $post_id, 'wqm_foreground_color', sanitize_text_field( $_POST['wqm_foreground_color'] ) );
			}

			if ( isset( $_POST['wqm_filename'] ) ) {
				update_post_meta( $post_id, 'wqm_filename', sanitize_text_field( $_POST['wqm_filename'] ) );
			}

			if ( isset( $_POST['wqm_size'] ) ) {
				update_post_meta( $post_id, 'wqm_size', WQM_Common::clear_digits( $_POST['wqm_size'] ) );
			}
		}

		return $post_id;
	}

	/**
	 * @param array $columns
	 *
	 * @return array
	 */
	public static function add_qr_code_column( $columns ) {
		$columns['qr_code'] = __( 'QR Code', 'wp-qrcode-me-v-card' );

		return $columns;
	}

	/**
	 * @param string $column
	 * @param int $post_id
	 */
	public static function show_qr_code_column( $column, $post_id ) {
		if ( 'qr_code' === $column ) {
			$params = array_merge(
				self::get_card_metas( $post_id ),
				self::get_qr_code_settings_metas( $post_id )
			);

			$generator = new WQM_Qr_Code_Generator( $params );
			echo $generator->build();
		}
	}

	/**
	 * Handle permalink ajax call
	 */
	public static function wqm_make_url_permanent() {
		$code_id = $_POST['code_id'] ?? 0;
		if ( $code_id ) {
			$params = array_merge(
				self::get_card_metas( $code_id ),
				self::get_qr_code_settings_metas( $code_id )
			);

			$text = ( new WQM_Qr_Code_Generator( $params ) )->build( $just_code = true );
		}

		die();
	}
}
