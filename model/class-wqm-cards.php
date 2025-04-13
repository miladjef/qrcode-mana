<?php
/**
 * Base class for cards functionality
 */

defined( 'ABSPATH' ) || exit;

class WQM_Cards {

	public static function get_vcard_format( array $fields ) {
		$vcard_version = apply_filters( 'wqm_vcard_version', '3.0' );
		$vcard_text    = "BEGIN:VCARD\r\n";
		$vcard_text    .= "VERSION:{$vcard_version}\r\n";

		// Product information
		if ( ! empty( $fields['wqm_product_name'] ) ) {
			$vcard_text .= "PRODUCT_NAME:" . self::escape_vcard_text( $fields['wqm_product_name'] ) . "\r\n";
		}

		if ( ! empty( $fields['wqm_production_date'] ) ) {
			$vcard_text .= "PRODUCTION_DATE:" . self::escape_vcard_text( $fields['wqm_production_date'] ) . "\r\n";
		}

		if ( ! empty( $fields['wqm_ral'] ) ) {
			$vcard_text .= "RAL:" . self::escape_vcard_text( $fields['wqm_ral'] ) . "\r\n";
		}

		if ( ! empty( $fields['wqm_batch_number'] ) ) {
			$vcard_text .= "BATCH_NUMBER:" . self::escape_vcard_text( $fields['wqm_batch_number'] ) . "\r\n";
		}

		if ( ! empty( $fields['wqm_product_code'] ) ) {
			$vcard_text .= "PRODUCT_CODE:" . self::escape_vcard_text( $fields['wqm_product_code'] ) . "\r\n";
		}

		if ( ! empty( $fields['wqm_datasheet_url'] ) ) {
			$vcard_text .= "DATASHEET_URL:" . self::escape_vcard_text( $fields['wqm_datasheet_url'] ) . "\r\n";
		}

		$vcard_text .= "END:VCARD";

		return $vcard_text;
	}

	public static function get_mecard_format( array $fields ) {
		$mecard_text = "MECARD:";

		// Product information
		if ( ! empty( $fields['wqm_product_name'] ) ) {
			$mecard_text .= "PRODUCT_NAME:" . self::escape_mecard_text( $fields['wqm_product_name'] ) . ";";
		}

		if ( ! empty( $fields['wqm_production_date'] ) ) {
			$mecard_text .= "PRODUCTION_DATE:" . self::escape_mecard_text( $fields['wqm_production_date'] ) . ";";
		}

		if ( ! empty( $fields['wqm_ral'] ) ) {
			$mecard_text .= "RAL:" . self::escape_mecard_text( $fields['wqm_ral'] ) . ";";
		}

		if ( ! empty( $fields['wqm_batch_number'] ) ) {
			$mecard_text .= "BATCH_NUMBER:" . self::escape_mecard_text( $fields['wqm_batch_number'] ) . ";";
		}

		if ( ! empty( $fields['wqm_product_code'] ) ) {
			$mecard_text .= "PRODUCT_CODE:" . self::escape_mecard_text( $fields['wqm_product_code'] ) . ";";
		}

		if ( ! empty( $fields['wqm_datasheet_url'] ) ) {
			$mecard_text .= "DATASHEET_URL:" . self::escape_mecard_text( $fields['wqm_datasheet_url'] ) . ";";
		}

		$mecard_text .= ";";

		return $mecard_text;
	}

	/**
	 * Escape special characters in vCard format
	 * 
	 * @param string $text
	 * @return string
	 */
	protected static function escape_vcard_text( $text ) {
		$text = str_replace( array( '\\', ',', ';' ), array( '\\\\', '\\,', '\\;' ), $text );
		// Ensure proper encoding for Persian characters
		return $text;
	}

	/**
	 * Escape special characters in MeCard format
	 * 
	 * @param string $text
	 * @return string
	 */
	protected static function escape_mecard_text( $text ) {
		$text = str_replace( array( '\\', '"', ':' ), array( '\\\\', '\\"', '\\:' ), $text );
		// Ensure proper encoding for Persian characters
		return $text;
	}
}
