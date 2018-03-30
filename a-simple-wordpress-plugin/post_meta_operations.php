<?php
add_action( 'save_post', 'NYDA_save_meta_box' );
add_action( 'add_meta_boxes', 'NYDA_register_meta_boxes' );

/**
 * Register meta box(es).
 */
function NYDA_register_meta_boxes() {
    add_meta_box( 'NYDA-meta-box-country',NYDA_CONSTANT_METABOX_NAME, 'NYDA_display_callback', null, 'normal'  );
}
 
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function NYDA_display_callback( $post ) {
	$NY_country = get_post_meta( $post->ID, NYDA_CONSTANT_INPUT_NAME, true );
	$html = '<div class="inside">
				<table class="form-table">';
	$html .='	<tr>
					<th>
						<label for="'.NYDA_CONSTANT_INPUT_NAME.'">'.NYDA_CONSTANT_INPUT_TEXT.'</label>
					</th>';
    $html .='		<td>
						<input type="text" name="'.NYDA_CONSTANT_INPUT_NAME.'" value="'.$NY_country.'" class="ny-input-class" id="tags"><br/>
						<span class="description">'.NYDA_CONSTANT_INPUT_DESCRIPTION.'</span>
					</td>
				</tr>';
	$html .= '	</table>
			</div">';	
	echo $html;
}
 
/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function NYDA_save_meta_box( $post_id ) {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;
    
    global $post;
    update_post_meta($post->ID, NYDA_CONSTANT_INPUT_NAME, $_POST[NYDA_CONSTANT_INPUT_NAME]);
}