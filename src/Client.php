<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 16.5.2017 г.
 * Time: 15:12 ч.
 *
 * http://www.rapido.bg/soap_help/
 */

namespace Omniship\Rapido;

use Omniship\Common\CodPayment;
use Omniship\Common\RequestCourier;
use Rapido\EPSFacade;
use Rapido\Exception;
use Rapido\Response\Service;
use Rapido\Response\Courier;
use Rapido\Response\Quote;
use Rapido\Response\Country;
use Rapido\Response\City;
use Rapido\Response\Office;
use Rapido\Response\Street;
use Rapido\Response\MyObject;
use Rapido\Response\Pdf;
use Rapido\Response\Tracking;

class Client
{

    /**
     * @var EPSFacade
     */
    protected $ePSFacade;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var bool
     */
    protected $test_mode;
    /**
     * @var string
     */
    protected $error;
    /**
     * @var Service[]
     */
    protected $services;
    /**
     * @var Service[][]
     */
    protected $sub_services = [];

    /**
     * @var null|array
     */
    protected $connection_options;

    const SERVER_ADDRESS_DEMO = 'https://www.rapido.bg/testsystem/schema.wsdl';

    const SERVER_ADDRESS_PROD = 'https://www.rapido.bg/rsystem2/schema.wsdl';

    const VAT_PERCENTAGE = 20;

    const BULGARIA = 100;

    public function __construct($username, $password, $test_mode, array $connection_options = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->test_mode = $test_mode;
        $this->connection_options = $connection_options;
        $this->initialize();
    }

    /**
     * @return bool
     */
    public function initialize()
    {

        try {
            $soap_options = array(
                'wsdl_cache_wsdl' => WSDL_CACHE_NONE,
                'wsdl_trace' => 1
            );
            $soap_server_address = $this->test_mode ? static::SERVER_ADDRESS_DEMO : static::SERVER_ADDRESS_PROD;
            $connection_timeout = 0;
            $retries = 1;
            $read_timeout = null;
            if(is_array($this->connection_options)) {
                extract($this->connection_options);
            }

            $this->ePSFacade = new EPSFacade($soap_server_address, $this->username, $this->password, $soap_options, $connection_timeout, $retries, $read_timeout);
            return true;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }


    }

    /**
     * @return bool|Service[]
     */
    public function getServices()
    {
        if (!is_null($this->services)) {
            return $this->services;
        }

        try {
            return $this->services = $this->getEPSFacade()->getServices();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param integer $serviceId
     * @return bool|Service[]
     */
    public function getSubServices($serviceId)
    {
        if (!empty($this->sub_services[$serviceId])) {
            return $this->sub_services[$serviceId];
        }

        try {
            return $this->sub_services[$serviceId] = $this->getEPSFacade()->getSubServices($serviceId);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool|Courier[]
     */
    public function getCouriers()
    {
        try {
            return $this->getEPSFacade()->getCouriers();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool|Country[]
     */
    public function getCountries()
    {
        try {
            return $this->getEPSFacade()->getCountries();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param $country_id
     * @param null $start
     * @param null $count
     * @return bool|City[]
     */
    public function getCities($country_id, $start = null, $count = null)
    {
        try {
            return $this->getEPSFacade()->getCities($country_id, $start, $count);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param $name
     * @param $country_id
     * @return bool|City[]
     */
    public function findCities($name, $country_id = 100)
    {
        try {
            return $this->getEPSFacade()->findCities($name, $country_id);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param $city_id
     * @param null $start
     * @param null $count
     * @return bool|Street[]
     */
    public function getStreets($city_id, $start = null, $count = null)
    {
        try {
            return $this->getEPSFacade()->getStreets($city_id, $start, $count);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param $city_id
     * @return bool|Office[]
     */
    public function getOffices($city_id)
    {
        try {
            return $this->getEPSFacade()->getOffices($city_id);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param $city_id
     * @return bool|integer
     */
    public function checkCityFixChas($city_id)
    {
        try {
            return $this->getEPSFacade()->checkCityFixChas($city_id);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param $city_id
     * @return bool|integer
     */
    public function checkSiteId($city_id)
    {
        try {
            return $this->getEPSFacade()->checkSiteId($city_id);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool|MyObject[]
     */
    public function getMyObjects()
    {
        try {
            return $this->getEPSFacade()->getMyObjects();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool|MyObject[]
     */
    public function getInvoiceType()
    {
        try {
            return $this->getEPSFacade()->getMyObjects();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function validateCredentials($username, $password)
    {
        $instance = new static($username, $password, $this->getTestMode());
        if(is_array($instance->getCountries())) {
            return true;
        } else {
            if(strpos($instance->getError(), 'Error login!') !== 0) {
                $this->error = $instance->getError();
            }
            return false;
        }
    }

    /**
     * @param $object_id
     * @return bool|MyObject
     */
    public function getMyObjectInfo($object_id)
    {
        try {
            return $this->getEPSFacade()->getMyObjectInfo($object_id);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param $bol_id
     * @return bool
     */
    public function cancelBol($bol_id)
    {
        try {
            return $this->getEPSFacade()->cancelOrder($bol_id);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param array $parameters
     * @return Quote[]
     */
    public function calculate($parameters)
    {
        if(is_string($parameters)) {
            return $parameters;
        }
        $service_id = !empty($parameters['service']) ? $parameters['service'] : 0;
        $sub_services = !empty($parameters['subservice']) && is_array($parameters['subservice']) ? $parameters['subservice'] : [];

        if(!is_array($services_get = $this->getSubServices($service_id))) {
            return $this->getError();
        }

        $services = [];
        foreach ($services_get AS $s) {
            $services[$s->getTypeId()] = $s->getName();
        }

        $quotes = [];
        foreach ($sub_services AS $sub) {
            try {
                $parameters['subservice'] = $sub;
                $quotes[] = $this->getEPSFacade()->calculate($parameters, $services);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        return $quotes;
    }

    /**
     * @param array $parameters
     * @return bool|Quote
     */
    public function createBillOfLading(array $parameters)
    {
        try {
            return $this->getEPSFacade()->createOrder($parameters);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param $bol_id
     * @param int $type
     * @return bool|Pdf
     */
    public function createPDF($bol_id, $type = 1)
    {
        try {
            return $this->getEPSFacade()->createPdf($bol_id, $type);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * Get status for bill of landing
     * @param $bol_id
     * @return bool|null|Tracking
     */
    public function trackParcel($bol_id)
    {
        try {
            return $this->getEPSFacade()->trackOrder($bol_id);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * Get status for bill of landing
     * @param $bol_id
     * @return bool|Tracking[]
     */
    public function trackParcelMultiple($bol_id)
    {
        try {
            return $this->getEPSFacade()->trackOrders($bol_id);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param int $count
     * @param float $weight
     * @param int $sendoffice
     * @param string $readiness
     * @return false|RequestCourier
     */
    public function requestCourier($count, $weight, $sendoffice = 0, $readiness = '')
    {
        try {
            return $this->getEPSFacade()->requestCourier($count, $weight, $sendoffice, $readiness);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * Documentation : Връща информация дали товарителница е изплатена
     * @param $bol_id
     * @return boolean|CodPayment
     */
    public function codPayment($bol_id)
    {
        try {
            return $this->getEPSFacade()->codPayment($bol_id);
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * @param boolean $test_mode
     * @return Client
     */
    public function setTestMode($test_mode)
    {
        $this->test_mode = $test_mode;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getTestMode()
    {
        return $this->test_mode;
    }

    /**
     * @param string $username
     * @return Client
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $password
     * @return Client
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return EPSFacade
     */
    public function getEPSFacade()
    {
        return $this->ePSFacade;
    }
}