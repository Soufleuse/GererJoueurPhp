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
    <title>Gestion des Joueurs - Ligue</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.8em;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        
        .message {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: #1a5f3d;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(132, 250, 176, 0.3);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.95em;
        }
        
        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 35px;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .joueur-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .joueur {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-left: 5px solid #667eea;
        }
        
        .joueur:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .joueur strong {
            color: #667eea;
            font-size: 1.3em;
            display: block;
            margin-bottom: 12px;
        }
        
        .joueur-info {
            color: #555;
            line-height: 1.8;
            font-size: 0.95em;
        }
        
        .joueur-info span {
            display: block;
            margin-bottom: 5px;
        }
        
        .badge {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            margin-top: 8px;
        }
        
        .empty-state {
            text-align: center;
            color: #999;
            padding: 40px;
            font-size: 1.1em;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 1.8em;
            }
            
            .card {
                padding: 20px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .joueur-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
            <h2>➕ Ajouter un joueur</h2>
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="prenom">Prénom du joueur</label>
                        <input type="text" id="prenom" name="prenom" required placeholder="Ex: Wayne">
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom du joueur</label>
                        <input type="text" id="nom" name="nom" required placeholder="Ex: Gretzky">
                    </div>
                    <div class="form-group">
                        <label for="dateNaissance">Date de naissance</label>
                        <input type="date" id="dateNaissance" name="dateNaissance" required>
                    </div>
                    <div class="form-group">
                        <label for="villeNaissance">Ville de naissance</label>
                        <input type="text" id="villeNaissance" name="villeNaissance" required placeholder="Ex: Montréal">
                    </div>
                    <div class="form-group">
                        <label for="paysOrigine">Pays de naissance</label>
                        <input type="text" id="paysOrigine" name="paysOrigine" required placeholder="Ex: Canada">
                    </div>
                </div>
                
                <button type="submit" name="action" value="ajouter">Ajouter le joueur</button>
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
                        <div class="joueur">
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
    </script>
</body>
</html>