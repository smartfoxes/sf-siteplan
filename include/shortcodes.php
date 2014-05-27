<?php

add_shortcode( 'siteplan' , "sf_siteplan_shortcode");

function sf_siteplan_shortcode($atts, $content="") {
    $class = isset($atts['class']) ? $atts['class'] : null;
    $id = isset($atts['id']) ? $atts['id'] : null;
    
    if($id) {
        $term = get_term( $id, "siteplan" );
    }
    if(!$term) {
        return $content;
    }
    
    $meta = get_option( "_sf_siteplan_$id" );            
    ob_start();
?>
<div class="siteplan-wrapper text-center">
    <div style="display:inline-block;" class="siteplan <?php echo $class; ?>">        
        <ul class="siteplan-lots">
        <?php 
        $args = array( 
            'post_type' => 'siteplan_lot', 
            'tax_query' => array(
        		array(
        			'taxonomy' => 'siteplan',
        			'field' => 'id',
        			'terms' => $id
        		)
        	),
            'posts_per_page' => 1000 
        );
        
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post();
            $status = get_post_meta( get_the_id(), '_sf_siteplan_lot_status', true );
            $latlng = get_post_meta( get_the_id(), '_sf_siteplan_lot_latlng', true );
            $bathrooms = get_post_meta( get_the_id(), '_sf_siteplan_lot_bathrooms', true );
            $bedrooms = get_post_meta( get_the_id(), '_sf_siteplan_lot_bedrooms', true );
            $footage = get_post_meta( get_the_id(), '_sf_siteplan_lot_footage', true );
            
            list($lat,$lng) = explode(",", $latlng);
            $terms = wp_get_post_terms( get_the_id(), "siteplan_type"); 
            $description = "";
            
            $plans = array();
            foreach($terms as $term) {
                $plans[] = $term->name;
                $type_meta = get_option( "_sf_siteplan_type_".$term->term_id );
                if(isset($type_meta["image"])) {
                    $description .= '<img src="'.$type_meta["image"].'">';
                }
                
            }
                        
            $description .= "<ul>";
            if($bedrooms) {
                $description .= "<li>Bedrooms: ".htmlspecialchars($bedrooms)."</li>";
            }
            if($bathrooms) {
                $description .= "<li>Bathrooms: ".htmlspecialchars($bathrooms)."</li>";
            }
            if($footage) {
                $description .= "<li>Sq Footage: ".htmlspecialchars($footage)."</li>";
            }
            
            if($plans) {
                $description .= "<li>Floor Plan: ".htmlspecialchars(join(", ",$plans))."</li>";
            }
            $description .= "</ul>";
            if($status == "Coming Soon") {
                $description = "Floor Plans for this unit are coming soon!";
            }
            echo "<li data-toggle=\"popover\" title=\"Unit ".get_the_title().": $status\" data-html=\"true\" data-content=\"".htmlspecialchars($description)."\" class=\"siteplan-lot siteplan-lot-".preg_replace("/\W+/","-",$status)."\" style=\"left:".$lat."px;top:".$lng."px;\">";
            echo '<span class="sign"></span></li>';
        endwhile;
        
        ?>
        </ul>
        <?php if(isset($meta["image"])):?>    
        <img style="position:relative;z-index:1;" src="<?php echo $meta["image"]; ?>" class="siteplan-image">
        <?php endif; ?>
        
    </div>
</div>
<?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
