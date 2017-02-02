<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\Cart;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\Cart\Cart;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\Carts\Command\CartChangeLineItemQuantityAction;

/**
 * Builds the action to change cart item quantity
 * @author chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\Cart
 * @version $id$
 */
class SetLineItemQuantity extends ActionBuilderAbstract
{
    /**
     * A PCRE to match the hierarchical field path without delimiter.
     * @var string
     */
    protected $complexFieldFilter = 'lineItems/[^/]+';

    /**
     * For which class is this description used?
     * @var string
     */
    protected $modelClass = Cart::class;

    /**
     * Creates the update action for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param Cart $sourceObject
     * @param string $subFieldName If you work on attributes etc. this is the name of the specific attribute.
     * @return AbstractAction[]
     */
    public function createUpdateActions(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject,
        string $subFieldName = ''
    ): array {
        $actions = [];

        // Only process if quantity was changed and is > 0
        if (!isset($changedValue['quantity']) || $changedValue['quantity'] <= 0) {
            return $actions;
        }

        $offset = key($changedData);
        $lineItemId = $sourceObject->getLineItems()->getAt($offset)->getId();

        // Do not process on added items
        if (!$lineItemId) {
            return $actions;
        }

        $actions[] = CartChangeLineItemQuantityAction::fromArray([
            'lineItemId' => $lineItemId,
            'quantity' => $changedValue['quantity']
        ]);

        return $actions;
    }
}
