<?php
// src/DataPersister/UserDataPersister.php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $entityManager;
    private $passwordEncoder;
    private $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;
    }

    /**
     *
     * @param mixed $data
     * @param array $context
     * @return boolean
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data, array $context = [])
    {
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->passwordEncoder->hashPassword(
                    $data,
                    $data->getPlainPassword()
                )
            );

            /* Erase all traces of the password that is not encrypted */
            $data->eraseCredentials();
        }

        /* Set client with the client of the logged in user */
        $data->setCustomer($this->security->getUser()->getCustomer());

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     *
     * @param mixed $data
     * @param array $context
     * @return void
     */
    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
