<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

#[ORM\Table(name: 'cars')]
#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $car_id = null;

    #[ORM\Column(length: 10)]
    private ?string $registration_number = null;

    #[ORM\Column(length: 50)]
    private ?string $model = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img_url = null;

/*    #[ORM\ManyToMany(targetEntity: Employee::class, mappedBy: 'cars',)]
    #[ORM\JoinTable(name: 'cars_emp')]
    private Collection $employees;*/



    public function __toString() :string {
        return $this->model;
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

    public function getRegistrationNumber(): ?string
    {
        return $this->registration_number;
    }

    public function setRegistrationNumber(string $registration_number): self
    {
        $this->registration_number = $registration_number;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->img_url;
    }

    public function setImgUrl(?string $img_url): self
    {
        $this->img_url = $img_url;

        return $this;
    }

/*    /**
     * @return Entity

    public function getEmployees(): Collection
    {
        return $this->employees;
    }*/

    /**
     * @return Car

    public function setEmployees($employees): Car
    {
        $this->employees = $employees;
        return $this;
    }*/




}
