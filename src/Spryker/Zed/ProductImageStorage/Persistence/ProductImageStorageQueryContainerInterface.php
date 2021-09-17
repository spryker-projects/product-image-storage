<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence;

use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductImageStorageQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return \Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorageQuery
     */
    public function queryProductAbstractImageStorageByIds(array $productAbstractIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productIds
     *
     * @return \Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorageQuery
     */
    public function queryProductConcreteImageStorageByIds(array $productIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery
     */
    public function queryProductAbstractLocalizedByIds(array $productAbstractIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery
     */
    public function queryProductLocalizedByIds(array $productIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productImageIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductAbstractIdsByProductImageIds(array $productImageIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productImageSetToProductImageIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductAbstractIdsByProductImageSetToProductImageIds(array $productImageSetToProductImageIds);

    /**
     * Specification:
     * - Returns a a query for the table `spy_product_image_set_to_product_image` joining `spy_product_image_set` filtered by primary ids.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param array<int> $productImageSetToProductImageIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductImageSetToProductImageByIds(array $productImageSetToProductImageIds): SpyProductImageSetToProductImageQuery;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productImageIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductIdsByProductImageIds(array $productImageIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productImageSetToProductImageIds
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function queryProductIdsByProductImageSetToProductImageIds(array $productImageSetToProductImageIds);
}
