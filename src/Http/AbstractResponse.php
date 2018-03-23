<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 16:55 ч.
 */

namespace Omniship\Rapido\Http;

use Omniship\Message\AbstractResponse AS BaseAbstractResponse;

abstract class AbstractResponse extends BaseAbstractResponse
{
    /**
     * Get the initiating request object.
     *
     * @return AbstractRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        if(is_string($this->data)) {
            return $this->data;
        } elseif(is_object($this->data) && method_exists($this->data, 'getError') && $this->data->getError()) {
            return $this->data->getError();
        }
        return null;
    }

    /**
     * @return null|string
     */
    public function getCode()
    {
        if(is_string($this->data)) {
            if(preg_match('~\(([a-z0-9]{2,})\)~i', $this->data, $match)) {
                return $match[1];
            }
            return md5($this->data);
        } elseif(is_object($this->data) && method_exists($this->data, 'getError') && $this->data->getError()) {
            return md5($this->data->getError());
        }
        return null;
    }

}