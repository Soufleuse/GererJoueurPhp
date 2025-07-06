<?php
require_once 'classes/Joueur.php';

class JoueurService {
    private $baseUrl;
    
    public function __construct() {
        $this->baseUrl = API_BASE_URL;
    }
    
    /**
     * Obtient tous les joueurs de la BD
     */
    public function obtenirTousLesJoueurs() {
        $ch = curl_init();
        
        // Configuration de la requête GET
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Vérification d'erreurs
        if (curl_error($ch)) {
            curl_close($ch);
            throw new Exception('Erreur cURL: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        // Vérification du code HTTP
        if ($httpCode !== 200) {
            throw new Exception('Erreur HTTP: ' . $httpCode);
        }
        
        $joueurs = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erreur de décodage JSON: ' . json_last_error_msg());
        }
        
        $retour = array();
        foreach($joueurs as $y => $item) {
            $unJoueur = new Joueur($item["prenom"],
                                $item["nom"],
                                new DateTime($item["dateNaissance"]),
                                $item["villeNaissance"],
                                $item["paysOrigine"],
                                $item["id"]);
            $retour[] = $unJoueur;
        }

        return $retour;
    }

    /**
     * Ajoute un joueur à la BD
     * 
     * @param unjoueur le joueur à ajouter
     */
    public function ajouterJoueur($unJoueur) {
        $ch = curl_init();
        
        // Conversion de l'objet en JSON (au lieu de http_build_query)
        $jsonData = json_encode($unJoueur);
        
        // Configuration de la requête POST
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Vérification d'erreurs
        if (curl_error($ch)) {
            curl_close($ch);
            throw new Exception('Erreur cURL: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        // Vérification du code HTTP
        if ($httpCode < 200 || $httpCode >= 300) {
            throw new Exception('Erreur HTTP: ' . $httpCode . ' - ' . $response);
        }
        
        return $response;
    }
    
    // Méthode utilitaire pour les requêtes cURL
    private function executerRequeteCurl($url, $method = 'GET', $data = null, $headers = []) {
        $ch = curl_init();
        
        // Configuration de base
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        // Configuration selon la méthode
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_error($ch)) {
            curl_close($ch);
            throw new Exception('Erreur cURL: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        return ['response' => $response, 'httpCode' => $httpCode];
    }
}
?>