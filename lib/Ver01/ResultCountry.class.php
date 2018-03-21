<?php

/**
 * Instances of this class are returned in response to country search methods
 * @since 2.5.0
 */
class ResultCountry extends SpeedyResultFormat {

	/**
	 * Active country currency code
	 * @var string
	 */
    protected $_activeCurrencyCode;
    
    /**
     * Country id
     * @var integer Signed 64-bit
     */
    protected $_countryId;
    
    /**
     * Country ISO alpha 2 code
     * @var string
     */
    protected $_isoAlpha2;
    
    /**
     * Country ISO alpha 3 code
     * @var string
     */
    protected $_isoAlpha3;
    
    /**
     * Country name
     * @var string
     */
    protected $_name;
    
    /**
     * Country post code format
     * @var string
     */
    protected $_postCodeFormat;
    
    /**
     * Require post code for country addresses flag
     * @var boolean
     */
    protected $_requirePostCode;
    
    /**
     * Require state for country addresses flag
     * @var boolean
     */
    protected $_requireState;
    
    /**
     * Code for site nomenclature support
     * 0 - No site nomenclature
     * 1 - Site nomenclature is supported for this site
     * @var integer signed 32-bit 
     */
    protected $_siteNomen;
    
    /**
     * Constructs new instance of ResultCountry
     * @param stdClass $stdClassResultCountry
     */
    function __construct($stdClassResultCountry) {
        $this->_activeCurrencyCode = isset($stdClassResultCountry->activeCurrencyCode) ? $stdClassResultCountry->activeCurrencyCode : null;
        $this->_countryId          = isset($stdClassResultCountry->countryId)          ? $stdClassResultCountry->countryId          : null;
        $this->_isoAlpha2          = isset($stdClassResultCountry->isoAlpha2)          ? $stdClassResultCountry->isoAlpha2          : null;
        $this->_isoAlpha3          = isset($stdClassResultCountry->isoAlpha3)          ? $stdClassResultCountry->isoAlpha3          : null;
        $this->_name               = isset($stdClassResultCountry->name)               ? $stdClassResultCountry->name               : null;
        $this->_postCodeFormat     = isset($stdClassResultCountry->postCodeFormat)     ? $stdClassResultCountry->postCodeFormat     : null;
        $this->_requirePostCode    = isset($stdClassResultCountry->requirePostCode)    ? $stdClassResultCountry->requirePostCode    : null;
        $this->_requireState       = isset($stdClassResultCountry->requireState)       ? $stdClassResultCountry->requireState       : null;
        $this->_siteNomen          = isset($stdClassResultCountry->siteNomen)          ? $stdClassResultCountry->siteNomen          : null;
    }
    

    /**
     * Gets country active currency code
     * @return string Active currency code for the country
     */
    public function getActiveCurrencyCode() {
        return $this->_activeCurrencyCode;
    }

    /**
     * Gets the country id
     * @return integer signed 32-bit Country id
     */
    public function getCountryId() {
        return $this->_countryId;
    }

    /**
     * Gets the country ISO alpha2 code
     * @return string Country ISO alpha2 code
     */
    public function getIsoAlpha2() {
        return $this->_isoAlpha2;
    }

    /**
     * Gets the country ISO alpha3 code
     * @return string Country ISO alpha3 code
     */
    public function getIsoAlpha3() {
        return $this->_isoAlpha3;
    }

    /**
     * Gets the country name
     * @return string Country name
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Gets the country post code format
     * @return string Country post code format
     */
    public function getPostCodeFormat() {
        return $this->_postCodeFormat;
    }

    /**
     * Gets the require post code flag for the country
     * @return boolean Country require post code flag value 
     */
    public function isRequirePostCode() {
        return $this->_requirePostCode;
    }

    /**
     * Gets the require state flag value for the country
     * @return boolean Country require state flag value
     */
    public function isRequireState() {
        return $this->_requireState;
    }

    /**
     * Gets the code for site nomenclature support for the country
     * @return integer signed 32-bit Code for site nomenclature support for the country
     *   0 - No site nomenclature
     *   1 - Has full site nomenclature
     */
    public function getSiteNomen() {
        return $this->_siteNomen;
    }

}
?>