<?php
/**
 * QR Code generator class
 */

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Exception\WriterException;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

defined( 'ABSPATH' ) || exit;

class WQM_Qr_Code_Generator {

	/**
	 * @var array Params
	 */
	public $params = [];

	/**
	 * @var string QR code data as text
	 */
	public $text;

	/**
	 * WQM_Qr_Code_Generator constructor.
	 *
	 * @param array $params Params to generate QR code
	 */
	public function __construct( $params = [] ) {
		$this->params = $params;
	}

	/**
	 * Build QR code string
	 *
	 * @param bool $just_code Show just text or generate QR code
	 *
	 * @return string
	 */
	public function build( $just_code = false ) {
		// Format the data for product information
		$data = '';
		
		if (!empty($this->params['wqm_product_name'])) {
			$data .= "Product Name: " . $this->params['wqm_product_name'] . "\n";
		}
		
		if (!empty($this->params['wqm_production_date'])) {
			$data .= "Production Date: " . $this->params['wqm_production_date'] . "\n";
		}
		
		if (!empty($this->params['wqm_ral'])) {
			$data .= "RAL: " . $this->params['wqm_ral'] . "\n";
		}
		
		if (!empty($this->params['wqm_batch_number'])) {
			$data .= "Batch Number: " . $this->params['wqm_batch_number'] . "\n";
		}
		
		if (!empty($this->params['wqm_product_code'])) {
			$data .= "Product Code: " . $this->params['wqm_product_code'] . "\n";
		}
		
		if (!empty($this->params['wqm_datasheet_url'])) {
			$data .= "Datasheet: " . $this->params['wqm_datasheet_url'] . "\n";
		}

		$this->text = $data;

		if ( $just_code ) {
			return $this->text;
		}

		return $this->render_qr_code();
	}

	/**
	 * Generate QR code image
	 *
	 * @return string
	 */
	protected function render_qr_code() {
		if ( empty( $this->text ) ) {
			return '';
		}

		// Define colors
		$background_color = $this->hex_to_rgb( $this->params['wqm_background_color'] ?? '#FFFFFF' );
		$foreground_color = $this->hex_to_rgb( $this->params['wqm_foreground_color'] ?? '#000000' );
		$size             = $this->params['wqm_size'] ?? 200;

		$renderer = new ImageRenderer(
			new RendererStyle( $size, 0, null, null, Fill::uniformColor( new Rgb( $background_color[0], $background_color[1], $background_color[2] ), new Rgb( $foreground_color[0], $foreground_color[1], $foreground_color[2] ) ) ),
			new SvgImageBackEnd()
		);
		$writer   = new Writer( $renderer );

		try {
			$svg = $writer->writeString( $this->text, Encoder::DEFAULT_BYTE_MODE_ECODING, ErrorCorrectionLevel::L );
			$svg = str_replace( '<?xml version="1.0" encoding="UTF-8"?>', '', $svg );

			return '<div class="wqm-qrcode">' . $svg . '</div>';
		} catch ( WriterException $e ) {
			return '';
		}
	}

	/**
	 * Convert hex color to RGB array
	 *
	 * @param string $hex Hex color
	 *
	 * @return array
	 */
	private function hex_to_rgb( $hex ) {
		$hex = str_replace( '#', '', $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}

		return array( $r, $g, $b );
	}
}
public function build( $just_code = false ) {
    // Format the data for product information
    $data = '';
    
    // Ensure that all text is UTF-8 encoded
    mb_internal_encoding('UTF-8');
    
    if (!empty($this->params['wqm_product_name'])) {
        $data .= "Product Name: " . $this->params['wqm_product_name'] . "\n";
    }
    
    if (!empty($this->params['wqm_production_date'])) {
        $data .= "Production Date: " . $this->params['wqm_production_date'] . "\n";
    }
    
    if (!empty($this->params['wqm_ral'])) {
        $data .= "RAL: " . $this->params['wqm_ral'] . "\n";
    }
    
    if (!empty($this->params['wqm_batch_number'])) {
        $data .= "Batch Number: " . $this->params['wqm_batch_number'] . "\n";
    }
    
    if (!empty($this->params['wqm_product_code'])) {
        $data .= "Product Code: " . $this->params['wqm_product_code'] . "\n";
    }
    
    if (!empty($this->params['wqm_datasheet_url'])) {
        $data .= "Datasheet: " . $this->params['wqm_datasheet_url'] . "\n";
    }

    $this->text = $data;

    if ( $just_code ) {
        return $this->text;
    }

    return $this->render_qr_code();
}