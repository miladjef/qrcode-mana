<?php
/**
 * QR Code Settings Meta Box
 */

defined( 'ABSPATH' ) || exit;

/** @var string $background_color */
/** @var string $foreground_color */
/** @var string $filename */
/** @var int $size */
?>

<div class="wqm-wrapper">
    <div class="wqm-field-group">
        <div class="wqm-field">
            <label for="wqm_background_color"><?php esc_html_e( 'Background Color', 'wp-qrcode-me-v-card' ); ?></label>
            <input type="text" id="wqm_background_color" name="wqm_background_color" class="wqm-color-picker" value="<?php echo esc_attr( $background_color ); ?>">
        </div>

        <div class="wqm-field">
            <label for="wqm_foreground_color"><?php esc_html_e( 'Foreground Color', 'wp-qrcode-me-v-card' ); ?></label>
            <input type="text" id="wqm_foreground_color" name="wqm_foreground_color" class="wqm-color-picker" value="<?php echo esc_attr( $foreground_color ); ?>">
        </div>

        <div class="wqm-field">
            <label for="wqm_filename"><?php esc_html_e( 'Filename', 'wp-qrcode-me-v-card' ); ?></label>
            <input type="text" id="wqm_filename" name="wqm_filename" value="<?php echo esc_attr( $filename ); ?>">
            <p class="description">
                <?php esc_html_e( 'Without file extension', 'wp-qrcode-me-v-card' ); ?>
            </p>
        </div>

        <div class="wqm-field">
            <label for="wqm_size"><?php esc_html_e( 'Size (px)', 'wp-qrcode-me-v-card' ); ?></label>
            <input type="number" id="wqm_size" name="wqm_size" value="<?php echo esc_attr( $size ); ?>" min="100" max="1000" step="10">
        </div>
    </div>
</div>
