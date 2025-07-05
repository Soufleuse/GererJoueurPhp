<!DOCTYPE html>
<?php $page = "index"; ?>
<?php
// index.php - Gestion des joueurs de ligue
#require_once 'config/database.php';
require_once 'classes/Joueur.php';

// Logique PHP
$joueurs = [];
$message = '';

if ($_POST['action'] ?? '' === 'ajouter') {
    $nom = $_POST['prenom'] ?? '';
    $position = $_POST['nom'] ?? '';
    $datenaissance = $_POST['dateNaissance'] ?? '';
    
    if ($prenom && $nom) {
        $joueur = new Joueur($prenom, $nom, $datenaissance);
        $joueurs[] = $joueur;
        $message = "Joueur $nom ajouté avec succès !";
    }
}

// Exemple de données statiques pour la démo
#$dateNaissEricK = new DateTime('1990-05-31');
#$dateString = $dateNaissEricK->format('d-m-Y');
#echo "<p>Date naissance EK : $dateString </p>";
#$dateNaissEricK->setDate('1960', '12', '1');
#$dateString = $dateNaissEricK->format('d-m-Y');
#echo "<p>Date naissance EK : $dateString </p>";
$joueurs = [
    new Joueur("Sidney", "Crosby", new DateTime('1987-08-07'), "Cole Harbour"),
    new Joueur("Connor", "McDavid", new DateTime("1997-01-12"), "Richmond Hill"),
    new Joueur("Erik", "Karlsson", new DateTime('1990-05-31'), "Landsbro")
];
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
            <label for="dateNaissance">Nom du joueur:</label>
            <input type="date" id="dateNaissance" name="dateNaissance" required>
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
    
    <h2>Liste des joueurs (<?php echo count($joueurs); ?>)</h2>
    <?php if (empty($joueurs)): ?>
        <p>Aucun joueur enregistré.</p>
    <?php else: ?>
        <?php foreach ($joueurs as $index => $joueur): ?>
            <div class="joueur">
                <strong><?php echo htmlspecialchars($joueur->getNomComplet()); ?></strong>
                <br>Date de naissance: <?php 
                    echo $joueur->getDateNaissance() ? $joueur->getDateNaissance()->format('d-m-Y') : 'Non définie'; 
                ?>
                <br>Ville de naissance: <?php echo htmlspecialchars($joueur->getVilleNaissance()); ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>