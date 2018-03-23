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

class CreateBillOfLadingRequest extends AbstractRequest
{

    /**
     * @return array
     */
    public function getData()
    {

        //3, 7, 9 services is without sub services
        $data = [];
        $service = $this->getServiceId();
        if(strpos($service, '_') !== false) {
            list($service_id, $sub_service_id) = explode('_', $service);
            $data['service'] = $service_id;
            $data['subservice'] = [$sub_service_id];
        } else {
            $data['service'] = $service;
            $data['subservice'] = [0];
        }

        if(!empty($cash_on_delivery = $this->getCashOnDeliveryAmount()) && $cash_on_delivery > 0) {
            $data['nal_platej'] = (float)$cash_on_delivery;
        }
        if(!empty($insurance = $this->getInsuranceAmount()) && $insurance > 0) {
            $data['zastrahovka'] = (float)$insurance;
        }

        $data['CONTENT'] = mb_substr($this->getContent(), 0, 200, 'utf-8');

        $data['CHUPLIVO'] = (int)$this->getOtherParameters('fragile');

        $convert = new Convert();
        $data['teglo'] = (float)$convert->convertWeightUnit($this->getWeight(), $this->getWeightUnit());

        if(!empty($receiver_address = $this->getReceiverAddress())) {
            $data['RECEIVER'] = $receiver_address->getCompanyName() ? : $receiver_address->getFullName();
            if (!empty($country = $receiver_address->getCountry())) {
                $data['COUNTRY_B'] = $country->getId();
            }
            if(!empty($city = $receiver_address->getCity())) {
                $data['CITY_B'] = $city->getName();
                $data['SITEID_B'] = $city->getId();
            }
            $data['PK_B'] = $receiver_address->getPostCode();
            if(!empty($street = $receiver_address->getStreet())) {
                $data['STREET_B'] = $street->getName();
                if (!empty($street_id = $street->getId())) {
                    $data['STREETB_ID'] = $street_id;
                }
            }
            if(!empty($street_number = $receiver_address->getStreetNumber())) {
                $data['STREET_NO_B'] = $street_number;
            }
            if(!empty($building = $receiver_address->getBuilding())) {
                $data['BLOCK_B'] = $building;
            }
            if(!empty($entrance = $receiver_address->getEntrance())) {
                $data['ENTRANCE_B'] = $entrance;
            }
            if(!empty($floor = $receiver_address->getFloor())) {
                $data['FLOOR_B'] = $floor;
            }
            if(!empty($apartment = $receiver_address->getApartment())) {
                $data['APARTMENT_B'] = $apartment;
            }
            if(count($lines = array_filter([$receiver_address->getAddress1(), $receiver_address->getAddress2(), $receiver_address->getAddress3()])) > 0) {
                $data['ADDITIONAL_INFO_B'] = implode(' ', $lines);
            }
            $data['PHONE_B'] = $receiver_address->getPhone();
            $data['CPERSON_B'] = $receiver_address->getFullName();

            if(!empty($office = $receiver_address->getOffice()) && $office->getId()) {
                $data['TAKEOFFICE'] = $office->getId();
            }
        }

        $data['fix_chas'] = (int)$this->getOtherParameters('fixed_time_delivery');
        $data['return_receipt'] = (int)$this->getBackReceipt();
        $data['return_doc'] = (int)$this->getBackDocuments();

        $data['PACK_COUNT'] = $this->getNumberOfPieces();
        $data['CLIENT_REF1'] = $this->getTransactionId();

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

        if ($this->getOptionBeforePayment() == Consts::OPTION_BEFORE_PAYMENT_TEST) {
            $data['CHECK_BEFORE_PAY'] = 1;
        }

        if($this->getOtherParameters('saturday_delivery')) {
            $data['SUBOTEN_RAZNOS'] = 1;
        }

        return $data;
    }

    /**
     * @param mixed $data
     * @return CreateBillOfLadingResponse
     */
    public function sendData($data)
    {
        $response = $data ? $this->getClient()->createBillOfLading($data) : null;
        return $this->createResponse(!$response && $this->getClient()->getError() ? $this->getClient()->getError() : $response);
    }

    /**
     * @param $data
     * @return CreateBillOfLadingResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new CreateBillOfLadingResponse($this, $data);
    }

}