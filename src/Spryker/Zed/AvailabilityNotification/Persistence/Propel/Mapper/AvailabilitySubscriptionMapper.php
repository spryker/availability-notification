<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription;

class AvailabilitySubscriptionMapper implements AvailabilitySubscriptionMapperInterface
{
    /**
     * @param \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription $availabilitySubscriptionEntity
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    public function mapAvailabilitySubscriptionTransfer(SpyAvailabilitySubscription $availabilitySubscriptionEntity): AvailabilitySubscriptionTransfer
    {
        $availabilitySubscriptionTransfer = new AvailabilitySubscriptionTransfer();
        $availabilitySubscriptionTransfer->fromArray($availabilitySubscriptionEntity->toArray(), true);

        $storeTransfer = new StoreTransfer();
        $storeTransfer->fromArray($availabilitySubscriptionEntity->getSpyStore()->toArray(), true);

        $availabilitySubscriptionTransfer->setStore($storeTransfer);

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($availabilitySubscriptionEntity->getSpyLocale()->toArray(), true);

        $availabilitySubscriptionTransfer->setLocale($localeTransfer);

        return $availabilitySubscriptionTransfer;
    }
}
