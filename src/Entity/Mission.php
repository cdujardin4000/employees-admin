<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
#[ORM\Table('missions')]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"id", type:"integer" )]
    private ?int $missionId = null;


    #[ORM\Column(name:"emp_no", type:"integer" ,nullable: true)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\Column(length: 15)]
    private ?string $status = null;


    #[ORM\ManyToOne(inversedBy: 'missions')]
    #[JoinColumn(name: 'emp_no', referencedColumnName: 'id')]
    private ?Employee $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getmissionId(): ?int
    {
        return $this->missionId;
    }

    public function setMissionId(?int $missionId): self
    {
        $this->missionId = $missionId;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(\DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOwner(): ?Employee
    {
        return $this->owner;
    }

    public function setOwner(?Employee $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
