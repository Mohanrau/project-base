<?php

declare(strict_types=1);

namespace App\Model\Category;

use App\Model\Product\Product;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Shopsys\FrameworkBundle\Model\Category\CategoryRepository as BaseCategoryRepository;
use Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomain;

class CategoryRepository extends BaseCategoryRepository
{
    /**
     * @return \Doctrine\Persistence\ObjectRepository
     */
    private function getProductCategoryDomainRepository(): ObjectRepository
    {
        return $this->em->getRepository(ProductCategoryDomain::class);
    }

    /**
     * @param int $productId
     * @param int $domainId
     * @return \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomain[]
     */
    public function getProductVisibleAndListableProductCategoryDomains(int $productId, int $domainId): array
    {
        return $this->getProductVisibleAndListableProductCategoryDomainsQueryBuilder($productId, $domainId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $productId
     * @param int $domainId
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getProductVisibleAndListableProductCategoryDomainsQueryBuilder(int $productId, int $domainId): QueryBuilder
    {
        return $this->getProductCategoryDomainRepository()->createQueryBuilder('pcd')
            ->select('pcd')
            ->innerJoin('pcd.category', 'c')
            ->innerJoin('c.domains', 'cd')
            ->andWhere('pcd.product = :product')
            ->andWhere('pcd.domainId = :domainId')
            ->andWhere('cd.domainId = :domainId')
            ->andWhere('cd.visible = true')
            ->andWhere('c.parent IS NOT NULL')
            ->andWhere('cd.enabled = true')
            ->setParameter('product', $productId)
            ->setParameter('domainId', $domainId);
    }
}
