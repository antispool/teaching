<?php

namespace App\Transformers;

use App\Entities\College;
use League\Fractal\TransformerAbstract;

class CollegeTransformer extends TransformerAbstract
{
    /**
     * @param College $college
     * @return array
     */
    public function transform(College $college)
    {
        return [
            'id' => $college->getId(),
            'name' => $college->getName()
        ];
    }
}
