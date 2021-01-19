<?php

declare(strict_types=1);

namespace App\ReadModel\Product\Detail;

use App\Model\Category\CategoryFacade;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Customer\User\CurrentCustomerUser;
use Shopsys\FrameworkBundle\Model\Product\Pricing\PriceFactory;
use Shopsys\FrameworkBundle\Model\Product\ProductElasticsearchProvider;
use Shopsys\FrameworkBundle\Model\Seo\SeoSettingFacade;
use Shopsys\ReadModelBundle\Brand\BrandView;
use Shopsys\ReadModelBundle\Brand\BrandViewFactory;
use Shopsys\ReadModelBundle\Image\ImageViewFacadeInterface;
use Shopsys\ReadModelBundle\Parameter\ParameterViewFactory;
use Shopsys\ReadModelBundle\Product\Action\ProductActionViewFactory;
use Shopsys\ReadModelBundle\Product\Detail\ProductDetailViewElasticsearchFactory as BaseProductDetailViewElasticsearchFactory;
use Shopsys\ReadModelBundle\Product\Listed\ListedProductViewFactory;

class ProductDetailViewElasticsearchFactory extends BaseProductDetailViewElasticsearchFactory
{
    /**
     * @var \App\Model\Category\CategoryFacade
     */
    protected CategoryFacade $categoryFacade;

    public function __construct(ImageViewFacadeInterface $imageViewFacade, CurrentCustomerUser $currentCustomerUser, ProductActionViewFactory $productActionViewFactory, ParameterViewFactory $parameterViewFactory, BrandViewFactory $brandViewFactory, SeoSettingFacade $seoSettingFacade, Domain $domain, ProductElasticsearchProvider $productElasticsearchProvider, ListedProductViewFactory $listedProductViewFactory, PriceFactory $priceFactory, CategoryFacade $categoryFacade)
    {
        parent::__construct($imageViewFacade, $currentCustomerUser, $productActionViewFactory, $parameterViewFactory, $brandViewFactory, $seoSettingFacade, $domain, $productElasticsearchProvider, $listedProductViewFactory, $priceFactory);
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param array $productArray
     * @param \Shopsys\ReadModelBundle\Image\ImageView[] $imageViews
     * @param \Shopsys\ReadModelBundle\Parameter\ParameterView[] $parameterViews
     * @param \Shopsys\ReadModelBundle\Brand\BrandView $brandView
     * @param \Shopsys\ReadModelBundle\Product\Listed\ListedProductView[] $accessories
     * @param \Shopsys\ReadModelBundle\Product\Listed\ListedProductView[] $variants
     * @return \App\ReadModel\Product\Detail\ProductDetailView
     */
    protected function createInstance(
        array $productArray,
        array $imageViews,
        array $parameterViews,
        BrandView $brandView,
        array $accessories,
        array $variants
    ): ProductDetailView {
        return new ProductDetailView(
            $productArray['id'],
            $productArray['seo_h1'] ?: $productArray['name'],
            $productArray['description'],
            $productArray['availability'],
            $this->priceFactory->createProductPriceFromArrayByPricingGroup(
                $productArray['prices'],
                $this->currentCustomerUser->getPricingGroup()
            ),
            $productArray['catnum'],
            $productArray['partno'],
            $productArray['ean'],
            $productArray['main_category_id'],
            $productArray['calculated_selling_denied'],
            $productArray['in_stock'],
            $productArray['is_main_variant'],
            $productArray['main_variant_id'],
            $productArray['flags'],
            $productArray['seo_title'] ?: $productArray['name'],
            $this->getSeoMetaDescription($productArray),
            $this->productActionViewFactory->createFromArray($productArray),
            $brandView,
            $this->getMainImageView($imageViews),
            $imageViews,
            $parameterViews,
            $accessories,
            $variants,
            $this->categoryFacade->getProductVisibleAndListableProductCategoryDomains($productArray['id'], $this->domain->getId())
        );
    }
}
