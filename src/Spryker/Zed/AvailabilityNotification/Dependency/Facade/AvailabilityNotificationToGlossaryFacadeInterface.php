<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\TranslationTransfer;

interface AvailabilityNotificationToGlossaryFacadeInterface
{
    public function hasTranslation(string $keyName, ?LocaleTransfer $localeTransfer = null): bool;

    public function getTranslation(string $keyName, LocaleTransfer $localeTransfer): TranslationTransfer;
}
