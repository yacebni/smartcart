<?php

namespace App\Entity;

use App\Repository\ArticlePrevuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlePrevuRepository::class)]
class ArticlePrevu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?bool $statutAchat = null;

    #[ORM\ManyToOne(inversedBy: 'ArticlesPrevus')]
    private ?ListeCourse $listeCourse = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function isStatutAchat(): ?bool
    {
        return $this->statutAchat;
    }

    public function setStatutAchat(bool $statutAchat): static
    {
        $this->statutAchat = $statutAchat;

        return $this;
    }

    public function getListeCourse(): ?ListeCourse
    {
        return $this->listeCourse;
    }

    public function setListeCourse(?ListeCourse $listeCourse): static
    {
        $this->listeCourse = $listeCourse;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }
}
