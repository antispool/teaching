<?php

namespace App\Http\Controllers\Api;

use App\Entities\Student;
use App\Http\Controllers\Controller;
use App\Transformers\StudentTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    protected $em;

    /**
     * DefaultController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $page = $request->get("page", 1);
        $limit = $request->get("limit", 10);

        if( $name = $request->header("name") ) {
            $students = $this->em->getRepository(Student::class)->findByName($name, $limit, $page);
        } else {
            $students = $this->em->getRepository(Student::class)->all($limit, $page);
        }

        return response()->json(
            fractal($students, new StudentTransformer())
        );
    }
}