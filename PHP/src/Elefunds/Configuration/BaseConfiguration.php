<?php

/**
 * elefunds API PHP Library
 *
 * Copyright (c) 2012, elefunds GmbH <hello@elefunds.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of the elefunds GmbH nor the names of its
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 */

require_once 'ConfigurationInterface.php';
require_once dirname(__FILE__) . '/../Exception/ElefundsException.php';


/**
 * Base Configuration for the elefunds API.
 *
 * @package    elefunds API PHP Library
 * @subpackage Configuration
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Configuration_BaseConfiguration implements Elefunds_Configuration_ConfigurationInterface {

    /**
     * @var array
     */
    protected $availableShareServices = array();

    /**
     * @var int
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * The calculated hashedKey that is a sha1 value of
     * the clientId concatenated with the api key.
     *
     * @var string
     */
    protected $hashedKey;

    /**
     * @var Elefunds_Communication_RestInterface
     */
    protected $rest;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * Class name of the donation implementation.
     *
     * @var string
     */
    protected $donationClassName;

    /**
     * Class name of the receiver implementation.
     *
     * @var string
     */
    protected $receiverClassName;

    /**
     * Two digit countrycode to use for calls.
     *
     * @var string
     */
    protected $countrycode = 'en';

    /**
     * Instance of the facade.
     *
     * @var Elefunds_Facade
     */
    protected $facade;

    /**
     * @var Elefunds_View_ViewInterface
     */
    protected $view;

    /**
     * Setup configuration of an elefunds API Plugin.
     *
     * This function gets called after forwarding the configuration to the facade.
     *
     * @return void
     */
    public function init() {}

    /**
     * An instance of the facade. This is set by the facade itself, so you can access API functionality
     * from within init()!
     *
     * @param Elefunds_Facade $facade
     * @return Elefunds_Configuration_ConfigurationInterface
     */
    public function setFacade(Elefunds_Facade $facade) {
        $this->facade = $facade;
        return $this;
    }

    /**
     * Sets the view for this configuration.
     *
     * @param Elefunds_View_ViewInterface $view
     * @return Elefunds_Configuration_ConfigurationInterface
     */
    public function setView(Elefunds_View_ViewInterface $view) {
        $this->view = $view;
        return $this;
    }

    /**
     * Returns the view that is configured for this configuration.
     *
     * @return Elefunds_View_ViewInterface
     */
    public function getView() {
        return $this->view;
    }

    /**
     * Sets the clientId.
     *
     * @param int $clientId
     * @return Elefunds_Configuration_ConfigurationInterface
     */
    public function setClientId($clientId) {
        $this->clientId = (int)$clientId;
        if ($this->apiKey !== NULL && $this->hashedKey === NULL) {
            $this->hashedKey = sha1($this->clientId . $this->apiKey);
        }

        return $this;
    }

    /**
     * Sets the apiKey.
     *
     * @param string $apiKey
     * @return Elefunds_Configuration_ConfigurationInterface
     */
    public function setApiKey($apiKey) {
        $this->apiKey = (string)$apiKey;
        if ($this->clientId !== NULL && $this->hashedKey === NULL) {
            $this->hashedKey = sha1($this->clientId . $this->apiKey);
        }

        return $this;
    }

    /**
     * Returns the ClientId.
     *
     * @return int
     */
    public function getClientId() {
        return $this->clientId;
    }

    /**
     * The API Url.
     *
     * Url is not validated here, as it's dependent on the RestInterface Implementation. For example
     * the curl implementation adds it's error message to the additionalInformation of the ElefundsException.
     *
     * @param string $url
     * @return Elefunds_Configuration_ConfigurationInterface
     */
    public function setApiUrl($url) {
        $this->apiUrl = rtrim((string)$url, '/');
        return $this;
    }

    /**
     * Returns the URL to the API without trailing slashes.
     *
     * @return string
     */
    public function getApiUrl() {
        return $this->apiUrl;
    }

    /**
     * Returns the hashed key
     *
     * @throws Elefunds_Exception_ElefundsException if hashedKey has not been calculated
     * @return string
     */
    public function getHashedKey() {
        if ($this->hashedKey === NULL) {
            if ($this->apiKey === NULL || $this->clientId === NULL) {
                throw new Elefunds_Exception_ElefundsException('HashedKey could not been calculated. Make sure that either clientId and apiKey are set.', 1347889008107);
            } else {
                $this->hashedKey = sha1($this->clientId . $this->apiKey);
            }
        }
        return $this->hashedKey;
    }

    /**
     * The rest implementation to be used to connect to the api.
     *
     * If not changed in the configuration, this will be curl.
     *
     * @param Elefunds_Communication_RestInterface $rest
     * @return Elefunds_Configuration_ConfigurationInterface
     */
    public function setRestImplementation(Elefunds_Communication_RestInterface $rest) {
        $this->rest = $rest;
        return $this;
    }

    /**
     * Returns the rest implementation to use, by default, it's curl.
     *
     * @return Elefunds_Communication_RestInterface
     */
    public function getRestImplementation() {
        return $this->rest;
    }

    /**
     * Sets the donation class name a fully qualified string.
     *
     * Attention: Since we do not use autoloading, you have to require_once the class before
     * setting it.
     *
     * @param string $donationClassName
     * @throws Elefunds_Exception_ElefundsException if given class does not exist
     * @return Elefunds_Configuration_ConfigurationInterface
     */
    public function setDonationClassName($donationClassName) {
        if (!class_exists($donationClassName)) {
            throw new Elefunds_Exception_ElefundsException('Class ' . $donationClassName . ' does not exist. Did you called required_once on the file that hosts this class?', 1347893442819);
        }
        $this->donationClassName = (string)$donationClassName;
        Elefunds_Model_Factory::setDonationImplementation($this->donationClassName);
        return $this;
    }

    /**
     * Returns the donation class name.
     *
     * @return string
     */
    public function getDonationClassName() {
        return $this->donationClassName;
    }

    /**
     * Sets the receiver class name a fully qualified string.
     *
     * Attention: Since we do not use auto-loading, you have to require_once the class before
     * setting it.
     *
     * @param string $receiverClassName
     * @throws Elefunds_Exception_ElefundsException if given class does not exist
     * @return Elefunds_Configuration_ConfigurationInterface
     */
    public function setReceiverClassName($receiverClassName) {
        if (!class_exists($receiverClassName)) {
            throw new Elefunds_Exception_ElefundsException('Class ' . $receiverClassName . ' does not exist. Did you called required_once on the file that hosts this class?', 1347893442820);
        }
        $this->receiverClassName = (string)$receiverClassName;
        Elefunds_Model_Factory::setReceiverImplementation($this->receiverClassName);
        return $this;
    }

    /**
     * Returns the receiver class name.
     *
     * @return string
     */
    public function getReceiverClassName() {
        return $this->receiverClassName;
    }

    /**
     * Sets the countrycode.
     *
     * @param string $countrycode two digit countrycode
     * @throws InvalidArgumentException if given string is not a countrycode
     * @return Elefunds_Configuration_ConfigurationInterface
     */
    public function setCountrycode($countrycode) {
        if (is_string($countrycode) && strlen($countrycode) === 2) {
            $this->countrycode = $countrycode;
        } else {
            throw new InvalidArgumentException('Given countrycode must be a two digit string.', 1347965897);
        }
        return $this;
    }

    /**
     * Returns the countrycode.
     *
     * @return string
     */
    public function getCountrycode() {
        return $this->countrycode;
    }

}