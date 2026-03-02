<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscription;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationPersistenceFactory getFactory()
 */
class AvailabilityNotificationEntityManager extends AbstractEntityManager implements AvailabilityNotificationEntityManagerInterface
{
    public function saveAvailabilityNotificationSubscription(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void
    {
        $availabilityNotificationSubscriptionTransfer->requireLocale();
        $availabilityNotificationSubscriptionTransfer->requireStore();

        $subscriptionEntity = new SpyAvailabilityNotificationSubscription();
        $subscriptionEntity->fromArray($availabilityNotificationSubscriptionTransfer->toArray());
        $subscriptionEntity->setFkStore($availabilityNotificationSubscriptionTransfer->getStore()->getIdStore());
        $subscriptionEntity->setFkLocale($availabilityNotificationSubscriptionTransfer->getLocale()->getIdLocale());

        $subscriptionEntity->save();
    }

    public function deleteBySubscriptionKey(string $subscriptionKey): void
    {
        $this->getFactory()
            ->createAvailabilityNotificationSubscriptionQuery()
            ->filterBySubscriptionKey($subscriptionKey)
            ->delete();
    }

    public function deleteByCustomerReference(string $customerReference): void
    {
        $this->getFactory()
            ->createAvailabilityNotificationSubscriptionQuery()
            ->filterByCustomerReference($customerReference)
            ->delete();
    }
}
