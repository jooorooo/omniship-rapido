<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 16:55 ч.
 */

namespace Omniship\Rapido\Http;

use Omniship\Rapido\Helper\Convert;

class RequestCourierRequest extends AbstractRequest
{

    /**
     * @return array
     */
    public function getData() {
        $convert = new Convert();
        return [
            'bol_id' => $this->getBolId(),
            'sender_office_id' => (int)$this->getOtherParameters('sender_office_id'),
            'readiness' => $this->getOtherParameters('readiness'),
            'weight' => $convert->convertWeightUnit($this->getWeight(), $this->getWeightUnit()),
        ];
    }

    /**
     * @param mixed $data
     * @return RequestCourierResponse
     */
    public function sendData($data) {
        $response = $data ? $this->getClient()->requestCourier(count($data['bol_id']), $data['weight'], $data['sender_office_id'], $data['readiness']) : null;
        return $this->createResponse(!$response && $this->getClient()->getError() ? $this->getClient()->getError() : $response);
    }

    /**
     * @param $data
     * @return RequestCourierResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new RequestCourierResponse($this, $data);
    }

}