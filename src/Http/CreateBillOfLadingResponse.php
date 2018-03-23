<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 17:22 ч.
 */

namespace Omniship\Rapido\Http;

use Omniship\Common\Bill\Create;

class CreateBillOfLadingResponse extends AbstractResponse
{

    /**
     * @return Create
     */
    public function getData()
    {
        $result = new Create();
        $result->setServiceId($this->getRequest()->getServiceId());
        $result->setBolId($this->data->getId());
        $result->setBillOfLadingSource($this->data->getPdf());
        $result->setBillOfLadingType($result::PDF);
        $result->setTotal($this->data->getTotal());
        $result->setCurrency('BGN'); //@todo return price in BGN
        $result->setError($this->data->getError());

        return $result;
    }

}