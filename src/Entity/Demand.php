<?php

namespace App\Entity;

use App\Repository\DemandRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\DateType;

#[ORM\Table(name: 'demands')]
#[ORM\Entity(repositoryClass: DemandRepository::class)]
class Demand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    private ?int $emp_no = null;

    /**
     * @return int|null
     */
    public function getEmpNo(): ?int
    {
        return $this->emp_no;
    }

    /**
     * @param int|null $emp_no
     */
    public function setEmpNo(?int $emp_no): void
    {
        $this->emp_no = $emp_no;
    }


    #[ORM\Column(length: 15)]
    private ?string $type = null;

    #[ORM\Column(length: 15)]
    private ?string $about = null;

    #[ORM\Column(nullable: true)]
    private ?bool $status = null;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'demands')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no', nullable: false)]
    private ?Employee $employee = null;

    #[ORM\Column(nullable: false)]
    private ?bool $reviewed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(string $about): self
    {
        $this->about = $about;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;
        $this->reviewed = 1;
        return $this;
    }

    public function isReviewed(): ?bool
    {
        return $this->reviewed;
    }

    public function setReviewed(?bool $reviewed): self
    {
        $this->reviewed = $reviewed;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }
}
