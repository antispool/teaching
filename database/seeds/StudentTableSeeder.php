<?php

use Illuminate\Database\Seeder;
use Doctrine\ORM\EntityManagerInterface;
use App\Entities\College;

class StudentTableSeeder extends Seeder
{

    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $college = $this->em->getRepository(College::class)->findOneBy([]);
        foreach(range(1, 20) as $index)
        {
            DB::table('student')->insert([
                'name' => str_random(10),
                'age' => 18,
                'college_id' => $college->getId()
            ]);
        }
    }
}
