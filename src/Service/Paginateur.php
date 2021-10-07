<?php

/*
 * Ce fichier est la propriété de Kairios
 */

namespace RestPaginateur\Service;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use RestPaginateur\Entity\Pagination;
use RestPaginateur\Factory\PaginationFactory;
use Symfony\Component\HttpFoundation\Request;

class Paginateur implements PaginateurInterface
{
    /** @var PaginatorInterface */
    private $paginator;

    /** @var PaginationFactory */
    private $paginationFactory;

    public function __construct(
        PaginatorInterface $paginator,
        PaginationFactory $paginationFactory
    ) {
        $this->paginator = $paginator;
        $this->paginationFactory = $paginationFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function paginer(
        Request $request,
        QueryBuilder $queryBuilder,
        int $nombreParPage = self::NOMBRE_PAR_PAGE
    ): Pagination {
        $page = $request->get('page', 1);

        $pagination = $this->paginator->paginate(
            $queryBuilder,
            $page,
            $nombreParPage,
            [
                'wrap-queries' => true,
            ]
        );

        return $this->paginationFactory->creer(
            $request,
            $pagination->getItems(),
            $pagination->getTotalItemCount(),
            $page,
            $nombreParPage
        );
    }
}
