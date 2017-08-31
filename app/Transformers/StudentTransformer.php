<?php

namespace App\Transformers;

use App\Entities\College;
use App\Entities\Student;
use League\Fractal\TransformerAbstract;

class StudentTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'college'
    ];

    /**
     * @param Student $student
     * @return array
     */
    public function transform(Student $student)
    {
        return [
            'id' => $student->getId(),
            'name' => $student->getName(),
            'age' => $student->getAge()
        ];
    }

    public function includeCollege(Student $student)
    {
        if($college = $student->getCollege()) {
            return $this->item($college, new CollegeTransformer());
        }
    }
}
