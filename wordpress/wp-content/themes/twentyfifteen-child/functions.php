<?php 

function projet_module() {
  $args = array(
    'label' => __('Petites Annonces'),
    'singular_label' => __('Projet'),
    'public' => true,
    'show_ui' => true,
    '_builtin' => false, // It's a custom post type, not built in
    '_edit_link' => 'post.php?post=%d',
    'capability_type' => 'post',
    'hierarchical' => false,
    'rewrite' => array("slug" => "projects"),
    'query_var' => "Petites Annonces", // This goes to the WP_Query schema
    'supports' => array('title', 'editor', 'thumbnail') //titre + zone de texte + champs personnalisés + miniature valeur possible : 'title','editor','author','thumbnail','excerpt'
);
register_post_type( 'projet' , $args ); // enregistrement de l'entité projet basé sur les arguments ci-dessus
register_taxonomy_for_object_type('post_tag', 'projet','show_tagcloud=1&hierarchical=true'); // ajout des mots clés pour notre custom post type
add_action("admin_init", "admin_init"); //function pour ajouter des champs personnalisés
add_action('save_post', 'save_custom'); //function pour la sauvegarde de nos champs personnalisés
}
add_action('init', 'projet_module');

function admin_init(){ //initialisation des champs spécifiques
add_meta_box("Prix", "Prix", "prix", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction prix()
}
function prix(){     //La fonction qui affiche notre champs personnalisé dans l'administration
global $post;
$custom = get_post_custom($post->ID); //fonction pour récupérer la valeur de notre champ
$prix = $custom["prix"][0];
?>
<input size="70" type="text" value="<?php echo $prix;?>" name="prix"/>
<?php
}
function save_custom(){ //sauvegarde des champs spécifiques
  global $post;
if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { // fonction pour éviter  le vidage des champs personnalisés lors de la sauvegarde automatique
  return $postID;
}
update_post_meta($post->ID, "prix", $_POST["prix"]); //enregistrement dans la base de données
}

$labelsCat1 = array(
  'name' => _x( 'Clients', 'post type general name' ),
  'singular_name' => _x( 'Client', 'post type singular name' ),
  'add_new' => _x( 'Add New', 'client' ),
  'add_new_item' => __( 'Ajouter un client' ),
  'edit_item' => __( 'Modifier le client' ),
  'new_item' => __( 'Nouveau client' ),
  'view_item' => __( 'Voir le client' ),
  'search_items' => __( 'Rechercher des clients' ),
  'not_found' =>  __( 'Aucun client trouvé' ),
  'not_found_in_trash' => __( 'Aucun client trouvé' ),
  'parent_item_colon' => ''
  );
register_taxonomy("clients", array("projet"), array("hierarchical" => true, "labels" => $labelsCat1, "rewrite" => true));


function the_meta_prix() {
  if ( $keys = get_post_custom_keys() ) {
    foreach ( (array) $keys as $key ) {
      $keyt = trim($key);
      if ( is_protected_meta( $keyt, 'post' ) )
        continue;
      $values = array_map('trim', get_post_custom_values($key));
      $value = implode($values,', ');

      /**
       * Filter the HTML output of the li element in the post custom fields list.
       *
       * @since 2.2.0
       *
       * @param string $html  The HTML output for the li element.
       * @param string $key   Meta key.
       * @param string $value Meta value.
       */
      echo apply_filters( 'the_meta_key', "$key: $value\n", $key, $value );
    }
  }
}
?>
