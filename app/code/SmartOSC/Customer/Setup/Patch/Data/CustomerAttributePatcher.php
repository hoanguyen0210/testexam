<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SmartOSC\Customer\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Config as EavConfig;

/**
 * Class \Magento\Bundle\Setup\Patch\ApplyAttributesUpdate
 */
class CustomerAttributePatcher implements DataPatchInterface, PatchVersionInterface
{
    const CUSTOMER_HOBBIES_ATTRIBUTE = 'hobbies';
    const CUSTOMER_INCOME_ATTRIBUTE = 'income';
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * @var EavConfig
     */
    private $eavConfig;

    /**
     * ApplyAttributesUpdate constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        CustomerSetupFactory $customerSetupFactory,
        EavConfig $eavConfig
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->eavConfig = $eavConfig;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            self::CUSTOMER_HOBBIES_ATTRIBUTE,
            [
                'type' => 'text',
                'label' => __('Hobbies'),
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
            ]
        );
        $customerHobbiesAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, self::CUSTOMER_HOBBIES_ATTRIBUTE);
        $customerHobbiesAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer', 'adminhtml_checkout', 'customer_account_edit', 'checkout_register']

        );
        $customerHobbiesAttribute->save();

        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            self::CUSTOMER_INCOME_ATTRIBUTE,
            [
                'type' => 'text',
                'label' => __('Income'),
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'position' => 999,
                'system' => 0,
            ]
        );
        $customerIncomeAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, self::CUSTOMER_INCOME_ATTRIBUTE);
        $customerIncomeAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer', 'adminhtml_checkout', 'customer_account_edit', 'checkout_register']

        );
        $customerIncomeAttribute->save();
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getVersion()
    {
        return '1.0.0';
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
