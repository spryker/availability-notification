<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Spryker\Service\UtilText\UtilTextService;

class SubscriptionKeyGenerator implements SubscriptionKeyGeneratorInterface
{
    /**
     * @return string
     */
    public function generateKey(): string
    {
        return (new UtilTextService())->generateRandomString(32);
    }
}
