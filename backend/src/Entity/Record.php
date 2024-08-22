<?php

namespace App\Entity;

use App\Repository\RecordRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecordRepository::class)]
#[ORM\Table(name: 'record')]
#[ORM\HasLifecycleCallbacks]
class Record
{

    /**
     * @param Collection $collection
     * @param string $title
     * @param string $format
     * @param int $trackcount
     * @param string $label
     * @param string $country
     * @param \DateTime $releasedate
     * @param string $genre
     * @param float $price
     * @param string $bookletfront
     * @param string $bookletback
     * @param string $grade
     */

    public function __construct(
        Collection $collection,
        string $title,
        string $artist,
        string $format,
        int $trackcount,
        string $label,
        string $country,
        \DateTime $releasedate,
        string $genre,
        float $price,
        string $bookletfront,
        string $bookletback,
        string $grade
    ) {
        $this->collection = $collection;
        $this->title = $title;
        $this->artist = $artist;
        $this->format = $format;
        $this->trackcount = $trackcount;
        $this->label = $label;
        $this->country = $country;
        $this->releasedate = $releasedate;
        $this->genre = $genre;
        $this->price = $price;
        $this->bookletfront = $bookletfront;
        $this->bookletback = $bookletback;
        $this->grade = $grade;
        $this->tracks = new ArrayCollection();
    }


    #[ORM\Id]
    #[ORM\Column(name: 'record_id', type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\ManyToOne(targetEntity: Collection::class)]
    #[ORM\JoinColumn(name: 'record_collection_id', referencedColumnName: 'collection_id')]
    private Collection $collection;

    #[ORM\Column(name: 'title', type: 'string')]
    private string $title;

    #[ORM\Column(name: 'artist', type: 'string')]
    private string $artist;

    #[ORM\Column(name: 'format', type: 'string')]
    private string $format;

    #[ORM\Column(name: 'track_count', type: 'integer')]
    private int $trackcount;

    #[ORM\Column(name: 'label', type: 'string')]
    private string $label;

    #[ORM\Column(name: 'country', type: 'string')]
    private string $country;

    #[ORM\Column(name: 'release_date', type: 'datetime')]
    private \DateTime $releasedate;

    #[ORM\Column(name: 'genre', type: 'string')]
    private string $genre;

    #[ORM\Column(name: 'price', type: 'float')]
    private float $price;

    #[ORM\Column(name: 'booklet_front', type: 'string')]
    private string $bookletfront;

    #[ORM\Column(name: 'booklet_back', type: 'string')]
    private string $bookletback;

    #[ORM\Column(name: 'grade', type: 'string')]
    private string $grade;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private DateTimeImmutable $created;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable')]
    private DateTimeImmutable $updated;

    #[ORM\OneToMany(mappedBy: 'record', targetEntity: Track::class, cascade: ['remove'], orphanRemoval: true)]
    private DoctrineCollection $tracks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCollection(): Collection
    {
        return $this->collection;
    }

    public function setCollection(User $collection): void
    {
        $this->collection = $collection;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getArtist(): string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): void
    {
        $this->artist = $artist;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function getTrackcount(): int
    {
        return $this->trackcount;
    }

    public function setTrackcount(int $trackcount): void
    {
        $this->trackcount = $trackcount;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releasedate;
    }

    public function setReleaseDate(\DateTimeInterface $releasedate): void
    {
        $this->releasedate = $releasedate;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getBookletFront(): ?string
    {
        return $this->bookletfront;
    }

    public function setBookletFront(?string $bookletfront): self
    {
        $this->bookletfront = $bookletfront;
        return $this;
    }

    public function getBookletBack(): ?string
    {
        return $this->bookletback;
    }

    public function setBookletBack(?string $bookletback): self
    {
        $this->bookletback = $bookletback;
        return $this;
    }

    public function getgrade(): string
    {
        return $this->grade;
    }

    public function setgrade(string $grade): void
    {
        $this->grade = $grade;
    }

    #[ORM\PrePersist]
    public function setCreated(): void
    {
        $this->created = new DateTimeImmutable();
        $this->setUpdated();
    }

    #[ORM\PreUpdate]
    public function setUpdated(): void
    {
        $this->updated = new DateTimeImmutable();
    }

    public function getUpdated(): DateTimeImmutable
    {
        return $this->updated;
    }

    public function getTracks(): DoctrineCollection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): void
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks->add($track);
            $track->setRecord($this);
        }
    }

    public function removeTrack(Track $track): void
    {
        if ($this->tracks->contains($track)) {
            $this->tracks->removeElement($track);
            $track->setRecord(null);
        }
    }


}