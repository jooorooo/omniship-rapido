<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 17:22 ч.
 */

namespace Omniship\Rapido\Http;

use Omniship\Common\RequestCourier;

class RequestCourierResponse extends AbstractResponse
{
    /**
     * The data contained in the response.
     *
     * @var \Rapido\Response\RequestCourier[]
     */
    protected $data;

    /**
     * @return RequestCourier[]
     */
    public function getData()
    {
        $results = [];
        /** @var \Omniship\Common\RequestCourier $data */
        $data = $this->data;
        foreach((array)$this->getRequest()->getBolId() AS $bol_id) {
            $results[] = new RequestCourier([
                'bol_id' => $bol_id,
                'pickup_date' => $this->getRequest()->getEndDate(),
                'error' => $data->getError(),
                'error_code' => $data->getError() ? md5($data->getError()) : $data->getError(),
            ]);
        }

        return $results;
    }

}