<?php

namespace App\Http\Controllers\Api;

use App\Entities\College;
use App\Entities\Student;
use App\Http\Controllers\Controller;
use App\Transformers\StudentTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StudentController extends Controller
{
    protected $em;

    /**
     * StudentController constructor.
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
        $students = $this->em->getRepository(Student::class)->all();

        return response()->json(
            fractal($students, new StudentTransformer())
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get($id)
    {
        $student = $this->em->getRepository(Student::class)->findOrThrowException($id);

        return response()->json(
            fractal($student, new StudentTransformer())
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function post(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'name' => 'required|min:3',
                'age' => 'required|integer|min:0',
                'college' => 'exists:App\Entities\College, "id"'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $student = new Student();
        $student->setName($request->get('name'));
        $student->setAge($request->get('age'));

        if ($collegeId = $request->get('college')) {
            $college = $this->em->find(College::class, $collegeId);
            $college ? $student->setCollege($college) : null;
        }

        $this->em->persist($student);
        $this->em->flush();

        return response()->json(
            fractal($student, new StudentTransformer())
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse | NotFoundHttpException
     */
    public function put(Request $request, $id)
    {
        $student = $this->em->getRepository(Student::class)->findOrThrowException($id);

        $validator = \Validator::make($request->all(),
            [
                'name' => 'min:3',
                'age' => 'integer|min:0',
                'college' => 'exists:App\Entities\College, "id"'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $college = $request->get('college') ? $this->em->getRepository(College::class)->find($request->get('college')) : null;

        $request->get('name') ? $student->setName($request->get('name')) : null;
        $request->get('age') ? $student->setName($request->get('age')) : null;
        $college ? $student->setCollege($college) : null;

        $this->em->persist($student);
        $this->em->flush();


        return response()->json(fractal($student, new StudentTransformer()));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        $student = $this->em->getRepository(Student::class)->findOrThrowException($id);

        $this->em->remove($student);
        $this->em->flush();

        return response([], 204);
    }
}