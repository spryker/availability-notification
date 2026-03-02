<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilTextServiceInterface;

class AvailabilityNotificationSubscriptionKeyGenerator implements AvailabilityNotificationSubscriptionKeyGeneratorInterface
{
    /**
     * @var int
     */
    public const DEFAULT_STRING_LENGTH = 32;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Service\AvailabilityNotificationToUtilTextServiceInterface
     */
    protected $utilTextService;

    public function __construct(AvailabilityNotificationToUtilTextServiceInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    public function generateKey(): string
    {
        return $this->utilTextService->generateRandomString(static::DEFAULT_STRING_LENGTH);
    }
}
