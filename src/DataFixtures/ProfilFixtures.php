<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $profiles = ["ADMIN", "FORMATEUR", "APPRENANT", "CM"];

        foreach ( $profiles as $key => $libelle ) {

            $profil = new Profil();
            $profil ->setLibelle($libelle);
            $profil ->setArchived(false);
            $manager ->persist($profil);
            $manager ->flush();
            $this->addReference("p$key", $profil);

        }
        $manager->flush();
    }
}
