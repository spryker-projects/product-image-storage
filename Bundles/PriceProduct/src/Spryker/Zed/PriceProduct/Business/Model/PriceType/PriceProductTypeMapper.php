<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\PriceType;

use Generated\Shared\Transfer\PriceTypeTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceType;

class PriceProductTypeMapper implements ProductPriceTypeMapperInterface
{
    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceType $priceTypeEntity
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer
     */
    public function mapFromEntity(SpyPriceType $priceTypeEntity)
    {
        $priceTypeTransfer = new PriceTypeTransfer();
        $priceTypeTransfer->fromArray($priceTypeEntity->toArray(), true);

        return $priceTypeTransfer;
    }
}
