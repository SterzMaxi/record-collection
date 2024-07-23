<?php

namespace App\Entity;

use App\Repository\CollectionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CollectionRepository::class)]
#[ORM\Table(name: 'collection')]
#[ORM\HasLifecycleCallbacks]
class Collection
{

    /**
     * @param User $user
     * @param string $collectionname
     * @param string $style
     */

     public function __construct(
        User $user,
        string $collectionname,
        string $style
     ){
        $this->user = $user;
        $this->collectionname = $collectionname;
        $this->style = $style;
        $this->records = new ArrayCollection();
     }


    #[ORM\Id]
    #[ORM\Column(name: 'collection_id', type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'collection_user_id', referencedColumnName: 'user_id')]
    private User $user;

    #[ORM\Column(name: 'collectionname', type: 'string')]
    private string $collectionname;

    #[ORM\Column(name: 'style', type: 'string')]
    private string $style;

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

    public function getCollectionName(): string
    {
        return $this->collectionname;
    }

    public function setCollectionName(string $collectionname): void
    {
        $this->collectionname = $collectionname;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function setStyle(string $style): void
    {
        $this->style = $style;
    }

    public function getRecords(): DoctrineCollection
    {
        return $this->records;
    }

    public function addRecord(Record $record): void
    {
        if (!$this->records->contains($record)) {
            $this->records->add($record);
            $record->setCollection($this);
        }
    }

    #[ORM\PrePersist]
    public function setCreated(): void
    {
        $this->created = new DateTimeImmutable();
        $this->setUpdated();
    }

    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
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

    

}