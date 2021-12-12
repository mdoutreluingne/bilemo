<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{
    public const CUSTOMER_BILEMO = 'BileMo';
    public const CUSTOMER_SFR = 'SFR';
    public const CUSTOMER_ORANGE = 'ORANGE';

    public function load(ObjectManager $manager)
    {
        $customerOne = new Customer();
        $customerOne->setName('BileMo');
        $manager->persist($customerOne);

        $customerTwo = new Customer();
        $customerTwo->setName('SFR');
        $manager->persist($customerTwo);

        $customerThree = new Customer();
        $customerThree->setName('Orange');
        $manager->persist($customerThree);

        $manager->flush();

        $this->addReference(self::CUSTOMER_BILEMO, $customerOne);
        $this->addReference(self::CUSTOMER_SFR, $customerTwo);
        $this->addReference(self::CUSTOMER_ORANGE, $customerThree);
    }
}
