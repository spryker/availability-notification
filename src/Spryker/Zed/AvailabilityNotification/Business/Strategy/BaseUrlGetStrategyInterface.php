<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Strategy;

use Generated\Shared\Transfer\StoreTransfer;

interface BaseUrlGetStrategyInterface
{
    public function isApplicable(?StoreTransfer $storeTransfer = null): bool;

    public function getBaseUrl(?StoreTransfer $storeTransfer = null): string;
}
