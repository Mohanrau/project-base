<?php

declare(strict_types=1);

namespace App\Model\Category;

use App\Model\Product\Product;
use Shopsys\FrameworkBundle\Model\Category\CategoryFacade as BaseCategoryFacade;

class CategoryFacade extends BaseCategoryFacade
{
    /**
     * @param \App\Model\Product\Product $product
     * @param int $domainId
     * @return \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomain[]
     */
    public function getProductVisibleAndListableProductCategoryDomains(Product $product, int $domainId): array
    {
        return $this->categoryRepository->getProductVisibleAndListableProductCategoryDomains($product, $domainId);
    }
}
