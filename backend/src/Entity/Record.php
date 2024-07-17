<?php

namespace App\Entity;

use App\Repository\RecordRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecordRepository::class)]
#[ORM\Table(name: 'record')]
#[ORM\HasLifecycleCallbacks]
class Record
{

    /**
     * @param User $user
     * @param string $title
     * @param string $artist
     * @param string $format
     * @param string $tracknumber
     * @param string $tracktitle
     * @param string $tracktime
     * @param string $label
     * @param string $country
     * @param \DateTime $releasedate
     * @param string $genre
     * @param string $collectionname
     * @param float $price
     * @param string $bookletfront
     * @param string $bookletback
     * @param string $listenlink
     * @param string $grade
     */

     public function __construct(
        User $user,
        string $title,
        string $artist,
        string $format,
        string $tracknumber,
        string $tracktitle,
        string $tracktime,
        string $label,
        string $country,
        \DateTime $releasedate,
        string $genre,
        string $collectionname,
        float $price,
        string $bookletfront,
        string $bookletback,
        string $listenlink,
        string $grade
     ){
        $this->user = $user;
        $this->title = $title;
        $this->artist = $artist;
        $this->format = $format;
        $this->tracknumber = $tracknumber;
        $this->tracktitle = $tracktitle;
        $this->tracktime = $tracktime;
        $this->label = $label;
        $this->country = $country;
        $this->releasedate = $releasedate;
        $this->genre = $genre;
        $this->collectionname = $collectionname;
        $this->price = $price;
        $this->bookletfront = $bookletfront;
        $this->bookletback = $bookletback;
        $this->listenlink = $listenlink;
        $this->grade = $grade;
     }


    #[ORM\Id]
    #[ORM\Column(name: 'record_id', type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'record_user_id', referencedColumnName: 'user_id')]
    private User $user;

    #[ORM\Column(name: 'title', type: 'string')]
    private string $title;

    #[ORM\Column(name: 'artist', type: 'string')]
    private string $artist;

    #[ORM\Column(name: 'format', type: 'string')]
    private string $format;

    #[ORM\Column(name: 'track_number', type: 'string')]
    private string $tracknumber;

    #[ORM\Column(name: 'track_title', type: 'string')]
    private string $tracktitle;

    #[ORM\Column(name: 'track_time', type: 'string')]
    private string $tracktime;

    #[ORM\Column(name: 'label', type: 'string')]
    private string $label;

    #[ORM\Column(name: 'country', type: 'string')]
    private string $country;

    #[ORM\Column(name: 'release_date', type: 'datetime')]
    private \DateTime $releasedate;

    #[ORM\Column(name: 'genre', type: 'string')]
    private string $genre;

    #[ORM\Column(name: 'collection_name', type: 'string')]
    private string $collectionname;

    #[ORM\Column(name: 'price', type: 'float')]
    private float $price;

    #[ORM\Column(name: 'booklet_front', type: 'string')]
    private string $bookletfront;

    #[ORM\Column(name: 'booklet_back', type: 'string')]
    private string $bookletback;

    #[ORM\Column(name: 'listen_link', type: 'string')]
    private string $listenlink;

    #[ORM\Column(name: 'grade', type: 'string')]
    private string $grade;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private DateTimeImmutable $created;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable')]
    private DateTimeImmutable $updated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
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

    public function getTrackNumber(): string
    {
        return $this->tracknumber;
    }

    public function setTrackNumber(string $tracknumber): void
    {
        $this->tracknumber = $tracknumber;
    }

    public function getTrackTitle(): string
    {
        return $this->tracktitle;
    }

    public function setTrackTitle(string $tracktitle): void
    {
        $this->tracktitle = $tracktitle;
    }

    public function getTrackTime(): string
    {
        return $this->tracktime;
    }

    public function setTrackTime(string $tracktime): void
    {
        $this->tracktime = $tracktime;
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

    public function setReleaseDate(DateTimeInterface $releasedate): void
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

    public function getCollectionName(): string
    {
        return $this->collectionname;
    }

    public function setCollectionName(string $collectionname): void
    {
        $this->collectionname = $collectionname;
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

    public function getLitenLink(): string
    {
        return $this->listenlink;
    }

    public function setListenLink(string $listenlink): void
    {
        $this->listenlink = $listenlink;
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
    

}