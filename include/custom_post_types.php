<?php

/**
* Add custom post types
*/
add_action( 'init', 'sf_siteplan_create_post_type' );
//add_action( 'add_meta_boxes', 'sf_siteplan_landing_pages_metaboxes' );

function sf_siteplan_create_post_type() {
    register_post_type( 'siteplan_lot',
		array(
			'labels' => array(
				'name' => __( 'Lots' ),
				'singular_name' => __( 'Lot' ),
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Lot',    		    
			),
			'register_meta_box_cb' => 'sf_siteplan_lot_metaboxes',
    		
    		'public' => true,
    		'has_archive' => false,
    		'show_ui' => true,
    		'supports' => array(
    		    'title'
    	    ),
    	    'rewrite' => array(
    	        'slug' => 'lot',
    	        'with_front' => false,
    	        'ep_mask' => EP_POSTS 
    	    ),
    	    'capability_type' => 'post'
		)
	);
	
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Site Plans', 'taxonomy general name' ),
		'singular_name'     => _x( 'Site Plan', 'taxonomy singular name' ),
		'all_items'         => __( 'All Site Plans' ),
		'edit_item'         => __( 'Edit Site Plan' ),
		'update_item'       => __( 'Update Site Plan' ),
		'add_new_item'      => __( 'Add New Site Plan' ),
		'new_item_name'     => __( 'New Site Plan Name' ),
		'menu_name'         => __( 'Site Plans' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'siteplan' ),
	);

	register_taxonomy( 'siteplan', array( 'siteplan_lot' ), $args );

    // Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Lot Types', 'taxonomy general name' ),
		'singular_name'     => _x( 'Lot Type', 'taxonomy singular name' ),
		'all_items'         => __( 'All Lot Types' ),
		'edit_item'         => __( 'Edit Lot Type' ),
		'update_item'       => __( 'Update Lot Type' ),
		'add_new_item'      => __( 'Add New Lot Type' ),
		'new_item_name'     => __( 'New Lot Type Name' ),
		'menu_name'         => __( 'Lot Types' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'lot-types' ),
	);

	register_taxonomy( 'siteplan_type', array( 'siteplan_lot' ), $args );

}

// Site Plan custom fields for the image
function sf_siteplan_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="sf_siteplan[image]"><?php _e( 'Site Plan Image', 'sf_siteplan' ); ?></label>
		<input type="text" name="sf_siteplan[image]" id="_sf_siteplan_image" />  
        <input class="button upload_button" name="_sf_siteplan_image_button" id="_sf_siteplan_image_button" value="Upload" />
	</div>
<?php
}
add_action( 'siteplan_add_form_fields', 'sf_siteplan_add_new_meta_field', 10, 2 );

// Edit term page
function sf_siteplan_edit_meta_field($term) {
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "_sf_siteplan_$t_id" ); ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="sf_siteplan[image]"><?php _e( 'Site Plan Image', 'sf_siteplan' ); ?></label></th>
		<td>
			<input type="text" name="sf_siteplan[image]" id="_sf_siteplan_image" value="<?php echo esc_attr( $term_meta['image'] ) ? esc_attr( $term_meta['image'] ) : ''; ?>"/>  
            <input class="button upload_button" name="_sf_siteplan_image_button" id="_sf_siteplan_image_button" value="Upload" />
		</td>
	</tr>
<?php
}
add_action( 'siteplan_edit_form_fields', 'sf_siteplan_edit_meta_field', 10, 2 );

// Save extra taxonomy fields callback function.
function save_sf_siteplan_custom_meta( $term_id ) {
    if ( isset( $_POST['sf_siteplan'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "_sf_siteplan_$t_id" );
		$cat_keys = array_keys( $_POST['sf_siteplan'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['sf_siteplan'][$key] ) ) {
				$term_meta[$key] = $_POST['sf_siteplan'][$key];
			}
		}
		// Save the option array.
		update_option( "_sf_siteplan_$t_id", $term_meta );
	}
}  
add_action( 'edited_siteplan', 'save_sf_siteplan_custom_meta', 10, 2 );  
add_action( 'create_siteplan', 'save_sf_siteplan_custom_meta', 10, 2 );



// Site Plan custom fields for the image
function sf_siteplan_type_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="sf_siteplan_type[image]"><?php _e( 'Type Image', 'sf_siteplan_type' ); ?></label>
		<input type="text" name="sf_siteplan_type[image]" id="_sf_siteplan_type_image" />  
        <input class="button upload_button" name="_sf_siteplan_type_image_button" id="_sf_siteplan_type_image_button" value="Upload" />
	</div>
<?php
}
add_action( 'siteplan_type_add_form_fields', 'sf_siteplan_type_add_new_meta_field', 10, 2 );

// Edit term page
function sf_siteplan_type_edit_meta_field($term) {
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "_sf_siteplan_type_$t_id" ); ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="sf_siteplan_type[image]"><?php _e( 'Type Image', 'sf_siteplan_type' ); ?></label></th>
		<td>
			<input type="text" name="sf_siteplan_type[image]" id="_sf_siteplan_type_image" value="<?php echo esc_attr( $term_meta['image'] ) ? esc_attr( $term_meta['image'] ) : ''; ?>"/>  
            <input class="button upload_button" name="_sf_siteplan_type_image_button" id="_sf_siteplan_type_image_button" value="Upload" />
		</td>
	</tr>
<?php
}
add_action( 'siteplan_type_edit_form_fields', 'sf_siteplan_type_edit_meta_field', 10, 2 );

// Save extra taxonomy fields callback function.
function save_sf_siteplan_type_custom_meta( $term_id ) {
    if ( isset( $_POST['sf_siteplan_type'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "_sf_siteplan_type_$t_id" );
		$cat_keys = array_keys( $_POST['sf_siteplan_type'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['sf_siteplan_type'][$key] ) ) {
				$term_meta[$key] = $_POST['sf_siteplan_type'][$key];
			}
		}
		// Save the option array.
		update_option( "_sf_siteplan_type_$t_id", $term_meta );
	}
}  
add_action( 'edited_siteplan_type', 'save_sf_siteplan_type_custom_meta', 10, 2 );  
add_action( 'create_siteplan_type', 'save_sf_siteplan_type_custom_meta', 10, 2 );



function sf_siteplan_lot_metaboxes() {
    add_meta_box(
        'sf_siteplan_lot_options',
        __( 'Lot Options', 'sf_siteplan_textdomain' ),
        'sf_siteplan_lot_metabox_html',
        "siteplan_lot",
        "normal"
    );
}

function sf_siteplan_lot_metabox_html($post) {
    
    wp_nonce_field( 'sf_siteplan_lot_options', 'sf_siteplan_lot_options_metabox_nonce' );
    
    $status = get_post_meta( $post->ID, '_sf_siteplan_lot_status', true );
    $latlng = get_post_meta( $post->ID, '_sf_siteplan_lot_latlng', true );
    $bathrooms = get_post_meta( $post->ID, '_sf_siteplan_lot_bathrooms', true );
    $bedrooms = get_post_meta( $post->ID, '_sf_siteplan_lot_bedrooms', true );
    $footage = get_post_meta( $post->ID, '_sf_siteplan_lot_footage', true );
    ?>
    <p>
    <label for="sf_siteplan_status">Lot Status</label>
    <select class="widefat" type="text" id="sf_siteplan_lot_status" name="sf_siteplan_lot_status">
        <option <?php echo ($status && $status == "Available") ? "selected":"";?> value="Available">Available</option>
        <option <?php echo ($status  && $status == "Coming Soon") ? "selected":"";?> value="Coming Soon">Coming Soon</option>
        <option <?php echo ($status  && $status == "Showhome") ? "selected":"";?> value="Showhome">Showhome</option>
        <option <?php echo ($status  && $status == "Sold") ? "selected":"";?> value="Sold">Sold</option>
    </select>    
    </p>
    <p>
    <label for="sf_siteplan_bathrooms">Number of Bathrooms</label>
    <input type=text class="widefat" type="text" id="sf_siteplan_lot_bathrooms" name="sf_siteplan_lot_bathrooms" value="<?php echo esc_attr($bathrooms);?>">          
    </p>
    <p>
    <label for="sf_siteplan_bedrooms">Number of Bedrooms</label>
    <input type=text class="widefat" type="text" id="sf_siteplan_lot_bedrooms" name="sf_siteplan_lot_bedrooms" value="<?php echo esc_attr($bedrooms);?>">          
    </p>
    <p>
    <label for="sf_siteplan_footage">Sq. Footage</label>
    <input type=text class="widefat" type="text" id="sf_siteplan_lot_footage" name="sf_siteplan_lot_footage" value="<?php echo esc_attr($footage);?>">          
    </p>
    
    <p>
    <label for="sf_siteplan_latlng">Lot Position on the Plan</label>
    <input type=text class="widefat" type="text" id="sf_siteplan_lot_latlng" name="sf_siteplan_lot_latlng" value="<?php echo esc_attr($latlng);?>">          
    <?php
        $terms = wp_get_post_terms( $post->ID, "siteplan"); 
        $meta = null;
        foreach($terms as $term) {
            $meta = get_option( "_sf_siteplan_" . $term->term_id );            
            break;
        }
        
    ?>
        <?php if(isset($meta["image"])):?> 
            Click on the map to set position   
            <div style="overflow: hidden;position:relative;">                
                <img style="left:-150px;position:relative;display:block;" src="<?php echo $meta["image"]; ?>" class="siteplan-image">
            </div>
        <?php endif; ?>
    </p>
    <?php		
}

/**
 * When the post is saved, saves our custom data.
 * @param int $post_id The ID of the post being saved.
 */
function sf_siteplan_lot_save_postdata( $post_id ) {
    // Check if our nonce is set.
    
    if ( ! isset( $_POST['sf_siteplan_lot_options_metabox_nonce'] ) ) {
        return $post_id;
    }
    $nonce = $_POST['sf_siteplan_lot_options_metabox_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'sf_siteplan_lot_options' ) ) {
        return $post_id;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // Check the user's permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;    
    }
    $status = sanitize_text_field( $_POST['sf_siteplan_lot_status'] );
    update_post_meta( $post_id, '_sf_siteplan_lot_status', $status );    
    
    $latlng = sanitize_text_field( $_POST['sf_siteplan_lot_latlng'] );
    update_post_meta( $post_id, '_sf_siteplan_lot_latlng', $latlng ); 
    
    $bathrooms = sanitize_text_field( $_POST['sf_siteplan_lot_bathrooms'] );
    update_post_meta( $post_id, '_sf_siteplan_lot_bathrooms', $bathrooms); 
    
    $bedrooms = sanitize_text_field( $_POST['sf_siteplan_lot_bedrooms'] );
    update_post_meta( $post_id, '_sf_siteplan_lot_bedrooms', $bedrooms ); 
    
    $footage = sanitize_text_field( $_POST['sf_siteplan_lot_footage'] );
    update_post_meta( $post_id, '_sf_siteplan_lot_footage', $footage );    
}
add_action( 'save_post', 'sf_siteplan_lot_save_postdata' );
