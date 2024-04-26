<?php

namespace App\Entity;

use App\Repository\ArtistEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ArtistEntityRepository::class)]
class ArtistEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, AlbumEntity>
     */
    #[ORM\OneToMany(targetEntity: AlbumEntity::class, mappedBy: 'artistEntity')]
    private Collection $albums;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, AlbumEntity>
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(AlbumEntity $album): static
    {
        if (!$this->albums->contains($album)) {
            $this->albums->add($album);
            $album->setArtistEntity($this);
        }

        return $this;
    }

    public function removeAlbum(AlbumEntity $album): static
    {
        if ($this->albums->removeElement($album)) {
            // set the owning side to null (unless already changed)
            if ($album->getArtistEntity() === $this) {
                $album->setArtistEntity(null);
            }
        }

        return $this;
    }
}
