<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Product;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;

class ProductAttributeFinder implements ProductAttributeFinderInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface $urlGenerator
     */
    public function __construct(AvailabilityNotificationToProductFacadeInterface $productFacade, UrlGeneratorInterface $urlGenerator)
    {
        $this->productFacade = $productFacade;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    public function findProductName(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer): ?string
    {
        $attributes = [];

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            if ($localizedAttributes->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                $attributes = array_merge($attributes, $localizedAttributes->toArray());
            }
        }

        return $attributes['name'] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string|null
     */
    public function findProductUrl(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $localeTransfer,
        ?StoreTransfer $storeTransfer = null
    ): ?string {
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($productConcreteTransfer->getFkProductAbstract());

        if ($productAbstractTransfer === null) {
            return null;
        }

        $productUrlTransfer = $this->productFacade->getProductUrl($productAbstractTransfer);

        foreach ($productUrlTransfer->getUrls() as $localizedUrlTransfer) {
            if ($localeTransfer->getIdLocale() === $localizedUrlTransfer->getLocale()->getIdLocale()) {
                return $this->urlGenerator->generateProductUrl($localizedUrlTransfer, $storeTransfer);
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string|null
     */
    public function findExternalProductImage(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        if ($productConcreteTransfer->getImageSets()->count() === 0) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer */
        $productImageSetTransfer = $productConcreteTransfer->getImageSets()->getIterator()->current();

        if ($productImageSetTransfer->getProductImages()->count() === 0) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer */
        $productImageTransfer = $productImageSetTransfer->getProductImages()->getIterator()->current();

        return $productImageTransfer->getExternalUrlLarge();
    }
}
