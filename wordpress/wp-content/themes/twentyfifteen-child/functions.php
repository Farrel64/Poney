<?php 

add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type( 'annonces',
    array(
      'labels' => array(
        'name' => __( 'Petites Annonces' ),
        'singular_name' => __( 'Petite Annonce' )
      ),
      'public' => true,
      'supports' => array(       
		'title',       
		'editor',       
		'thumbnail',
		'custom-fields',
		'comments',
		),
    )
  );
}
	
	
?>

<?php add_filter( 'pre_get_posts', 'my_get_posts' );

function my_get_posts( $query ) {
 if ( is_home() )
 $query->set( 'post_type', array( 'annonces' ) );

 return $query;
}
?>

<?php 

echo get_post_meta($post->ID, 'ICI_MON_TITRE_DE_CHAMPS', true) 

?>

