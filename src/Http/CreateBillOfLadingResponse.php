<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 17:22 ч.
 */

namespace Omniship\Rapido\Http;

use Omniship\Common\Bill\Create;
use Rapido\Response\BillOfLading;

class CreateBillOfLadingResponse extends AbstractResponse
{
    /**
     * The data contained in the response.
     *
     * @var BillOfLading
     */
    protected $data;

    /**
     * @return Create
     */
    public function getData()
    {
        $result = new Create();
        $result->setServiceId($this->getRequest()->getServiceId());

        if(!($this->data instanceof BillOfLading)) {
            $result->setCurrency('BGN'); //@todo return price in BGN
            $result->setError(is_string($this->data) ? $this->data : json_encode($this->data));
            return $result;
        }

        $result->setBolId($this->data->getId());
        $result->setBillOfLadingSource($this->data->getPdf());
        $result->setBillOfLadingType($result::PDF);
        $result->setTotal($this->data->getTotal());
        $result->setCurrency('BGN'); //@todo return price in BGN
        $result->setError($this->data->getError());

        return $result;
    }

}