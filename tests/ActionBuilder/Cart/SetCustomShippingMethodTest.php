<?php

namespace BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\Cart\AddLineItem;
use BestIt\CommercetoolsODM\ActionBuilder\Cart\SetCustomShippingMethod;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use BestIt\CommercetoolsODM\Tests\ActionBuilder\SupportTestTrait;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\ShippingMethod\ShippingRate;
use Commercetools\Core\Model\TaxCategory\TaxCategoryReference;
use Commercetools\Core\Request\Carts\Command\CartAddLineItemAction;
use Commercetools\Core\Request\Carts\Command\CartSetCustomShippingMethodAction;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Test the custom shipping method action
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Tests\ActionBuilder\Cart
 */
class SetCustomShippingMethodTest extends TestCase
{
    use SupportTestTrait;

    /**
     * The test class.
     *
     * @var SetCustomShippingMethod
     */
    protected $fixture;

    /**
     * {@inheritdoc}
     */
    public function getSupportAssertions(): array
    {
        return [
            ['shippingInfo/foo', Cart::class, true],
            ['shippingInfo/shippingMethod/id', Cart::class, true],
            ['shippingMethod/bob/', Cart::class]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fixture = new SetCustomShippingMethod();
    }

    /**
     * Checks if a no action will created if cart state mismatch
     *
     * @return void
     */
    public function testOnlyBuildActionWithState()
    {
        $cart = new Cart();

        /** @var CartAddLineItemAction[] $actions */
        $actions = $this->fixture->createUpdateActions(
            $value = uniqid(),
            static::createMock(ClassMetadataInterface::class),
            [],
            [],
            $cart
        );

        static::assertCount(0, $actions);
    }

    /**
     * Checks if a action will created without old data
     *
     * @return void
     */
    public function testBuildActionWithoutOldData()
    {
        $cart = new Cart();

        $changedData = [
            'shippingMethodState' => 'IsCustomShippingMethod',
            'shippingMethodName' => 'Foobar',
            'shippingRate' => [
                'price' => [
                    'centAmount' => 599,
                    'currency' => 'EUR'
                ]
            ],
            'taxCategory' => [
                'id' => '4a3924af-190f-4f9d-88ce-80ff585b7d65'
            ],
        ];

        $oldData = [
            'shippingInfo' => [
                'shippingMethodState' => 'IsCustomShippingMethod',
                'shippingMethodName' => 'OldFoo',
                'shippingRate' => [
                    'price' => [
                        'centAmount' => 745,
                        'currency' => 'CHF'
                    ]
                ],
                'taxCategory' => [
                    'id' => '9a3924fo-190b-4a9r-88ce-80ff585b7d65'
                ]
            ]
        ];

        /** @var CartAddLineItemAction[] $actions */
        $actions = $this->fixture->createUpdateActions(
            uniqid(),
            static::createMock(ClassMetadataInterface::class),
            $changedData,
            $oldData,
            $cart
        );

        $action = CartSetCustomShippingMethodAction::of();
        $action->setShippingMethodName($changedData['shippingMethodName']);
        $action->setShippingRate(ShippingRate::fromArray($changedData['shippingRate']));
        $action->setTaxCategory(TaxCategoryReference::ofId($changedData['taxCategory']['id']));

        static::assertCount(1, $actions);
        static::assertEquals($action, $actions[0]);
    }

    /**
     * Checks if a action will created with merged old data
     *
     * @return void
     */
    public function testBuildActionWithOldData()
    {
        $cart = new Cart();

        $changedData = [
            'shippingMethodState' => 'IsCustomShippingMethod',
            'shippingMethodName' => 'Foobar',
            'shippingRate' => [
                'price' => [
                    'centAmount' => 599
                ]
            ],
        ];

        $oldData = [
            'shippingInfo' => [
                'shippingMethodState' => 'IsCustomShippingMethod',
                'shippingMethodName' => 'OldFoo',
                'shippingRate' => [
                    'price' => [
                        'centAmount' => 745,
                        'currency' => 'CHF'
                    ]
                ],
                'taxCategory' => [
                    'id' => '9a3924fo-190b-4a9r-88ce-80ff585b7d65'
                ]
            ]
        ];

        /** @var CartAddLineItemAction[] $actions */
        $actions = $this->fixture->createUpdateActions(
            uniqid(),
            static::createMock(ClassMetadataInterface::class),
            $changedData,
            $oldData,
            $cart
        );

        $action = CartSetCustomShippingMethodAction::of();
        $action->setShippingMethodName($changedData['shippingMethodName']);
        $action->setShippingRate(ShippingRate::fromArray([
            'price' => [
                'centAmount' => 599,
                'currency' => 'CHF'
            ]
        ]));
        $action->setTaxCategory(TaxCategoryReference::ofId('9a3924fo-190b-4a9r-88ce-80ff585b7d65'));

        static::assertCount(1, $actions);
        static::assertEquals($action, $actions[0]);
    }
}
