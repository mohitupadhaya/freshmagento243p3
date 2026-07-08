<?php
namespace Custom\CustomCheckout\Plugin\Checkout;

class LayoutProcessorPlugin
{
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ): array {
        $shippingFields = &$jsLayout['components']['checkout']['children']
            ['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children'];

        // ── 1. DELIVERY DATE ──────────────────────────────────────────────────
        $shippingFields['delivery_date'] = [
            'component'  => 'Magento_Ui/js/form/element/date',
            'config'     => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template'    => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/date',
                'id'          => 'delivery_date',
                'options'     => [
                    'dateFormat'    => 'MM/dd/yyyy',   // display format
                    'minDate'       => 0,              // today or later
                    'showOn'        => 'button',
                ],
            ],
            'dataScope'  => 'shippingAddress.custom_attributes.delivery_date',
            'label'      => __('Delivery Date'),
            'provider'   => 'checkoutProvider',
            'visible'    => true,
            'validation' => ['required-entry' => true],
            'sortOrder'  => 200,
            'id'         => 'delivery_date',
        ];

        // ── 2. DELIVERY TIME ──────────────────────────────────────────────────
        $shippingFields['delivery_time'] = [
            'component'  => 'Magento_Ui/js/form/element/select',
            'config'     => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template'    => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id'          => 'delivery_time',
            ],
            'dataScope'  => 'shippingAddress.custom_attributes.delivery_time',
            'label'      => __('Preferred Delivery Time'),
            'provider'   => 'checkoutProvider',
            'visible'    => true,
            'validation' => ['required-entry' => true],
            'sortOrder'  => 210,
            'id'         => 'delivery_time',
            'options'    => [
                ['value' => '',          'label' => __('-- Select Time --')],
                ['value' => '08:00-12:00', 'label' => __('Morning (08:00 – 12:00)')],
                ['value' => '12:00-16:00', 'label' => __('Afternoon (12:00 – 16:00)')],
                ['value' => '16:00-20:00', 'label' => __('Evening (16:00 – 20:00)')],
            ],
        ];

        // ── 3. SHIPPING INSTRUCTION (after shipping method) ───────────────────
        // Target: sidebar > shipping-information block OR
        // shippingAddress > before-form-fields (renders below method selection)
        $jsLayout['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['before-form-fields']['children']['shipping_instruction'] = [
            'component'  => 'Magento_Ui/js/form/element/textarea',
            'config'     => [
                'customScope' => 'shippingAddress',
                'template'    => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/textarea',
                'id'          => 'shipping_instruction',
                'rows'        => 4,
            ],
            'dataScope'  => 'shippingAddress.shipping_instruction',
            'label'      => __('Shipping Instructions'),
            'placeholder'=> __('Any special delivery instructions?'),
            'provider'   => 'checkoutProvider',
            'visible'    => true,
            'validation' => [],
            'sortOrder'  => 5,   // low number = renders near top of that region
            'id'         => 'shipping_instruction',
        ];

        return $jsLayout;
    }
}