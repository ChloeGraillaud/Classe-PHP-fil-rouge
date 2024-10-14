<?php

class Photo
{
    private $idPhoto;
    private $photoDate;
    private $idService;
    private $idCategorie;

    
    public function __construct($photoDate, $idService, $idCategorie, $idPhoto = null)
    {
        $this->idPhoto = $idPhoto;
        $this->photoDate = $photoDate;
        $this->idService = $idService;
        $this->idCategorie = $idCategorie;
    }

    // Getters pour récupérer les informations
    public function getIdPhoto()
    {
        return $this->idPhoto;
    }

    public function getPhotoDate()
    {
        return $this->photoDate;
    }

    public function getIdService()
    {
        return $this->idService;
    }

    public function getIdCategorie()
    {
        return $this->idCategorie;
    }

    // Setters ( au cas où même si c'est pas utile avec le constructeur )
    public function setPhotoDate($photoDate)
    {
        $this->photoDate = $photoDate;
    }

    public function setIdService($idService)
    {
        $this->idService = $idService;
    }

    public function setIdCategorie($idCategorie)
    {
        $this->idCategorie = $idCategorie;
    }

    // Méthode pour sauvegarder ou mettre à jour la photo dans la base de données
    public function save($pdo)
    {
        if ($this->idPhoto) {
            // Si idPhoto existe, on fait une mise à jour
            $stmt = $pdo->prepare('UPDATE Photo SET photoDate = ?, idService = ?, idCategorie = ? WHERE idPhoto = ?');
            $stmt->execute([$this->photoDate, $this->idService, $this->idCategorie, $this->idPhoto]);
        } else {
            // Sinon, on insère une nouvelle entrée
            $stmt = $pdo->prepare('INSERT INTO Photo (photoDate, idService, idCategorie) VALUES (?, ?, ?)');
            $stmt->execute([$this->photoDate, $this->idService, $this->idCategorie]);
            $this->idPhoto = $pdo->lastInsertId();
        }
    }

    // Récupérer une photo par son ID
    public static function getById($pdo, $idPhoto)
    {
        $stmt = $pdo->prepare('SELECT * FROM Photo WHERE idPhoto = ?');
        $stmt->execute([$idPhoto]);
        $row = $stmt->fetch();

        if ($row) {
            return new Photo($row['photoDate'], $row['idService'], $row['idCategorie'], $row['idPhoto']);
        }

        return null;
    }

    // Supprimer une photo
    public function delete($pdo)
    {
        if ($this->idPhoto) {
            $stmt = $pdo->prepare('DELETE FROM Photo WHERE idPhoto = ?');
            $stmt->execute([$this->idPhoto]);
        }
    }
}
