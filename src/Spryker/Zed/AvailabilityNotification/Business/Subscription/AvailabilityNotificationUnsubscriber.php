<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionResponseTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Spryker\Shared\AvailabilityNotification\AvailabilityNotificationConfig;
use Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationUnsubscriptionSenderInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface;

class AvailabilityNotificationUnsubscriber implements AvailabilityNotificationUnsubscriberInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationUnsubscriptionSenderInterface
     */
    protected $availabilityNotificationUnsubscriptionSender;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface
     */
    protected $availabilityNotificationReader;

    public function __construct(
        AvailabilityNotificationEntityManagerInterface $entityManager,
        AvailabilityNotificationUnsubscriptionSenderInterface $availabilityNotificationUnsubscriptionSender,
        AvailabilityNotificationSubscriptionReaderInterface $availabilityNotificationReader
    ) {
        $this->entityManager = $entityManager;
        $this->availabilityNotificationUnsubscriptionSender = $availabilityNotificationUnsubscriptionSender;
        $this->availabilityNotificationReader = $availabilityNotificationReader;
    }

    public function unsubscribeBySubscriptionKey(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer {
        $availabilityNotificationSubscriptionTransfer->requireSubscriptionKey();

        $availabilityNotificationSubscriptionTransfer = $this->availabilityNotificationReader->findOneBySubscriptionKey($availabilityNotificationSubscriptionTransfer->getSubscriptionKey());

        if ($availabilityNotificationSubscriptionTransfer === null) {
            return $this->createSubscriptionNotExistsResponse();
        }

        $this->unsubscribe($availabilityNotificationSubscriptionTransfer);

        return $this->createSuccessResponse($availabilityNotificationSubscriptionTransfer);
    }

    public function unsubscribeByCustomerReferenceAndSku(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer {
        $availabilityNotificationSubscriptionTransfer->requireCustomerReference();
        $availabilityNotificationSubscriptionTransfer->requireSku();

        $availabilityNotificationSubscriptionTransfer = $this->availabilityNotificationReader->findOneByCustomerReferenceAndSku(
            $availabilityNotificationSubscriptionTransfer->getCustomerReference(),
            $availabilityNotificationSubscriptionTransfer->getSku(),
        );

        if ($availabilityNotificationSubscriptionTransfer === null) {
            return $this->createSubscriptionNotExistsResponse();
        }

        $this->unsubscribe($availabilityNotificationSubscriptionTransfer);

        return $this->createSuccessResponse($availabilityNotificationSubscriptionTransfer);
    }

    protected function unsubscribe(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): void
    {
        $this->entityManager->deleteBySubscriptionKey($availabilityNotificationSubscriptionTransfer->getSubscriptionKey());
        $this->availabilityNotificationUnsubscriptionSender->send($availabilityNotificationSubscriptionTransfer);
    }

    protected function createSuccessResponse(
        AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
    ): AvailabilityNotificationSubscriptionResponseTransfer {
        return (new AvailabilityNotificationSubscriptionResponseTransfer())
            ->setIsSuccess(true)
            ->setAvailabilityNotificationSubscription($availabilityNotificationSubscriptionTransfer);
    }

    protected function createSubscriptionNotExistsResponse(): AvailabilityNotificationSubscriptionResponseTransfer
    {
        return (new AvailabilityNotificationSubscriptionResponseTransfer())
            ->setIsSuccess(false)
            ->setErrorMessage(AvailabilityNotificationConfig::MESSAGE_SUBSCRIPTION_DOES_NOT_EXIST);
    }
}
