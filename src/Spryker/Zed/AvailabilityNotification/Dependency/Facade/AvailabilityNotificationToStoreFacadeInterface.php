<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface AvailabilityNotificationToStoreFacadeInterface
{
    public function getCurrentStore(bool $fallbackToDefault = false): StoreTransfer;

    public function isDynamicStoreEnabled(): bool;
}
