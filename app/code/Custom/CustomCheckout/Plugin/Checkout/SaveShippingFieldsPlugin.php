<?php
namespace Custom\CustomCheckout\Plugin\Checkout;

use Magento\Quote\Model\QuoteRepository;

class SaveShippingFieldsPlugin
{
    public function __construct(
        private readonly QuoteRepository $quoteRepository
    ) {}

    /**
     * Fires when the user clicks "Next" on the shipping step.
     * ShippingInformationManagement::saveAddressInformation()
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ): void {
        $quote           = $this->quoteRepository->getActive($cartId);
        $shippingAddress = $addressInformation->getShippingAddress();
        $quoteAddress    = $quote->getShippingAddress();

        // ── Custom attributes (delivery_date, delivery_time) ──────────────────
        $customAttributes = $shippingAddress->getCustomAttributes();

        if (!empty($customAttributes['delivery_date'])) {
            $quoteAddress->setDeliveryDate(
                $customAttributes['delivery_date']->getValue()
            );
        }

        if (!empty($customAttributes['delivery_time'])) {
            $quoteAddress->setDeliveryTime(
                $customAttributes['delivery_time']->getValue()
            );
        }

        // ── Extension attributes (shipping_instruction) ───────────────────────
        $extAttributes = $shippingAddress->getExtensionAttributes();
        if ($extAttributes && $extAttributes->getShippingInstruction()) {
            $quoteAddress->setShippingInstruction(
                $extAttributes->getShippingInstruction()
            );
        }

        $this->quoteRepository->save($quote);
    }
}