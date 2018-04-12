<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 16:55 ч.
 */

namespace Omniship\Rapido\Http;

use Omniship\Consts;
use Omniship\Rapido\Client;
use Omniship\Rapido\Helper\Convert;
use Rapido\Response\Service;
use Omniship\Helper\Arr;

class ShippingQuoteRequest extends AbstractRequest
{

    /**
     * @return array
     */
    public function getData()
    {

        //3, 7, 9 services is without sub services
        $data = [];
        if(!empty($service_id = $this->getServiceId())) {
            if(strpos($service_id, '_') !== false) {
                list($service_id, $sub_service_id) = explode('_', $service_id);
                $data['service'] = $service_id;
                $data['subservice'] = [$sub_service_id];
            } else {
                $data['service'] = $service_id;
            }
        } elseif(!empty($receiver_address = $this->getReceiverAddress())) {
            if($receiver_address->getCountry() && $receiver_address->getCountry()->getId() != Client::BULGARIA) {
                $data['service'] = 3;
            } elseif(!empty($sender_city_id = $this->getOtherParameters('sender_city_id')) && $receiver_address->getCity()) {
                $data['service'] = $sender_city_id == $receiver_address->getCity()->getId() ? 1 : 2;
            }
        }

        if(empty($data['subservice']) && !empty($data['service'])) {
            if(in_array($data['service'], [3, 7, 9])) {
                $data['subservice'] = [0];
            } elseif(!empty($allowed_services = $this->getAllowedServices())) {
                $data['subservice'] = array_map(function($sub_service) {
                    return Arr::last(explode('_', $sub_service));
                }, array_filter($allowed_services, function($id) use($data) {
                    return strpos($id, $data['service'] . '_') === 0;
                }));
            }
        }

        if(empty($data['subservice']) && !empty($data['service']) && !in_array($data['service'], [3,7,9])) {
            if(!is_array($sub_services = $this->getClient()->getSubServices($data['service']))) {
                return $this->getClient()->getError();
            }
            $data['subservice'] = array_map(function(Service $sub_service) {
                return $sub_service->getTypeId();
            }, $sub_services);
        }

        if(!empty($receiver_address = $this->getReceiverAddress()) && !empty($country = $receiver_address->getCountry()) && $country->getId() != Client::BULGARIA) {
            $data['country_b'] = $country->getId();
        }

        $data['fix_chas'] = (int)$this->getOtherParameters('fixed_time_delivery');
        $data['return_receipt'] = (int)$this->getBackReceipt();
        $data['return_doc'] = (int)$this->getBackDocuments();
        if(!empty($cash_on_delivery = $this->getCashOnDeliveryAmount()) && $cash_on_delivery > 0) {
            $data['nal_platej'] = (float)$cash_on_delivery;
        }
        if(!empty($insurance = $this->getInsuranceAmount()) && $insurance > 0) {
            $data['zastrahovka'] = (float)$insurance;
        }

        $convert = new Convert();
        $data['teglo'] = (float)$convert->convertWeightUnit($this->getWeight(), $this->getWeightUnit());

        if($this->getPayer() != Consts::PAYER_RECEIVER) {
            $data['ZASMETKA'] = 0;
        } elseif($this->getPayer() == Consts::PAYER_RECEIVER) {
            $data['ZASMETKA'] = 1;
        }

        if($this->getOtherParameters('price_list') == Consts::PAYER_SENDER) {
            $data['CENOVA_LISTA'] = 1;
        } elseif($this->getOtherParameters('price_list') == Consts::PAYER_RECEIVER) {
            $data['CENOVA_LISTA'] = 2;
        } else {
            $data['CENOVA_LISTA'] = 0;
        }

        return $data;
    }

    public function sendData($data)
    {
        $response = $this->getClient()->calculate($data);
        return $this->createResponse(!$response && $this->getClient()->getError() ? $this->getClient()->getError() : $response);
    }

    /**
     * @param $data
     * @return ShippingQuoteResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new ShippingQuoteResponse($this, $data);
    }

}