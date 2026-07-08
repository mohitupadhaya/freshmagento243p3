<?php
namespace Custom\CustomCheckout\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class SaveFieldsToOrder implements ObserverInterface
{
    /**
     * Event: sales_model_service_quote_submit_before
     */
    public function execute(Observer $observer): void
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote        = $observer->getEvent()->getQuote();
        $quoteAddress = $quote->getShippingAddress();

        $deliveryDate        = $quoteAddress->getDeliveryDate();
        $deliveryTime        = $quoteAddress->getDeliveryTime();
        $shippingInstruction = $quoteAddress->getShippingInstruction();

        // ── Save to sales_order (main order row) ──────────────────────────────
        $order->setDeliveryDate($deliveryDate)
              ->setDeliveryTime($deliveryTime)
              ->setShippingInstruction($shippingInstruction);

        // ── Save to sales_order_address ───────────────────────────────────────
        $orderAddress = $order->getShippingAddress();
        if ($orderAddress) {
            $orderAddress->setDeliveryDate($deliveryDate)
                         ->setDeliveryTime($deliveryTime)
                         ->setShippingInstruction($shippingInstruction);
        }
    }
}