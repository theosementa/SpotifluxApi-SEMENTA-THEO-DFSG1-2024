<?php

namespace App\Entity;

use App\Repository\AlbumEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AlbumEntityRepository::class)]
class AlbumEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $year = null;

    /**
     * @var Collection<int, TrackEntity>
     */
    #[ORM\OneToMany(targetEntity: TrackEntity::class, mappedBy: 'albumEntity')]
    private Collection $tracks;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ArtistEntity $artist = null;

    #[ORM\ManyToOne(inversedBy: 'albums')]
    private ?ArtistEntity $artistEntity = null;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection<int, TrackEntity>
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(TrackEntity $track): static
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks->add($track);
            $track->setAlbumEntity($this);
        }

        return $this;
    }

    public function removeTrack(TrackEntity $track): static
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getAlbumEntity() === $this) {
                $track->setAlbumEntity(null);
            }
        }

        return $this;
    }

    public function getArtist(): ?ArtistEntity
    {
        return $this->artist;
    }

    public function setArtist(?ArtistEntity $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getArtistEntity(): ?ArtistEntity
    {
        return $this->artistEntity;
    }

    public function setArtistEntity(?ArtistEntity $artistEntity): static
    {
        $this->artistEntity = $artistEntity;

        return $this;
    }
}
