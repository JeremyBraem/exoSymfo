<?php

namespace App\DataFixtures;

use App\Entity\Note;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $note = new Note;
            $note->setName('Student '.$i);
            $note->setNote(rand(0, 20));
            $manager->persist($note);
        }
        $manager->flush();
    }
}
