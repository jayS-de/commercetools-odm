<?php

namespace BestIt\CommercetoolsODM\ActionBuilder\ProductType;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderAbstract;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataInterface;
use Commercetools\Core\Model\ProductType\ProductType;
use Commercetools\Core\Request\AbstractAction;
use Commercetools\Core\Request\ProductTypes\Command\ProductTypeAddAttributeDefinitionAction;

/**
 * Builds the action to add an attribute to a product type.
 * @author blange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 * @subpackage ActionBuilder\ProductType
 * @version $id$
 */
class AddAttribute extends ActionBuilderAbstract
{
    /**
     * The field name.
     * @var string
     */
    protected $fieldName = 'attributes/*';

    /**
     * For which class is this description used?
     * @var string
     */
    protected $modelClass = ProductType::class;

    /**
     * Creates the update action for the given class and data.
     * @param mixed $changedValue
     * @param ClassMetadataInterface $metadata
     * @param array $changedData
     * @param array $oldData
     * @param ProductType $sourceObject
     * @param string $subFieldName If you work on attributes etc. this is the name of the specific attribute.
     * @return AbstractAction|void
     * @todo Can more then one attribute be added?
     */
    public function createUpdateAction(
        $changedValue,
        ClassMetadataInterface $metadata,
        array $changedData,
        array $oldData,
        $sourceObject,
        string $subFieldName = ''
    ) {
        /** @var AttributeDefinition $attribute */
        foreach ($sourceObject->getAttributes() as $attribute) {
            $searchName = $attribute->getName();

            $foundAttr = array_filter($oldData['attributes'] ?? [], function (array $oldAttr) use ($searchName) {
                return @$oldAttr['name'] === $searchName;
            });

            if (!$foundAttr) {
                return ProductTypeAddAttributeDefinitionAction::ofAttribute($attribute);
            }
        }
    }
}