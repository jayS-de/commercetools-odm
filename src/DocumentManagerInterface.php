<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODM;

use Commercetools\Commons\Helper\QueryHelper;
use Commercetools\Core\Client;
use Commercetools\Core\Request\AbstractApiRequest;
use Commercetools\Core\Request\AbstractCreateRequest;
use Commercetools\Core\Request\AbstractDeleteRequest;
use Commercetools\Core\Request\AbstractUpdateRequest;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerAwareInterface;

/**
 * The public api for the document manager for commercetools.
 * @author lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODM
 */
interface DocumentManagerInterface extends ObjectManager, LoggerAwareInterface
{
    /**
     * Key to request the request-class for creating.
     * @var string
     */
    const REQUEST_TYPE_CREATE = 'Create';

    /**
     * Key to request the request-class for deleting by container and key.
     * @var string
     */
    const REQUEST_TYPE_DELETE_BY_CONTAINER_AND_KEY = 'DeleteByContainerAndKey';

    /**
     * Key to request the request-class for deleting by id.
     * @var string
     */
    const REQUEST_TYPE_DELETE_BY_ID = 'DeleteById';

    /**
     * Key to request the request-class for deleting by key.
     * @var string
     */
    const REQUEST_TYPE_DELETE_BY_KEY = 'DeleteByKey';

    /**
     * Key to request the request-class for finding by container and key.
     * @var string
     */
    const REQUEST_TYPE_FIND_BY_CONTAINER_AND_KEY = 'FindByContainerAndKey';
    /**
     * Key to request the request-class for finding by customer id.
     * @var string
     */
    const REQUEST_TYPE_FIND_BY_CUSTOMER_ID = 'FindByCustomerId';
    /**
     * Key to request the request-class for finding by id.
     * @var string
     */
    const REQUEST_TYPE_FIND_BY_ID = 'FindById';
    /**
     * Key to request the request-class for finding by key.
     * @var string
     */
    const REQUEST_TYPE_FIND_BY_KEY = 'FindByKey';
    /**
     * Key to request the request-class for simple querying.
     * @var string
     */
    const REQUEST_TYPE_QUERY = 'Query';

    /**
     * Key to request the request-class for updating by id.
     * @var string
     */
    const REQUEST_TYPE_UPDATE_BY_ID = 'UpdateById';

    /**
     * Key to request the request-class for updating by key.
     * @var string
     */
    const REQUEST_TYPE_UPDATE_BY_KEY = 'UpdateByKey';

    /**
     * Returns a request class for fetching/updating/deleting documents using the request map of the given class name.
     * @param string $className
     * @param string $requestType
     * @param array $args The optional arguments.
     * @return AbstractCreateRequest|AbstractUpdateRequest|AbstractDeleteRequest|AbstractApiRequest
     * @throws \InvalidArgumentException
     */
    public function createRequest(string $className, string $requestType = self::REQUEST_TYPE_QUERY, ...$args);

    /**
     * Detaches the given object after flush.
     * @param object $object
     * @return void
     */
    public function detachDeferred($object);

    /**
     * Returns the used commercetools client.
     *
     * @return Client
     */
    public function getClient(): Client;

    /**
     * Returns the full qualified class name for the given request type.
     *
     * @param string $className The class name for which the request is fetched.
     * @param string $requestType The type of the request or the request class name it self.
     * @return string
     */
    public function getRequestClass(string $className, string $requestType): string;

    /**
     * Returns the common query helper from commercetools.
     * @return QueryHelper
     */
    public function getQueryHelper(): QueryHelper;

    /**
     * Returns the unit of work for this manager.
     * @return UnitOfWorkInterface
     */
    public function getUnitOfWork(): UnitOfWorkInterface;

    /**
     * Refreshes the persistent state of an object from the database,
     * overriding any local changes that have not yet been persisted.
     *
     * @param object $object The object to refresh.
     * @param object $overwrite Commercetools returns a representation of the objectfor many update actions, so use
     * this respons directly.
     * @return void
     */
    public function refresh($object, $overwrite = null);
}
