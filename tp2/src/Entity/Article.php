<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /*#[ORM\Column(type:'string',length:255)]
    private ?string $nom=null;
#[ORM\Column (type:'decimal')]
    private ?string $prix=null;
*/
#[ORM\Column(type: 'string', length: 255)]
#[Assert\Length(
    min: 5,
    max: 50,
    minMessage: "Le nom d'un article doit comporter au moins {{ limit }} caractères",
    maxMessage: "Le nom d'un article doit comporter au plus {{ limit }} caractères"
)]
private ?string $nom = null;

#[ORM\Column(type: 'decimal', precision: 10, scale: 0)]
#[Assert\NotEqualTo(
    value: 0,
    message: "Le prix d'un article ne doit pas être égal à 0"
)]
private ?string $prix = null;


    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNom():?string{
        return $this->nom;
    }
    public function getPrix():?string{
        return $this->prix;
    }

    public function setNom(?string $nom)
    {
        $this->nom = $nom;
    
    }

    public function setPrix(?string $prix)
    {
        $this->prix = $prix;  
    }
}
