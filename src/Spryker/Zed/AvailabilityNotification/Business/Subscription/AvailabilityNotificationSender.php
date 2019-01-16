<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationSubscribedMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Mail\AvailabilityNotificationUnsubscribedMailTypePlugin;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMoneyFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;

class AvailabilityNotificationSender implements AvailabilityNotificationSenderInterface
{
    public const ROUTE_UNSUBSCRIBE = '/availability-notification/unsubscribe';

    public const PARAM_SUBSCRIPTION_KEY = 'subscriptionKey';

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        AvailabilityNotificationToMailFacadeInterface $mailFacade,
        AvailabilityNotificationToProductFacadeInterface $productFacade,
        AvailabilityNotificationToMoneyFacadeInterface $moneyFacade,
        AvailabilityNotificationToPriceProductFacadeInterface $priceProductFacade,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->mailFacade = $mailFacade;
        $this->productFacade = $productFacade;
        $this->moneyFacade = $moneyFacade;
        $this->priceProductFacade = $priceProductFacade;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function sendSubscriptionMail(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void
    {
        $productConcreteTransfer = $this->productFacade->getProductConcrete($availabilitySubscriptionTransfer->getSku());
        $productAttributes = $this->getProductAttributes(
            $productConcreteTransfer,
            $availabilitySubscriptionTransfer->getLocale()
        );
        $unsubscriptionLink = $this->urlGenerator->createUnsubscriptionLink($availabilitySubscriptionTransfer);

        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationSubscribedMailTypePlugin::MAIL_TYPE)
            ->setProduct($productConcreteTransfer)
            ->setProductAttributes($productAttributes)
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer)
            ->setAvailabilityUnsubscriptionLink($unsubscriptionLink)
            ->setLocale($availabilitySubscriptionTransfer->getLocale());

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return void
     */
    public function sendUnsubscriptionMail(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): void
    {
        $mailTransfer = (new MailTransfer())
            ->setType(AvailabilityNotificationUnsubscribedMailTypePlugin::MAIL_TYPE)
            ->setAvailabilitySubscription($availabilitySubscriptionTransfer)
            ->setLocale($availabilitySubscriptionTransfer->getLocale());

        $mailTransfer = $this->setLocalizedAttributes($mailTransfer, $availabilitySubscriptionTransfer);

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function setLocalizedAttributes(MailTransfer $mailTransfer, AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): MailTransfer
    {
        $productConcreteTransfer = $this->productFacade->getProductConcrete($availabilitySubscriptionTransfer->getSku());

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            if ($availabilitySubscriptionTransfer->getLocale()->getIdLocale() === $localizedAttributesTransfer->getLocale()->getIdLocale()) {
                $mailTransfer->setLocalizedAttributes($localizedAttributesTransfer);

                return $mailTransfer;
            }
        }

        return $mailTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getProductAttributes(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $localeTransfer
    ): array {
        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $imageSetTransfer */
        $imageSetTransfer = current($productConcreteTransfer->getImageSets());
        /** @var \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer */
        $productImageTransfer = current($imageSetTransfer->getProductImages());
        $attributes = ['image' => $productImageTransfer->getExternalUrlLarge()];

        $amount = $this->priceProductFacade->findPriceBySku($productConcreteTransfer->getSku());
        $priceTransfer = $this->moneyFacade->fromInteger($amount);
        $price = $this->moneyFacade->formatWithSymbol($priceTransfer);
        $attributes['price'] = $price;

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributes) {
            if ($localizedAttributes->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                return array_merge($attributes, $localizedAttributes->toArray());
            }
        }

        return $attributes;
    }
}