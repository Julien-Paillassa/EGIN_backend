<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $contributor = new User();
        $contributor->setEmail('kelly.slater@gmail.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor->setPassWord($this->passwordEncoder->encodePassword($contributor, 'kelly'));
        $manager->persist($contributor);
        $this->addReference('user_0', $contributor);

        $contributor = new User();
        $contributor->setEmail('mick.fanning@gmail.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor->setPassWord($this->passwordEncoder->encodePassword($contributor, 'mick'));
        $manager->persist($contributor);
        $this->addReference('user_1', $contributor);

        $admin = new User();
        $admin->setEmail('laird.hamilton@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassWord($this->passwordEncoder->encodePassword($admin, 'hamilton'));
        $manager->persist($admin);
        $this->addReference('admin_0', $admin);


        $manager->flush();
    }
}
