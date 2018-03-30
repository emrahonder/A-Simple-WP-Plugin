<?php


// Option page
function NYDA_theme_optionpage() {
	$option_details = array();
	$option_details['title'] = NYDA_MENU_NAME_OPTIONS;
	$option_details['slug'] = NYDA_MENU_SLUG_OPTIONS;
	$option_item = array();
	

	$option_item['name'] = 'Character Count for starting autocomplete';
	$option_item['key'] = NYDA_OPTION_CHAR_COUNT;
	$option_item['type'] = 'numeric';
	$option_item['description'] = 'This value may be min. 2 and max. 10';
	$option_details['option'][] = $option_item;
	
	NYDA_setup_create_option_page($option_details);
}


// This function helps to generate option page quickly. It was developed for a lib by me before.
function NYDA_setup_create_option_page($options = null) {
    $message_controller = false;
    $option_count = count($options['option']);
    if (isset($_POST['addoption'])) {


        for ($i = 0; $i < $option_count; $i++) {
            $option_key = $options['option'][$i]['key'];
            if (isset($_POST[$option_key])) {
                update_option($option_key, $_POST[$option_key]);
                $message_controller = true;
            } else {
                update_option($option_key, 'false');
            }
        }
    }
    if ($message_controller) {
        ?>
        <div class="updated below-h2" id="message">
            <p><?php _e('Settings are updated.', 'NYDA'); ?></p>
        </div>
        <?php
    }
    ?>
    <div class="wrap">
        <div class="icon32" id="icon-options-general"><br>
        </div>
        <h2 id="gp-add-options"><?php echo $options['title']; ?></h2>
        <form id="addoption" name="addoption" method="post" action="<?php echo NYDA_setup_get_option_page_url($options['slug']); ?>">
            <table class="form-table">
                <tbody>
                    <?php
                    for ($i = 0; $i < $option_count; $i++) {
                        $option_name = $options['option'][$i]['name'];
                        $option_key = $options['option'][$i]['key'];
                        $option_type = $options['option'][$i]['type'];
                        if(isset( $options['option'][$i]['description'])){
                            $option_description = $options['option'][$i]['description'];
                        }else {
                           $option_description = ''; 
                        }
                        ?>
                        <tr>
                            <th scope="row"><label><?php echo $option_name; ?></label></th>
                            <td><?php
                switch ($option_type) {
                    case 'page':
                        wp_dropdown_pages(array('name' => $option_key,
                            'show_option_none' => __('&mdash; Select &mdash;', 'NYDA'),
                            'selected' => get_option($option_key)));
                        break;
                    case 'category':
                        wp_dropdown_categories(array('name' => $option_key,
                            'show_option_none' => __('&mdash; Select &mdash;', 'NYDA'),
                            'selected' => get_option($option_key),
                            'hide_empty' => 0));
                        break;
                    case 'custom-taxonomy':
                        wp_dropdown_categories(array('name' => $option_key,
                            'show_option_none' => __('&mdash; Select &mdash;', 'NYDA'),
                            'selected' => get_option($option_key),
                            'taxonomy' => $options['option'][$i]['custom-type'],
                            'hide_empty' => 0));
                        break;
                    case 'custom-post-type':
						$post_type = $options['option'][$i]['custom-type'];
						$args = array(
							'numberposts'     => 99999999,
							'orderby'         => 'title',
						//    'order'           => 'ASC',
							'post_type'       => $post_type,
							'post_status'     => null);                    
						$specialposts = get_posts( $args ); 
						//print_r( $specialposts );
						echo '<select name="'.$option_key.'">';
								echo '<option value="">'.__( '&mdash; Seçiniz &mdash;','NYDA' ).'</option>';
								foreach($specialposts as $specialpost){
									echo '<option value="'.$specialpost->ID.'"';
									if ($specialpost->ID == get_option($option_key)) {
											echo ' selected="selected" ';
									}
									echo '>'.$specialpost->post_title.'</option>';
								}
						echo '</select>';
						

                        break;

                        break;
                    case 'checkbox':
                        echo '<input type="checkbox" name="' . $option_key . '" value="true" class="NYDA-input-class"';
                        if (get_option($option_key) == 'true') {
                            echo ' checked="checked"';
                        }
                        echo '>';
                        break;
                    case 'combobox':
                        $combobox = $options['option'][$i]['combobox'];
                        echo '<select name="' . $option_key . '">';
                        echo '<option>' . __('&mdash; Select &mdash;', 'NYDA') . '</option>';
                        for ($j = 0; $j < count($combobox); $j++) {
                            echo '<option value="' . $combobox[$j][key] . '"';
                            if ($combobox[$j]['key'] == get_option($option_key)) {
                                echo ' selected="selected" ';
                            }
                            echo '>' . $combobox[$j]['name'] . '</option>';
                        }
                        echo '</select>';

                        break;
                    case 'textarea':
                        echo '<textarea class="large-text code" id="moderation_keys" cols="30" rows="5" name="' . $option_key . '" style="width: 855px; height: 163px;">' . get_option($option_key) . '</textarea>';
                        break;
                    case 'editor':
                        if(function_exists('wp_editor')){
                            wp_editor( get_option($option_key), $option_key, $settings = array('media_buttons' => false, 'textarea_rows' => 3) );
                        }else {
                            echo '<textarea class="large-text code" id="moderation_keys" cols="30" rows="5" name="' . $option_key . '" style="width: 855px; height: 163px;">' . get_option($option_key) . '</textarea>';
                        }
                        break;                    
                    case 'numeric':
                        echo '<input type="number" min="2" max="10" name="' . $option_key . '" value="' . get_option($option_key) . '" class="NYDA-input-class">';
						
                        break;
					
                    case 'input':
                    default:
                        echo '<input type="input" name="' . $option_key . '" value="' . get_option($option_key) . '" class="NYDA-input-class">';
                        break;
                }
                        ?>
                                <i><?php echo $option_description; ?></i></td>
                        </tr>		
        <?php
    }
    ?>
                </tbody>
            </table>
            <p class="submit"><input type="submit" value="<?php _e('Send', 'NYDA') ?>" class="button-primary" id="addoptionsub" name="addoption"></p>
        </form>
    </div>
    <?php
}


function NYDA_setup_get_option_page_url($slug) {
    return 'admin.php?page=' . $slug;
}