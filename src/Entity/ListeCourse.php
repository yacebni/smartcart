<?php

namespace App\Entity;

use App\Repository\ListeCourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeCourseRepository::class)]
class ListeCourse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomListe = null;

    #[ORM\OneToMany(targetEntity: ArticlePrevu::class, mappedBy: 'listeCourse')]
    private Collection $ArticlesPrevus;

    #[ORM\ManyToOne(inversedBy: 'listes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->ArticlesPrevus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomListe(): ?string
    {
        return $this->nomListe;
    }

    public function setNomListe(string $nomListe): static
    {
        $this->nomListe = $nomListe;

        return $this;
    }

    /**
     * @return Collection<int, ArticlePrevu>
     */
    public function getArticlesPrevus(): Collection
    {
        return $this->ArticlesPrevus;
    }

    public function addArticlesPrevu(ArticlePrevu $articlesPrevu): static
    {
        if (!$this->ArticlesPrevus->contains($articlesPrevu)) {
            $this->ArticlesPrevus->add($articlesPrevu);
            $articlesPrevu->setListeCourse($this);
        }

        return $this;
    }

    public function removeArticlesPrevu(ArticlePrevu $articlesPrevu): static
    {
        if ($this->ArticlesPrevus->removeElement($articlesPrevu)) {
            // set the owning side to null (unless already changed)
            if ($articlesPrevu->getListeCourse() === $this) {
                $articlesPrevu->setListeCourse(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
