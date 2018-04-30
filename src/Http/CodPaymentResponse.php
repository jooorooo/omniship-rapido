<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 17:22 ч.
 */

namespace Omniship\Rapido\Http;

use Omniship\Common\CodPayment;
use \Rapido\Response\CodPayment AS RapidoCODPayment;

class CodPaymentResponse extends AbstractResponse
{
    /**
     * The data contained in the response.
     *
     * @var RapidoCODPayment
     */
    protected $data;

    /**
     * @return CodPayment
     */
    public function getData()
    {
        if(!is_null($this->getCode()) || !$this->data) {
            return null;
        }

        $cod_payment = new CodPayment([
            'id' => $this->getRequest()->getBolId(),
            'date' => $this->data->getDate(),
            'price' => $this->data->getTotal()
        ]);
        return $cod_payment;
    }

}