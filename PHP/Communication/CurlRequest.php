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

require_once 'RestInterface.php';

/**
 * Curl Request
 *
 * Connects to the elefunds API via curl.
 *
 * Type validation is performed, but valid semantic input is forwarded to the API or curl itself.
 *
 * @package    elefunds API PHP Library
 * @subpackage Communication
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Communication_CurlRequest implements Elefunds_Communication_RestInterface {

    /**
     * @var resource
     */
    protected $curl;

    /**
     * @var array
     */
    protected $curlOptions;

    /**
     * Initializes curl - if the extension is installed.
     *
     * @throws Elefunds_Exception_ElefundsException if curl is not installed
     */
    public function __construct() {
        if (!extension_loaded('curl')) {
            throw new Elefunds_Exception_ElefundsException(
                'You are using the curl request method without having curl installed on your server.
                 Your options are to either use another implementation of the RestInterface or to install curl.',
                 1347875278);
        }

        $this->curl = curl_init();
        $this->curlOptions = array();

    }

    /**
     * Performs a GET Request against a given URL.
     *
     * @param string $restUrl with fully qualified resource path
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string the server response as JSON
     */
    public function get($restUrl) {
       $this->curlOptions[CURLOPT_CUSTOMREQUEST] = 'GET';
       $this->curlOptions[CURLOPT_URL] = (string)$restUrl;

       return $this->performRequest();

    }

    /**
     * Performs a POST Request against a given URL.
     *
     * @param string $restUrl with fully qualified resource path
     * @param string $body the JSON body
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string the server response as JSON
     */
    public function post($restUrl, $body) {

        $this->curlOptions[CURLOPT_CUSTOMREQUEST] = 'POST';
        $this->curlOptions[CURLOPT_POSTFIELDS] = (string)$body;
        $this->curlOptions[CURLOPT_URL] = (string)$restUrl;
        $this->curlOptions[CURLOPT_HTTPHEADER] = array('Content-Type: application/json');

        return $this->performRequest();
    }

    /**
     * Performs a DELETE Request against a given URL.
     *
     * @param string $restUrl with fully qualified resource path
     * @param string $body the JSON body
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string the server response as JSON
     */
    public function put($restUrl, $body = '') {

        $this->curlOptions[CURLOPT_CUSTOMREQUEST] = 'PUT';
        $this->curlOptions[CURLOPT_URL] = (string)$restUrl;

        if (strlen($body) > 0) {
            $this->curlOptions[CURLOPT_POSTFIELDS] = (string)$body;
            $this->curlOptions[CURLOPT_HTTPHEADER] = array('Content-Type: application/json');
        }

        return $this->performRequest();
    }


    /**
     * Performs a DELETE Request against a given URL.
     *
     * @param string $restUrl with fully qualified resource path
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string the server response as JSON
     */
    public function delete($restUrl) {
        $this->curlOptions[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        $this->curlOptions[CURLOPT_URL] = (string)$restUrl;

        return $this->performRequest();
    }

    /**
     * Performs the actual curl request.
     *
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string the server response as JSON
     */
    protected function performRequest() {
         $this->curlOptions[CURLOPT_RETURNTRANSFER] = TRUE;
         $this->curlOptions[CURLOPT_USERAGENT] = 'elefunds-php-1.1';
         $this->curlOptions[CURLOPT_CAINFO] = dirname(__FILE__) . '/certificate/GandiProSSLCA.pem';

         curl_setopt_array($this->curl, $this->curlOptions);

         $serverResponse = curl_exec($this->curl);

         if ($serverResponse === FALSE) {

             throw new Elefunds_Exception_ElefundsCommunicationException(
                'Unable to connect to the elefunds API',
                1347878604,
                array(
                    'curlErrorCode'     => curl_errno($this->curl),
                    'curlErrorMessage'  => curl_error($this->curl)
                )
             );
         }

         $httpResponseCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

         if ($httpResponseCode !== 200) {
             throw new Elefunds_Exception_ElefundsCommunicationException(
                'An error occurred during the api call. Refer to additionalInformation of this exception for more details.',
                1347899756668,
                array(
                    'httpCode'          => $httpResponseCode,
                    'serverResponse'    => (string)$serverResponse
                )
            );
         }

         // Reset options
         $this->curlOptions = array();

         return $serverResponse;
    }

}