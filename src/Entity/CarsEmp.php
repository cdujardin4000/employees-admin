<?php

namespace App\Entity;

use App\Repository\CarsEmpRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`cars_emp`')]
#[ORM\Entity(repositoryClass: CarsEmpRepository::class)]
#[ORM\UniqueConstraint(name: "emp_no_car_id_unique",
    columns: ["emp_no", "car_id"])]
class CarsEmp
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $emp_no = null;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $car_id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $from_date = null;




    public function getEmpNo(): ?int
    {
        return $this->emp_no;
    }

    public function setEmpNo(?int $emp_no): self
    {
        $this->emp_no = $emp_no;

        return $this;
    }

    public function getCarId(): ?int
    {
        return $this->car_id;
    }

    public function setCarId(int $car_id): self
    {
        $this->car_id = $car_id;

        return $this;
    }

    public function getFromDate(): ?DateTimeInterface
    {
        return $this->from_date;
    }

    public function setFromDate(DateTimeInterface $from_date): self
    {
        $this->from_date = $from_date;

        return $this;
    }



}
