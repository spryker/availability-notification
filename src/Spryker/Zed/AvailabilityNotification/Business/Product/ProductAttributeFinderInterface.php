<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Product;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface ProductAttributeFinderInterface
{
    public function findProductName(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer): ?string;

    public function findProductUrl(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $localeTransfer,
        ?StoreTransfer $storeTransfer = null
    ): ?string;

    public function findExternalProductImage(ProductConcreteTransfer $productConcreteTransfer): ?string;
}
