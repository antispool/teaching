<?php

namespace App\Entities;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repositories\CollegeRepository")
 * @ORM\Table(name="college")
 */
class College
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Student", mappedBy="college", cascade={"persist"})
     * @var ArrayCollection|students[]
     */
    protected $students;

    function __construct()
    {
        $this->students = new ArrayCollection;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return students[]|ArrayCollection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * @param students[]|ArrayCollection $students
     */
    public function setStudents($students)
    {
        $this->students = $students;
    }

    /**
     * @param Student $student
     */
    public function addStudent(Student $student)
    {
        if(!$this->students->contains($student)) {
            $student->setCollege($this);
            $this->students->add($student);
        }
    }

    /**
     * @param Student $student
     */
    public function deleteStudent(Student $student)
    {
        $this->students->removeElement($student);
        $student->setCollege(null);
    }
}