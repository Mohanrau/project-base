<?php

declare(strict_types=1);

namespace App\ReadModel\Product\Detail;

use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice;
use Shopsys\FrameworkBundle\Model\Product\Product;
use Shopsys\ReadModelBundle\Brand\BrandView;
use Shopsys\ReadModelBundle\Product\Action\ProductActionView;
use Shopsys\ReadModelBundle\Product\Detail\ProductDetailViewFactory as BaseProductDetailViewFactory;

class ProductDetailViewFactory extends BaseProductDetailViewFactory
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Product\Product $product
     * @param \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice|null $sellingPrice
     * @param int $mainCategoryId
     * @param \Shopsys\ReadModelBundle\Image\ImageView[] $galleryImageViews
     * @param \Shopsys\ReadModelBundle\Brand\BrandView|null $brandView
     * @param \Shopsys\ReadModelBundle\Product\Action\ProductActionView $productActionView
     * @param \Shopsys\ReadModelBundle\Parameter\ParameterView[] $parameterViews
     * @param \Shopsys\ReadModelBundle\Product\Listed\ListedProductView[] $accessories
     * @param \Shopsys\ReadModelBundle\Product\Listed\ListedProductView[] $variants
     * @return \App\ReadModel\Product\Detail\ProductDetailView
     */
    protected function createInstance(
        Product $product,
        ?ProductPrice $sellingPrice,
        int $mainCategoryId,
        array $galleryImageViews,
        ?BrandView $brandView,
        ProductActionView $productActionView,
        array $parameterViews,
        array $accessories,
        array $variants
    ): ProductDetailView {
        $domainId = $this->domain->getId();
        $locale = $this->domain->getLocale();

        return new ProductDetailView(
            $product->getId(),
            $product->getSeoH1($domainId) ?: $product->getName($locale),
            $product->getDescription($domainId),
            $product->getCalculatedAvailability()->getName($locale),
            $sellingPrice,
            $product->getCatnum(),
            $product->getPartno(),
            $product->getEan(),
            $mainCategoryId,
            $product->getCalculatedSellingDenied(),
            $this->isProductInStock($product),
            $product->isMainVariant(),
            $product->isVariant() ? $product->getMainVariant()->getId() : null,
            $this->getFlagIdsForProduct($product),
            $product->getSeoTitle($domainId) ?: $product->getName($locale),
            $this->getSeoMetaDescription($product),
            $productActionView,
            $brandView,
            $this->getMainImageView($galleryImageViews),
            $galleryImageViews,
            $parameterViews,
            $accessories,
            $variants,
            $this->categoryFacade->getProductVisibleAndListableProductCategoryDomains($product->getId(), $domainId)
        );
    }
}
