<?php

function admin_init(){ //initialisation des champs spécifiques
add_meta_box("url_projet", "Url du projet", "url_projet", "projet", "normal", "low");  //il s'agit de notre champ personnalisé qui apelera la fonction url_projet()
}
function url_projet(){     //La fonction qui affiche notre champs personnalisé dans l'administration
global $post;
$custom = get_post_custom($post->ID); //fonction pour récupérer la valeur de notre champ
$url_projet = $custom["url_projet"][0];
?>
<input size="70" type="text" value="<?php echo $url_projet;?>" name="url_projet"/>
<?php
}
function save_custom(){ //sauvegarde des champs spécifiques
global $post;
if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { // fonction pour éviter  le vidage des champs personnalisés lors de la sauvegarde automatique
return $postID;
}
update_post_meta($post->ID, "url_projet", $_POST["url_projet"]); //enregistrement dans la base de données
}

add_action("admin_init", "admin_init"); //function pour ajouter des champs personnalisés
add_action('save_post', 'save_custom'); //function pour la sauvegarde de nos champs personnalisés