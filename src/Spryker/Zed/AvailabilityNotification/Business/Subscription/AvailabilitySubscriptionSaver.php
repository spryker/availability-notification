<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface;

class AvailabilitySubscriptionSaver implements AvailabilitySubscriptionSaverInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGeneratorInterface
     */
    protected $keyGenerator;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected $availabilityNotificationToStoreFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface
     */
    protected $availabilityNotificationToLocaleFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReaderInterface
     */
    protected $availabilitySubscriptionReader;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationEntityManagerInterface $entityManager
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionKeyGeneratorInterface $keyGenerator
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilitySubscriptionReaderInterface $availabilitySubscriptionReader
     */
    public function __construct(
        AvailabilityNotificationEntityManagerInterface $entityManager,
        AvailabilitySubscriptionKeyGeneratorInterface $keyGenerator,
        AvailabilityNotificationToStoreFacadeInterface $availabilityNotificationToStoreFacade,
        AvailabilityNotificationToLocaleFacadeInterface $availabilityNotificationToLocaleFacade,
        AvailabilitySubscriptionReaderInterface $availabilitySubscriptionReader
    ) {
        $this->entityManager = $entityManager;
        $this->keyGenerator = $keyGenerator;
        $this->availabilityNotificationToStoreFacade = $availabilityNotificationToStoreFacade;
        $this->availabilityNotificationToLocaleFacade = $availabilityNotificationToLocaleFacade;
        $this->availabilitySubscriptionReader = $availabilitySubscriptionReader;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    public function save(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): AvailabilitySubscriptionTransfer
    {
        $availabilitySubscriptionTransfer->requireSku();
        $availabilitySubscriptionTransfer->requireEmail();

        $existedAvailabilitySubscriptionTransfer = $this->availabilitySubscriptionReader
            ->findOneByEmailAndSku($availabilitySubscriptionTransfer->getEmail(), $availabilitySubscriptionTransfer->getSku());

        if ($existedAvailabilitySubscriptionTransfer !== null) {
            return $existedAvailabilitySubscriptionTransfer;
        }

        $subscriptionKey = $this->keyGenerator->generateKey();
        $availabilitySubscriptionTransfer->setSubscriptionKey($subscriptionKey);

        $store = $this->availabilityNotificationToStoreFacade->getCurrentStore();
        $availabilitySubscriptionTransfer->setStore($store);

        $locale = $this->availabilityNotificationToLocaleFacade->getCurrentLocale();
        $availabilitySubscriptionTransfer->setLocale($locale);

        $this->entityManager->saveAvailabilitySubscription($availabilitySubscriptionTransfer);

        return $availabilitySubscriptionTransfer;
    }
}
