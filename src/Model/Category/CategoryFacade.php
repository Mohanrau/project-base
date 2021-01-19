<?php

declare(strict_types=1);

namespace App\Model\Category;

use App\Model\Product\Product;
use Shopsys\FrameworkBundle\Model\Category\CategoryFacade as BaseCategoryFacade;

class CategoryFacade extends BaseCategoryFacade
{
    /**
     * @param int $productId
     * @param int $domainId
     * @return \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomain[]
     */
    public function getProductVisibleAndListableProductCategoryDomains(int $productId, int $domainId): array
    {
        return $this->categoryRepository->getProductVisibleAndListableProductCategoryDomains($productId, $domainId);
    }
}
