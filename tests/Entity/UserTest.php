<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserTest extends KernelTestCase
{

    public function getEntity(): User
    {
        $user = new User();
        $user->setEmail('silverSurfer@gmail.com');
        $user->setRoles(['ROLE_CONTRIBUTOR']);
        $user->setPassWord('silver');

        return $user;
    }

    public function asserHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $container = static::getContainer();

        $errors = $container->get('validator')->validate($user);

        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(',', $messages));
    }

    public function testValidUser()
    {
        $user = $this->getEntity();

        $this->asserHasErrors($user, 0);
    }

    public function testInvalidUser()
    {
        $user = $this->getEntity()->setEmail('jeNeSuisPasUnEmail');

        $this->asserHasErrors($user, 1);
    }
}
