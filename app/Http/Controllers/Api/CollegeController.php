<?php

namespace App\Http\Controllers\Api;

use App\Entities\College;
use App\Http\Controllers\Controller;
use App\Transformers\CollegeTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use App\Transformers\StudentTransformer;

class CollegeController extends Controller
{
    protected $em;

    /**
     * CollegeController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $colleges = $this->em->getRepository(College::class)->findAll();

        return response()->json(
            fractal($colleges, new CollegeTransformer())
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $college = $this->em->getRepository(College::class)->findOrThrowException($id);

        return response()->json(
            fractal($college, new CollegeTransformer())
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function post(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'name' => 'required|min:3'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $college = new College();

        $college->setName($request->get('name'));

        $this->em->persist($college);
        $this->em->flush();

        return response()->json(
            fractal($college, new CollegeTransformer())
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function put(Request $request, $id)
    {
        $college = $this->em->getRepository(College::class)->findOrThrowException($id);

        $validator = \Validator::make($request->all(),
            [
                'name' => 'min:3'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $request->get('name') ? $college->setName($request->get('name')) : null;

        $this->em->persist($college);
        $this->em->flush();

        return response()->json(
            fractal($college, new CollegeTransformer())
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        $college = $this->em->getRepository(College::class)->findOrThrowException($id);

        $this->em->remove($college);
        $this->em->flush();

        return response([], 204);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function putStudents(Request $request, $id)
    {
        $college = $this->em->getRepository(College::class)->findOrThrowException($id);

        $rules = [
            'ids' => 'required|array',
            'ids.*' =>
                'exists:App\Entities\Student, "id"'
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $college = $this->em->getRepository(College::class)->addStudentsByIds($college, $request->get('ids'));

        return response()->json(
            fractal($college, new CollegeTransformer())
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudents($id)
    {
        $college = $this->em->getRepository(College::class)->findOrThrowException($id);

        return response()->json(
            fractal($college->getStudents(), new StudentTransformer())
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteStudents(Request $request, $id)
    {
        $college = $this->em->getRepository(College::class)->findOrThrowException($id);

        $rules = [
            'ids' => 'required|array',
            'ids.*' =>
                'exists:App\Entities\Student, "id"'
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $college = $this->em->getRepository(College::class)->deleteStudentsByIds($college, $request->get('ids'));

        return response()->json(
            fractal($college, new CollegeTransformer())
        );
    }
}