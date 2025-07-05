<?php
class JoueurService {
    private $baseUrl;
    
    public function __construct() {
        $this->baseUrl = API_BASE_URL;
    }
    
    public function obtenirTousLesJoueurs() {
        $url = "https://ton-api.com/api/joueurs";
        $response = file_get_contents($this->baseUrl);
        $joueurs = json_decode($response, true);
        return $joueurs;
    }
}
?>