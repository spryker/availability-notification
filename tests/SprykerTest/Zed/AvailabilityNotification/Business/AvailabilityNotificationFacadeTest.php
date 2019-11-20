<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Business;

use Codeception\Test\Unit;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationDependencyProvider;
use Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationBusinessFactory;
use Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityNotification
 * @group Business
 * @group Facade
 * @group AvailabilityNotificationFacadeTest
 * Add your own group annotations below this line
 */
class AvailabilityNotificationFacadeTest extends Unit
{
    public const TESTER_INVALID_EMAIL = 'invalid<>example@spryker.com';

    public const TESTER_INCORRECT_SUBSCRIPTION_KEY = '992233445566778899';

    /**
     * @var \SprykerTest\Zed\AvailabilityNotification\AvailabilityNotificationBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGuestSubscribeShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct()
        );

        $response = $this->getAvailabilityNotificationFacadeMock()->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCustomerSubscribeShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            $this->tester->haveCustomer()
        );

        $response = $this->getAvailabilityNotificationFacadeMock()->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSubscribeFailsWhenEmailIsInvalid(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            null,
            [
                'email' => static::TESTER_INVALID_EMAIL,
            ]
        );

        $response = $this->getAvailabilityNotificationFacadeMock()->subscribe($availabilityNotificationSubscription);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSubscribeForAlreadySubscribedTypeShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct()
        );

        $response = $this->getAvailabilityNotificationFacadeMock()->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeBySubscriptionKeyShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscription(
            $this->tester->haveProduct()
        );

        $response = $this->getAvailabilityNotificationFacadeMock()->unsubscribeBySubscriptionKey($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeByCustomerReferenceAndSkuShouldSucceed(): void
    {
        $availabilityNotificationFacade = $this->getAvailabilityNotificationFacadeMock();

        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            $this->tester->haveCustomer()
        );

        $availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $response = $availabilityNotificationFacade->unsubscribeByCustomerReferenceAndSku($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeWithIncorrectSubscriptionKeyShouldFail(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            null,
            [
                'subscription_key' => static::TESTER_INCORRECT_SUBSCRIPTION_KEY,
            ]
        );

        $response = $this->getAvailabilityNotificationFacadeMock()->unsubscribeBySubscriptionKey($availabilityNotificationSubscription);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testAnonymize(): void
    {
        $availabilityNotificationFacade = $this->getAvailabilityNotificationFacadeMock();

        $product = $this->tester->haveProduct();

        $customer = $this->tester->haveCustomer();

        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer($product, $customer);

        $availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $availabilityNotificationFacade->anonymizeSubscription($customer);

        $repositoryMock = $this->getMockBuilder(AvailabilityNotificationRepositoryInterface::class)->getMock();

        $result = $repositoryMock
            ->findOneByCustomerReferenceAndSku(
                $customer->getCustomerReference(),
                $availabilityNotificationSubscription->getSku(),
                $availabilityNotificationSubscription->getStore()->getIdStore()
            );

        $this->assertNull($result);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade
     */
    protected function getAvailabilityNotificationFacadeMock(): AvailabilityNotificationFacade
    {
        $availabilityNotificationFacade = new AvailabilityNotificationFacade();
        $container = new Container();
        $availabilityNotificationDependencyProvider = new AvailabilityNotificationDependencyProvider();
        $availabilityNotificationDependencyProvider->provideBusinessLayerDependencies($container);

        $mailFacadeMock = $this->getMockBuilder(AvailabilityNotificationToMailFacadeInterface::class)->getMock();
        $container[AvailabilityNotificationDependencyProvider::FACADE_MAIL] = $mailFacadeMock;

        $availabilityNotificationBusinessFactory = new AvailabilityNotificationBusinessFactory();
        $availabilityNotificationBusinessFactory->setContainer($container);

        $availabilityNotificationFacade->setFactory($availabilityNotificationBusinessFactory);

        return $availabilityNotificationFacade;
    }
}
