<?php 

//ajout de l’autoload de composer
require_once 'vendor/autoload.php';

//ajout de la classe IntlExtension et creation de l’alias IntlExtension
use Twig\Extra\Intl\IntlExtension;

//initialisation twig : chargement du dossier contenant les templates
$loader = new Twig\Loader\FilesystemLoader('templates');

//Paramétrage de l'environnement twig
$twig = new Twig\Environment($loader, [
    /*passe en mode debug à enlever en environnement de prod : permet d'utiliser dans un templates {{dump
    (variable)}} pour afficher le contenu d'une variable. Nécessite l'utilisation de l'extension debug*/
    'debug' => true,
    // Il est possible de définir d'autre variable d'environnement
    //...
]);

//Définition de la timezone pour que les filtres date tiennent compte du fuseau horaire français.
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Paris');

//Ajouter l'extension debug
$twig->addExtension(new \Twig\Extension\DebugExtension());

//Ajout de l'extension d'internationalisation qui permet d'utiliser les filtres de date dans twig
$twig->addExtension(new IntlExtension());

 //Connexion à la base de données en pdo
 $pdo = new PDO('mysql:host=lakartxela;dbname=imahssini_bd', 'imahssini_bd', 'imahssini_bd');

 //Construction de la requête

 $sql = "SELECT R.id as 'recette_id', R.nom as 'recette_nom', C.nom as 'categorie_nom', R.image  as 'recette_image' 
 FROM yabontiap_recette R 
 INNER JOIN yabontiap_categorie C ON R.id_categorie = C.id";

 $pdoStatement = $pdo->prepare($sql);
 $pdoStatement->execute();
 $recettes = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

 $template = $twig->load('recettes_tableau.html.twig');

 echo $template->render(array(
    'recettes' => $recettes,

));




