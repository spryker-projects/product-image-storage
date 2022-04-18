<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageAbstract\ProductImageAbstractStoragePublishListener}
 *   and {@link \Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener\ProductImageAbstract\ProductImageAbstractStorageUnpublishListener} instead.
 *
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductImageStorage\Communication\ProductImageStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductImageStorage\ProductImageStorageConfig getConfig()
 */
class ProductImageAbstractPublishStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $productAbstractIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventEntityTransfers);

        if ($eventName === ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_UNPUBLISH) {
            $this->getFacade()->unpublishProductAbstractImages($productAbstractIds);
        }

        if ($eventName === ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH) {
            $this->getFacade()->publishProductAbstractImages($productAbstractIds);
        }
    }
}
