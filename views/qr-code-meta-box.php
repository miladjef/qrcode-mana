<?php
/**
 * QR Code Meta Box
 */

defined( 'ABSPATH' ) || exit;

/** @var array $types */
/** @var string $type_current */
/** @var array $fields */
/** @var int $qr_code_id */
?>

<div class="wqm-wrapper">
    <div class="wqm-row">
        <div class="wqm-col wqm-col-8">
            <div class="wqm-field-group">
                <div class="wqm-field">
                    <label for="wqm_type"><?php esc_html_e( 'Type', 'wp-qrcode-me-v-card' ); ?></label>
                    <select id="wqm_type" name="wqm_type">
						<?php foreach ( $types as $key => $name ) { ?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $type_current ); ?>><?php echo esc_html( $name ); ?></option>
						<?php } ?>
                    </select>
                </div>
            </div>

            <!-- Product Information Fields -->
            <div class="wqm-field-group">
                <h3><?php _e('Product Information', 'wp-qrcode-me-v-card'); ?></h3>
                
                <div class="wqm-field">
                    <label for="wqm_product_name"><?php _e('Product Name', 'wp-qrcode-me-v-card'); ?></label>
                    <input type="text" id="wqm_product_name" name="wqm_product_name" value="<?php echo esc_attr($fields['wqm_product_name']['value']); ?>">
                </div>
                
                <div class="wqm-field">
                    <label for="wqm_production_date"><?php _e('Production Date', 'wp-qrcode-me-v-card'); ?></label>
                    <input type="text" id="wqm_production_date" name="wqm_production_date" 
                           value="<?php echo esc_attr($fields['wqm_production_date']['value']); ?>"
                           placeholder="1404/01/21">
                </div>
                
                <div class="wqm-field">
                    <label for="wqm_ral"><?php _e('RAL', 'wp-qrcode-me-v-card'); ?></label>
                    <input type="text" id="wqm_ral" name="wqm_ral" value="<?php echo esc_attr($fields['wqm_ral']['value']); ?>">
                </div>
                
                <div class="wqm-field">
                    <label for="wqm_batch_number"><?php _e('Batch Number', 'wp-qrcode-me-v-card'); ?></label>
                    <input type="text" id="wqm_batch_number" name="wqm_batch_number" value="<?php echo esc_attr($fields['wqm_batch_number']['value']); ?>">
                </div>
                
                <div class="wqm-field">
                    <label for="wqm_product_code"><?php _e('Product Code', 'wp-qrcode-me-v-card'); ?></label>
                    <input type="text" id="wqm_product_code" name="wqm_product_code" value="<?php echo esc_attr($fields['wqm_product_code']['value']); ?>">
                </div>
                
                <div class="wqm-field">
                    <label for="wqm_datasheet_url"><?php _e('Datasheet Address', 'wp-qrcode-me-v-card'); ?></label>
                    <div class="wqm-file-upload">
                        <input type="text" id="wqm_datasheet_url" name="wqm_datasheet_url" value="<?php echo esc_attr($fields['wqm_datasheet_url']['value']); ?>" readonly>
                        <button type="button" class="button wqm-upload-pdf"><?php _e('Upload PDF', 'wp-qrcode-me-v-card'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="wqm-col wqm-col-4">
            <div class="wqm-field-group">
                <h3><?php esc_html_e( 'QR Code Preview', 'wp-qrcode-me-v-card' ); ?></h3>
                <div id="wqm-qr-code-preview">
					<?php
					if ( $qr_code_id ) {
						$params   = array_merge(
							WQM_QR_Code_Type::get_card_metas( $qr_code_id ),
							WQM_QR_Code_Type::get_qr_code_settings_metas( $qr_code_id )
						);
						$instance = new WQM_Qr_Code_Generator( $params );
						echo $instance->build();
					}
					?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        // PDF upload functionality
        $('.wqm-upload-pdf').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var urlField = button.siblings('input[name="wqm_datasheet_url"]');
            
            var mediaUploader = wp.media({
                title: '<?php _e('Select or Upload PDF Datasheet', 'wp-qrcode-me-v-card'); ?>',
                button: {
                    text: '<?php _e('Use this PDF', 'wp-qrcode-me-v-card'); ?>'
                },
                library: {
                    type: 'application/pdf'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                urlField.val(attachment.url);
            });
            
            mediaUploader.open();
        });
    });
</script>
