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
$joueurAModifier = null;

$joueurService = new JoueurService();

// Gestion de la modification
if ($_POST['action'] ?? '' === 'modifier') {
    $id = $_POST['id'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $datenaissance = $_POST['dateNaissance'] ?? '';
    $villeNaissance = $_POST['villeNaissance'] ?? '';
    $paysOrigine = $_POST['paysOrigine'] ?? '';
    
    if ($id && $prenom && $nom && $datenaissance && $villeNaissance && $paysOrigine) {
        try {
            $joueur = new Joueur($prenom, $nom, new DateTime($datenaissance), $villeNaissance, $paysOrigine);
            $joueurData = $joueur->toArray();
            $joueurData['id'] = $id;
            
            if($joueurService->modifierJoueur($joueurData) !== false) {
                $message = "Joueur $nom modifié avec succès !";
            }
            else {
                $message = "Erreur à la modification du joueur";
            }
        } catch (Exception $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    }
}

// Chargement d'un joueur pour modification
if ($_GET['modifier'] ?? '' !== '') {
    $idModif = $_GET['modifier'];
    try {
        $joueurAModifier = $joueurService->obtenirJoueur($idModif);
    } catch (Exception $e) {
        $message = "Erreur lors du chargement du joueur : " . $e->getMessage();
    }
}

if ($_POST['action'] ?? '' === 'ajouter') {
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $datenaissance = $_POST['dateNaissance'] ?? '';
    $villeNaissance = $_POST['villeNaissance'] ?? '';
    $paysOrigine = $_POST['paysOrigine'] ?? '';
    
    if ($prenom && $nom && $datenaissance && $villeNaissance && $paysOrigine) {
        try {
            $joueur = new Joueur($prenom, $nom, new DateTime($datenaissance), $villeNaissance, $paysOrigine);
            if($joueurService->ajouterJoueur($joueur->toArray()) !== false) {
                $joueurs[] = $joueur;
                $message = "Joueur $nom ajouté avec succès !";
            }
            else {
                $message = "Erreur à l'ajout du joueur";
            }
        } catch (Exception $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    }
    else {
        $message = "Veuillez entrer les informations requises.";
    }
}

$joueurs = $joueurService->obtenirTousLesJoueurs();
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/gererjoueur/images/balle-baseball.png">
    <link rel="stylesheet" href="css/styles.css">
    <title>Gestion des Joueurs - Ligue</title>
</head>
<body>
    <div class="container">
        <h1>⚾ Gestion des Joueurs de Ligue</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-bottom: 25px;">
            <button id="toggleBtn" onclick="toggleSections()">➕ Ajouter un nouveau joueur</button>
        </div>
        
        <div class="card" id="formSection" style="display: none;">
            <h2><?php echo $joueurAModifier ? '✏️ Modifier le joueur' : '➕ Ajouter un joueur'; ?></h2>
            <form method="POST">
                <?php if ($joueurAModifier): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($joueurAModifier->getId() ?? ''); ?>">
                <?php endif; ?>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="prenom">Prénom du joueur</label>
                        <input type="text" id="prenom" name="prenom" required placeholder="Ex: Wayne" 
                               value="<?php echo $joueurAModifier ? htmlspecialchars($joueurAModifier->getPrenom()) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom du joueur</label>
                        <input type="text" id="nom" name="nom" required placeholder="Ex: Gretzky"
                               value="<?php echo $joueurAModifier ? htmlspecialchars($joueurAModifier->getNom()) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="dateNaissance">Date de naissance</label>
                        <input type="date" id="dateNaissance" name="dateNaissance" required
                               value="<?php echo $joueurAModifier && $joueurAModifier->getDateNaissance() ? $joueurAModifier->getDateNaissance()->format('Y-m-d') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="villeNaissance">Ville de naissance</label>
                        <input type="text" id="villeNaissance" name="villeNaissance" required placeholder="Ex: Montréal"
                               value="<?php echo $joueurAModifier ? htmlspecialchars($joueurAModifier->getVilleNaissance()) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="paysOrigine">Pays de naissance</label>
                        <input type="text" id="paysOrigine" name="paysOrigine" required placeholder="Ex: Canada"
                               value="<?php echo $joueurAModifier ? htmlspecialchars($joueurAModifier->getPaysOrigine()) : ''; ?>">
                    </div>
                </div>
                
                <button type="submit" name="action" value="<?php echo $joueurAModifier ? 'modifier' : 'ajouter'; ?>">
                    <?php echo $joueurAModifier ? '💾 Enregistrer les modifications' : 'Ajouter le joueur'; ?>
                </button>
                
                <?php if ($joueurAModifier): ?>
                    <button type="button" onclick="window.location.href='index.php'" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%); margin-left: 10px;">
                        Annuler
                    </button>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="card" id="listSection">
            <h2>📋 Liste des joueurs (<?php echo count($joueurs); ?> dans la liste)</h2>
            <?php if (empty($joueurs)): ?>
                <div class="empty-state">
                    <p>Aucun joueur enregistré pour le moment.</p>
                    <p>Commencez par ajouter votre premier joueur ! 🏒</p>
                </div>
            <?php else: ?>
                <div class="joueur-grid">
                    <?php foreach ($joueurs as $index => $joueur): ?>
                        <div class="joueur" onclick="window.location.href='?modifier=<?php echo htmlspecialchars($joueur->getId() ?? ''); ?>'">
                            <strong><?php echo htmlspecialchars($joueur->getNomComplet()); ?></strong>
                            <div class="joueur-info">
                                <span>📅 Né le <?php echo $joueur->getDateNaissance() ? $joueur->getDateNaissance()->format('d/m/Y') : 'Non définie'; ?></span>
                                <span>📍 <?php echo htmlspecialchars($joueur->getVilleNaissance()); ?>, <?php echo htmlspecialchars($joueur->getPaysOrigine());?></span>
                                <span class="badge"><?php echo $joueur->getAge(); ?> ans</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function toggleSections() {
            const formSection = document.getElementById('formSection');
            const listSection = document.getElementById('listSection');
            const toggleBtn = document.getElementById('toggleBtn');
            
            if (formSection.style.display === 'none') {
                // Afficher le formulaire, masquer la liste
                formSection.style.display = 'block';
                listSection.style.display = 'none';
                toggleBtn.textContent = '📋 Retour à la liste';
            } else {
                // Masquer le formulaire, afficher la liste
                formSection.style.display = 'none';
                listSection.style.display = 'block';
                toggleBtn.textContent = '➕ Ajouter un nouveau joueur';
            }
        }
        
        // Si on est en mode modification, afficher le formulaire au chargement
        <?php if ($joueurAModifier): ?>
        window.onload = function() {
            document.getElementById('formSection').style.display = 'block';
            document.getElementById('listSection').style.display = 'none';
            document.getElementById('toggleBtn').textContent = '📋 Retour à la liste';
        };
        <?php endif; ?>
    </script>
</body>
</html>