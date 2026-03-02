<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AvailabilityNotificationDataBuilder;
use Generated\Shared\DataBuilder\AvailabilityNotificationSubscriptionBuilder;
use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AvailabilityNotificationDataHelper extends Module
{
    use LocatorHelperTrait;

    public function haveAvailabilityNotificationSubscriptionTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        ?CustomerTransfer $customerTransfer = null,
        array $seedData = []
    ): AvailabilityNotificationSubscriptionTransfer {
        $availabilityNotificationSubscriptionTransfer = (new AvailabilityNotificationSubscriptionBuilder($seedData))
            ->build()
            ->setSku($productConcreteTransfer->getSKU());

        if ($customerTransfer) {
            $availabilityNotificationSubscriptionTransfer->setCustomerReference($customerTransfer->getCustomerReference());
        }

        return $availabilityNotificationSubscriptionTransfer;
    }

    public function haveAvailabilityNotificationSubscription(
        ProductConcreteTransfer $productConcreteTransfer,
        ?CustomerTransfer $customerTransfer = null,
        array $seedData = []
    ): AbstractTransfer {
        $availabilityNotificationSubscription = $this->haveAvailabilityNotificationSubscriptionTransfer($productConcreteTransfer, $customerTransfer, $seedData);

        $result = $this->getAvailabilityNotificationSubscriptionFacade()->subscribe($availabilityNotificationSubscription);

        return $result->getAvailabilityNotificationSubscription();
    }

    public function haveAvailabilityNotificationDataTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        array $seedData = []
    ): AvailabilityNotificationDataTransfer {
        return (new AvailabilityNotificationDataBuilder($seedData))
            ->build()
            ->setSku($productConcreteTransfer->getSku());
    }

    protected function getAvailabilityNotificationSubscriptionFacade(): AvailabilityNotificationFacade
    {
        return $this->getLocator()->availabilityNotification()->facade();
    }
}
