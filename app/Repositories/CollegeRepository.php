<?php

namespace App\Repositories;

use App\Entities\College;
use App\Entities\Student;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\PaginatesFromParams;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CollegeRepository extends EntityRepository
{
    use PaginatesFromParams;

    /**
     * @param int $limit
     * @param int $page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function all($limit = 8, $page = 1)
    {
        return $this->paginateAll($limit, $page);
    }

    /**
     * @param $name
     * @param int $limit
     * @param int $page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function findByName($name, $limit = 8, $page = 1)
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->orderBy('c.name', 'asc')
            ->setParameter('name', "%$name%")
            ->getQuery();

        return $this->paginate($query, $limit, $page);
    }

    /**
     * @param $id
     * @return College|NotFoundHttpException
     */
    public function findOrThrowException($id)
    {
        if( $college = $this->find($id)) {
            return $college;
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @param College $college
     * @param $ids
     * @return College
     */
    public function addStudentsByIds(College $college, $ids)
    {
        foreach ($ids as $id) {
            $student = $this->_em->find(Student::class, $id);
            $student instanceof Student ? $college->addStudent($student) : null;
        }

        $this->_em->persist($college);
        $this->_em->flush();

        return $college;
    }

    /**
     * @param College $college
     * @param $ids
     * @return College
     */
    public function deleteStudentsByIds(College $college, $ids)
    {
        foreach ($ids as $id) {
            $student = $this->_em->find(Student::class, $id);
            $student instanceof Student ? $college->deleteStudent($student) : null;
        }

        $this->_em->persist($college);
        $this->_em->flush();

        return $college;
    }
}