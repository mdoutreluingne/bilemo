<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail('admin@bilemo.fr');
        $admin->setPassword($this->encoder->hashPassword($admin, 'adminadmin'));
        $admin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $admin->setCustomer($this->getReference(CustomerFixtures::CUSTOMER_BILEMO));
        $manager->persist($admin);

        $userOne = new User();
        $userOne->setEmail('stephane@flexcon.fr');
        $userOne->setPassword($this->encoder->hashPassword($userOne, 'testtest'));
        $userOne->setRoles(['ROLE_USER']);
        $userOne->setCustomer($this->getReference(CustomerFixtures::CUSTOMER_SFR));
        $manager->persist($userOne);

        $userTwo = new User();
        $userTwo->setEmail('jeff@best-platform.fr');
        $userTwo->setPassword($this->encoder->hashPassword($userTwo, 'testtest'));
        $userTwo->setRoles(['ROLE_USER']);
        $userTwo->setCustomer($this->getReference(CustomerFixtures::CUSTOMER_ORANGE));
        $manager->persist($userTwo);

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            CustomerFixtures::class,
        ];
    }
}
