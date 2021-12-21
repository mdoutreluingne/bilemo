<?php

namespace App\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\FilterExtension;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\PaginationExtension;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class UserCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $security;
    private $entityManager;
    private $pagination;
    private $filter;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        PaginationExtension $pagination,
        FilterExtension $filter
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->pagination = $pagination;
        $this->filter = $filter;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return  User::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        # Create queryBuilder
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder = $this->requestAccess($this->security->getUser(), $queryBuilder);

        $generator = new QueryNameGenerator();

        # Apply filters & pagination
        $this->filter->applyToCollection($queryBuilder, $generator, $resourceClass, $operationName, $context);
        $this->pagination->applyToCollection($queryBuilder, $generator, $resourceClass, $operationName, $context);

        return $this->pagination->getResult($queryBuilder, $resourceClass, $operationName, $context);
    }

    /**
     * Execute request in function of ROLE
     *
     * @param User $user
     * @param QueryBuilder $queryBuilder
     * @return QueryBuilder
     */
    private function requestAccess(User $user, QueryBuilder $queryBuilder): QueryBuilder
    {
        if ($this->security->isGranted("ROLE_ADMIN")) {
            return $queryBuilder->select('u')
                ->from(User::class, 'u');
        }

        return $queryBuilder->select('u')
            ->from(User::class, 'u')
            ->andWhere('u.customer = :customer')
            ->setParameter('customer', $user->getCustomer());
    }
}
