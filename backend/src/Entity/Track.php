<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
#[ORM\Table(name: 'track')]
#[ORM\HasLifecycleCallbacks]
class Track
{

    /**
     * @param Record $record
     * @param string $artist
     * @param string $tracknumber
     * @param string $tracktitle
     * @param string $tracktime
     * @param string $genre
     * @param string $listenlink
     */

     public function __construct(
        Record $record,
        string $artist,
        string $tracknumber,
        string $tracktitle,
        string $tracktime,
        string $genre,
        string $listenlink
     ){
        $this->record = $record;
        $this->artist = $artist;
        $this->tracknumber = $tracknumber;
        $this->tracktitle = $tracktitle;
        $this->tracktime = $tracktime;
        $this->genre = $genre;
        $this->listenlink = $listenlink;
     }


    #[ORM\Id]
    #[ORM\Column(name: 'track_id', type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\ManyToOne(targetEntity: Record::class)]
    #[ORM\JoinColumn(name: 'track_record_id', referencedColumnName: 'record_id')]
    private Record $record;

    #[ORM\Column(name: 'artist', type: 'string')]
    private string $artist;

    #[ORM\Column(name: 'track_number', type: 'string')]
    private string $tracknumber;

    #[ORM\Column(name: 'track_title', type: 'string')]
    private string $tracktitle;

    #[ORM\Column(name: 'track_time', type: 'string')]
    private string $tracktime;

    #[ORM\Column(name: 'genre', type: 'string')]
    private string $genre;

    #[ORM\Column(name: 'listen_link', type: 'string')]
    private string $listenlink;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private DateTimeImmutable $created;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable')]
    private DateTimeImmutable $updated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecord(): Record
    {
        return $this->record;
    }

    public function setRecord(Record $record): void
    {
        $this->record = $record;
    }

    public function getArtist(): string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): void
    {
        $this->artist = $artist;
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

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }

    public function getListenLink(): string
    {
        return $this->listenlink;
    }

    public function setListenLink(string $listenlink): void
    {
        $this->listenlink = $listenlink;
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