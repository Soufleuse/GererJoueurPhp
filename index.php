<!DOCTYPE html>
<?php $page = "index"; ?>
<?php
// index.php - Gestion des joueurs de ligue
require_once 'config/api_config.php';
require_once 'classes/JoueurService.php';
require_once 'classes/Joueur.php';

// Logique PHP
$joueurs = [];
$message = '';

$joueurService = new JoueurService();

if ($_POST['action'] ?? '' === 'ajouter') {
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $datenaissance = $_POST['dateNaissance'] ?? '';
    $villeNaissance = $_POST['villeNaissance'] ?? '';
    $paysOrigine = $_POST['paysOrigine'] ?? '';

    /*var_dump($prenom);
    var_dump($nom);
    var_dump($datenaissance);
    var_dump($villeNaissance);
    var_dump($paysOrigine);*/
    
    if ($prenom && $nom && $datenaissance && $villeNaissance && $paysOrigine) {
        echo "Sur le point d'ajouter le joueur<br>";
        $joueur = new Joueur($prenom, $nom, new DateTime($datenaissance), $villeNaissance, $paysOrigine);
        echo "Appel à ajouterJoueur<br>";
        if($joueurService->ajouterJoueur($joueur->toArray()) !== false) {
            $joueurs[] = $joueur;
            $message = "Joueur $nom ajouté avec succès !";
        }
        else {
            $message = "Erreur à l'ajout du joueur";
        }
    }
    else {
        $message = "Veuillez entrer les informations requises.";
    }
}

$joueurs = $joueurService->obtenirTousLesJoueurs();
//var_dump($joueurs);
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/gererjoueur/images/balle-baseball.png">
    <title>Gestion des Joueurs - Ligue</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .joueur { border: 1px solid #ccc; padding: 10px; margin: 5px 0; }
        .form-group { margin: 10px 0; }
        .message { color: green; padding: 10px; background: #f0f8ff; }
    </style>
</head>
<body>
    <h1>Gestion des Joueurs de Ligue</h1>
    
    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <h2>Ajouter un joueur</h2>
    <form method="POST">
        <div class="form-group">
            <label for="prenom">Prénom du joueur:</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>
        <div class="form-group">
            <label for="nom">Nom du joueur:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div class="form-group">
            <label for="dateNaissance">Date de naissance:</label>
            <input type="date" id="dateNaissance" name="dateNaissance" required>
        </div>
        <div class="form-group">
            <label for="villeNaissance">Ville de naissance:</label>
            <input type="text" id="villeNaissance" name="villeNaissance" required>
        </div>
        <div class="form-group">
            <label for="paysOrigine">Pays de naissance:</label>
            <input type="text" id="paysOrigine" name="paysOrigine" required>
        </div>
        
        <!--div class="form-group">
            <label for="position">Position:</label>
            <select id="position" name="position" required>
                <option value="">Choisir une position</option>
                <option value="Centre">Centre</option>
                <option value="Ailier gauche">Ailier gauche</option>
                <option value="Ailier droit">Ailier droit</option>
                <option value="Défenseur">Défenseur</option>
                <option value="Gardien">Gardien</option>
            </select>
        </div-->
        
        <button type="submit" name="action" value="ajouter">Ajouter le joueur</button>
    </form>
    
    <h2>Liste des joueurs (<?php echo count($joueurs); ?> dans la liste)</h2>
    <?php if (empty($joueurs)): ?>
        <p>Aucun joueur enregistré.</p>
    <?php else: ?>
        <?php foreach ($joueurs as $index => $joueur): ?>
            <div class="joueur">
                <strong><?php echo htmlspecialchars($joueur->getNomComplet()); ?></strong>
                <br>Date de naissance: <?php 
                    echo $joueur->getDateNaissance() ? $joueur->getDateNaissance()->format('d-m-Y') : 'Non définie';  ?>, Âge : <?php echo $joueur->getAge(); ?> ans
                <br>Lieu de naissance: <?php echo htmlspecialchars($joueur->getVilleNaissance()); ?>, <?php echo htmlspecialchars($joueur->getPaysOrigine());?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>