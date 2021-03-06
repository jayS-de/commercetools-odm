<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM\Repository;

use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Model\Order\Order;

/**
 * Repository for orders.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM\Repository
 */
interface OrderRepositoryInterface extends ObjectRepository
{
    /**
     * Creates an order frmo a cart.
     * @param Cart $cart
     * @return Order
     */
    public function createFromCart(Cart $cart): Order;

    /**
     * Removes the given order.
     * @param Order $order The order.
     * @param bool $direct Should the deletion be deleted directly with a doc manager flush?
     * @return void
     */
    public function deleteOrder(Order $order, bool $direct = false);

    /**
     * Imports the given order.
     * @param Order $order The importable order.
     * @return Order The imported order.
     */
    public function import(Order $order): Order;
}
