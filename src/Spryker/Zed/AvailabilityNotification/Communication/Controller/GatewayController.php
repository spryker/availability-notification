<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Communication\Controller;

use Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacadeInterface getFacade()
 * @method \Spryker\Zed\AvailabilityNotification\Communication\AvailabilityNotificationCommunicationFactory getFactory()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface getRepository()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function subscribeAction(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer {
        return $this->getFacade()->subscribe($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeByCustomerReferenceAndSkuAction(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer {
        return $this->getFacade()->unsubscribeByCustomerReferenceAndSku($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer
     */
    public function unsubscribeBySubscriptionKeyAction(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer {
        return $this->getFacade()->unsubscribeBySubscriptionKey($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer $availabilityNotificationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer
     */
    public function getAvailabilityNotificationsAction(
        AvailabilityNotificationCriteriaTransfer $availabilityNotificationCriteriaTransfer
    ): AvailabilityNotificationSubscriptionCollectionTransfer {
        return $this->getFacade()->getAvailabilityNotifications($availabilityNotificationCriteriaTransfer);
    }
}
