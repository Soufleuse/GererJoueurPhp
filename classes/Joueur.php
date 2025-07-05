<?php

class Joueur
{
    private int $id;
    private string $prenom;
    private string $nom;
    private DateTime $dateNaissance;
    private string $villeNaissance;
    private string $paysOrigine;
    private array $listeEquipeJoueur = [];
    private array $listeStatsJoueur = [];

    public function __construct(
        string $prenom = '',
        string $nom = '',
        DateTime $dateNaissance = null,
        string $villeNaissance = '',
        string $paysOrigine = '',
        int $id = 0
    ) {
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->dateNaissance = $dateNaissance ?? new DateTime();
        $this->villeNaissance = $villeNaissance;
        $this->paysOrigine = $paysOrigine;
        $this->id = $id;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getDateNaissance(): DateTime
    {
        //var_dump($this->dateNaissance);
        return $this->dateNaissance;
    }

    public function getVilleNaissance(): string
    {
        return $this->villeNaissance;
    }

    public function getPaysOrigine(): string
    {
        return $this->paysOrigine;
    }

    public function getListeEquipeJoueur(): array
    {
        return $this->listeEquipeJoueur;
    }

    public function getListeStatsJoueur(): array
    {
        return $this->listeStatsJoueur;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setDateNaissance(DateTime $dateNaissance): void
    {
        $this->dateNaissance = $dateNaissance;
    }

    public function setVilleNaissance(string $villeNaissance): void
    {
        $this->villeNaissance = $villeNaissance;
    }

    public function setPaysOrigine(string $paysOrigine): void
    {
        $this->paysOrigine = $paysOrigine;
    }

    public function setListeEquipeJoueur(array $listeEquipeJoueur): void
    {
        $this->listeEquipeJoueur = $listeEquipeJoueur;
    }

    public function setListeStatsJoueur(array $listeStatsJoueur): void
    {
        $this->listeStatsJoueur = $listeStatsJoueur;
    }

    // Méthodes utilitaires
    public function getNomComplet(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getAge(): int
    {
        $maintenant = new DateTime();
        $age = $maintenant->diff($this->dateNaissance);
        return $age->y;
    }

    public function ajouterEquipeJoueur($equipeJoueur): void
    {
        $this->listeEquipeJoueur[] = $equipeJoueur;
    }

    public function ajouterStatsJoueur($statsJoueur): void
    {
        $this->listeStatsJoueur[] = $statsJoueur;
    }

    // Méthode pour convertir en array (utile pour JSON ou base de données)
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'prenom' => $this->prenom,
            'nom' => $this->nom,
            'dateNaissance' => $this->dateNaissance->format('Y-m-d'),
            'villeNaissance' => $this->villeNaissance,
            'paysOrigine' => $this->paysOrigine,
            'nomComplet' => $this->getNomComplet(),
            'age' => $this->getAge()
        ];
    }

    // Méthode pour créer un objet depuis un array (utile pour base de données)
    public static function fromArray(array $data): Joueur
    {
        $joueur = new Joueur();
        $joueur->setId($data['id'] ?? 0);
        $joueur->setPrenom($data['prenom'] ?? '');
        $joueur->setNom($data['nom'] ?? '');
        $joueur->setDateNaissance(new DateTime($data['dateNaissance'] ?? 'now'));
        $joueur->setVilleNaissance($data['villeNaissance'] ?? '');
        $joueur->setPaysOrigine($data['paysOrigine'] ?? '');
        
        return $joueur;
    }
}

?>