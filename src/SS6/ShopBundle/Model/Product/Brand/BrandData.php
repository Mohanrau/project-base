<?php

namespace SS6\ShopBundle\Model\Product\Brand;

use SS6\ShopBundle\Form\UrlListData;
use SS6\ShopBundle\Model\Product\Brand\Brand;

class BrandData {

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string[]
	 */
	public $image;

	/**
	 * @var string[]
	 */
	public $descriptions;

	/**
	 * @var \SS6\ShopBundle\Form\UrlListData
	 */
	public $urls;

	public function __construct() {
		$this->name = '';
		$this->image = [];
		$this->descriptions = [];
		$this->urls = new UrlListData();
	}

	/**
	 * @param \SS6\ShopBundle\Model\Product\Brand\Brand $brand
	 */
	public function setFromEntity(Brand $brand) {
		$this->name = $brand->getName();

		$translations = $brand->getTranslations();
		/* @var $translations \SS6\ShopBundle\Model\Product\Brand\BrandTranslation[]  */

		$this->descriptions = [];
		foreach ($translations as $translate) {
			$this->descriptions[$translate->getLocale()] = $translate->getDescription();
		}
	}

}
