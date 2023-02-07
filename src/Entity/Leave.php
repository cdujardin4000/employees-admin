<?php

namespace App\Entity;

use App\Repository\LeaveRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeaveRepository::class)]
#[ORM\Table(name: '`leaves`')]
class Leave
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $leave_id = null;

    #[ORM\Column]
    private ?int $emp_no = null;

    #[ORM\Column(length: 15)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $from_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $to_date = null;

    #[ORM\ManyToOne(inversedBy: 'leaves')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no', nullable: false)]
    private ?Employee $employee = null;

    public function getLeaveId(): ?int
    {
        return $this->leave_id;
    }

    public function get_leave_id(): ?int
    {
        return $this->leave_id;
    }

    public function getEmpNo(): ?int
    {
        return $this->emp_no;
    }

    public function setEmpNo(int $emp_no): self
    {
        $this->emp_no = $emp_no;

        return $this;
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

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->from_date;
    }

    public function setFromDate(\DateTimeInterface $from_date): self
    {
        $this->from_date = $from_date;

        return $this;
    }

    public function getToDate(): ?\DateTimeInterface
    {
        return $this->to_date;
    }

    public function setToDate(\DateTimeInterface $to_date): self
    {
        $this->to_date = $to_date;

        return $this;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }
}
