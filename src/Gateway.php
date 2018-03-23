<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 11.5.2017 г.
 * Time: 17:18 ч.
 */

namespace Omniship\Rapido;

use Carbon\Carbon;
use Omniship\Common\Address;
use Omniship\Rapido\Http\CancelBillOfLadingRequest;
use Omniship\Rapido\Http\CodPaymentRequest;
use Omniship\Rapido\Http\CodPaymentsRequest;
use Omniship\Rapido\Http\CreateBillOfLadingRequest;
use Omniship\Rapido\Http\GetPdfRequest;
use Omniship\Rapido\Http\RequestCourierRequest;
use Omniship\Rapido\Http\ServicesRequest;
use Omniship\Rapido\Http\ShippingQuoteRequest;
use Omniship\Rapido\Http\TrackingParcelRequest;
use Omniship\Common\AbstractGateway;
use Omniship\Rapido\Http\TrackingParcelsRequest;
use Omniship\Rapido\Http\ValidateAddressRequest;
use Omniship\Rapido\Http\ValidateCredentialsRequest;
use Omniship\Rapido\Http\ValidatePostCodeRequest;

class Gateway extends AbstractGateway
{

    private $name = 'Rapido';

    private $client;

    const TRACKING_URL = 'http://www.rapido.bg/information/tracking-tovaritelnitsa?tnomer=%s';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
        );
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * @param array $parameters
     * @return ServicesRequest
     */
    public function getServices(array $parameters = [])
    {
        return $this->createRequest(ServicesRequest::class, $this->getParameters() + $parameters);
    }

    /**
     * @param array|ShippingQuoteRequest $parameters
     * @return ShippingQuoteRequest
     */
    public function getQuotes($parameters = [])
    {
        if ($parameters instanceof ShippingQuoteRequest) {
            return $parameters;
        }
        if (!is_array($parameters)) {
            $parameters = [];
        }
        return $this->createRequest(ShippingQuoteRequest::class, $this->getParameters() + $parameters);
    }

//    /**
//     * @param string $bol_id
//     * @return TrackingParcelRequest
//     */
//    public function trackingParcel($bol_id)
//    {
//        return $this->createRequest(TrackingParcelRequest::class, $this->setBolId($bol_id)->getParameters());
//    }
//
//    /**
//     * @param array $bol_ids
//     * @return TrackingParcelRequest
//     */
//    public function trackingParcels(array $bol_ids = [])
//    {
//        return $this->createRequest(TrackingParcelsRequest::class, $this->setBolId($bol_ids)->getParameters());
//    }

    /**
     * @param array|CreateBillOfLadingRequest $parameters
     * @return CreateBillOfLadingRequest
     */
    public function createBillOfLading($parameters = [])
    {
        if ($parameters instanceof CreateBillOfLadingRequest) {
            return $parameters;
        }
        if (!is_array($parameters)) {
            $parameters = [];
        }
        return $this->createRequest(CreateBillOfLadingRequest::class, $this->getParameters() + $parameters);
    }

    /**
     * @param $bol_id
     * @param null $cancelComment
     * @return CancelBillOfLadingRequest
     */
    public function cancelBillOfLading($bol_id, $cancelComment = null)
    {
        $this->setBolId($bol_id)->setCancelComment($cancelComment);
        return $this->createRequest(CancelBillOfLadingRequest::class, $this->getParameters());
    }
//
//    /**
//     * @param $bol_id
//     * @param null|Carbon $date_start
//     * @param null|Carbon $date_end
//     * @return RequestCourierRequest
//     */
////    public function requestCourier($bol_id, Carbon $date_start = null, Carbon $date_end = null)
////    {
////        return $this->createRequest(RequestCourierRequest::class, $this->setBolId(array_map('floatval', (array)$bol_id))->setStartDate($date_start)->setEndDate($date_end)->getParameters());
////    }
//
//    /**
//     * @param $bol_id
//     * @return CodPaymentRequest
//     */
//    public function codPayment($bol_id)
//    {
//        return $this->createRequest(CodPaymentRequest::class, $this->setBolId($bol_id)->getParameters());
//    }
//
//    /**
//     * @param array $bol_ids
//     * @return CodPaymentRequest
//     */
//    public function codPayments(array $bol_ids)
//    {
//        return $this->createRequest(CodPaymentsRequest::class, $this->setBolId($bol_ids)->getParameters());
//    }
//
//    /**
//     * @param $bol_id
//     * @return GetPdfRequest
//     */
//    public function getPdf($bol_id)
//    {
//        return $this->createRequest(GetPdfRequest::class, $this->setBolId($bol_id)->getParameters());
//    }


    /**
     * @param array $parameters
     * @param null|bool $test_mode
     *      if set null get mode from currently instance
     * @return ValidateCredentialsRequest
     */
    public function validateCredentials(array $parameters = [], $test_mode = null)
    {
        $instance = new Gateway();
        $instance->initialize($parameters);
        $instance->setTestMode(is_null($test_mode) ? $this->getTestMode() : (bool)$test_mode);
        return $instance->createRequest(ValidateCredentialsRequest::class, $instance->getParameters());
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Client($this->getUsername(), $this->getPassword(), $this->getTestMode());
        }
        return $this->client;
    }

    /**
     * @param $parcel_id
     * @return string
     */
    public function trackingUrl($parcel_id)
    {
        return sprintf(static::TRACKING_URL, $parcel_id);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsCashOnDelivery()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsInsurance()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supportPriorityDay()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supportPriorityTime()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supportMoneyTransfer()
    {
        return false;
    }
}