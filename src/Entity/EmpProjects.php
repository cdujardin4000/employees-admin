<?php

namespace App\Entity;

use App\Repository\EmpProjectsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpProjectsRepository::class)]
class EmpProjects
{


    #[ORM\Id]
    #[ORM\Column]
    private ?int $project_id = null;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $emp_no = null;



    public function getProjectId(): ?int
    {
        return $this->project_id;
    }

    public function setProjectId(int $project_id): self
    {
        $this->project_id = $project_id;

        return $this;
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
}
