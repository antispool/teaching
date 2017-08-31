<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\PaginatesFromParams;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StudentRepository extends EntityRepository
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
        $query = $this->createQueryBuilder('s')
            ->where('s.name LIKE :name')
            ->orderBy('s.name', 'asc')
            ->setParameter('name', "%$name%")
            ->getQuery();

        return $this->paginate($query, $limit, $page);
    }

    public function findOrThrowException($id)
    {
        if( $student = $this->find($id)) {
            return $student;
        } else {
            throw new NotFoundHttpException();
        }
    }
}