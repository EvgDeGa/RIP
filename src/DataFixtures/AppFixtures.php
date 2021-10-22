<?php

namespace App\DataFixtures;

use App\Entity\Todo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $customer = new Todo();
            $customer->setText($faker->text);
            $customer->setChecked($faker->boolean);
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
