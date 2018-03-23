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
     * @return Create
     */
    public function getData()
    {
        $result = new Create();

        /** @var BillOfLading $data */
        $data = $this->data;

        if($data->getError()) {
            $this->getRequest()->getClient()->setError($data->getError());
            return false;
        }

        $result->setServiceId($this->getRequest()->getServiceId());
        $result->setBolId($data->getId());
        $result->setBillOfLadingSource($data->getPdf());
        $result->setBillOfLadingType($result::PDF);
        $result->setTotal($data->getTotal());
        $result->setCurrency('BGN'); //@todo return price in BGN

        return $result;
    }

}