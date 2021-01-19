<?php

declare(strict_types=1);

namespace App\ReadModel\Product\Detail;

use App\Model\Category\Category;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice;
use Shopsys\ReadModelBundle\Brand\BrandView;
use Shopsys\ReadModelBundle\Image\ImageView;
use Shopsys\ReadModelBundle\Product\Action\ProductActionView;
use Shopsys\ReadModelBundle\Product\Detail\ProductDetailView as BaseProductDetailView;

class ProductDetailView extends BaseProductDetailView
{
    /**
     * @var Category[]
     */
    private array $categories;

    /**
     * ProductDetailView constructor.
     * @param int $id
     * @param string|null $name
     * @param string|null $description
     * @param string $availability
     * @param \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice|null $sellingPrice
     * @param string|null $catnum
     * @param string|null $partno
     * @param string|null $ean
     * @param int|null $mainCategoryId
     * @param bool $isSellingDenied
     * @param bool $isInStock
     * @param bool $isMainVariant
     * @param int|null $mainVariantId
     * @param array $flagIds
     * @param string|null $seoPageTitle
     * @param string|null $seoMetaDescription
     * @param \Shopsys\ReadModelBundle\Product\Action\ProductActionView $actionView
     * @param \Shopsys\ReadModelBundle\Brand\BrandView|null $brandView
     * @param \Shopsys\ReadModelBundle\Image\ImageView|null $mainImageView
     * @param array $galleryImageViews
     * @param array $parameterViews
     * @param array $accessories
     * @param array $variants
     * @param \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomain[] $categories
     */
    public function __construct(int $id, ?string $name, ?string $description, string $availability, ?ProductPrice $sellingPrice, ?string $catnum, ?string $partno, ?string $ean, ?int $mainCategoryId, bool $isSellingDenied, bool $isInStock, bool $isMainVariant, ?int $mainVariantId, array $flagIds, ?string $seoPageTitle, ?string $seoMetaDescription, ProductActionView $actionView, ?BrandView $brandView, ?ImageView $mainImageView, array $galleryImageViews, array $parameterViews, array $accessories, array $variants, array $categories)
    {
        parent::__construct($id, $name, $description, $availability, $sellingPrice, $catnum, $partno, $ean, $mainCategoryId, $isSellingDenied, $isInStock, $isMainVariant, $mainVariantId, $flagIds, $seoPageTitle, $seoMetaDescription, $actionView, $brandView, $mainImageView, $galleryImageViews, $parameterViews, $accessories, $variants);
        $this->categories = $categories;
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Product\ProductCategoryDomain[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
