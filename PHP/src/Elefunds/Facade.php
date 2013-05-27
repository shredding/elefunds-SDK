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

require_once dirname(__FILE__) . '/Model/Factory.php';


/**
 * Elefunds Facade with access to the entire API functionality.
 *
 * @package    elefunds API PHP Library
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Facade {

      /**
       * @var Elefunds_Configuration_ConfigurationInterface
       */
      protected $configuration;

     /**
      * The cached receivers response - during one process there should only be one call.
      *
      * @var array
      */
     protected $cachedReceivers = array();

      /**
       * Sets up the facade and initializes configuration (if set).
       *
       * @param Elefunds_Configuration_ConfigurationInterface $configuration
       */
      public function __construct(Elefunds_Configuration_ConfigurationInterface $configuration = NULL) {
          if ($configuration !== NULL) {
              $this->setConfiguration($configuration);
          }
      }

     /**
      * Returns a brand new donation.
      *
      * @return Elefunds_Model_DonationInterface
      */
      public function createDonation() {
          return Elefunds_Model_Factory::getDonation();
      }

     /**
      * Returns a brand new Receiver.
      *
      * @return Elefunds_Model_ReceiverInterface
      */
      public function createReceiver() {
          return Elefunds_Model_Factory::getReceiver();
      }

      /**
       * Sets the configuration.
       *
       * @param Elefunds_Configuration_ConfigurationInterface $configuration
       * @return Elefunds_Facade
       */
      public function setConfiguration(Elefunds_Configuration_ConfigurationInterface $configuration) {
          $this->configuration = $configuration;
          $this->configuration->setFacade($this);
          $this->configuration->init();
          return $this;
      }

      /**
       * Returns the configuration instance.
       *
       * @return Elefunds_Configuration_ConfigurationInterface
       */
      public function getConfiguration() {
          return $this->configuration;
      }

    /**
     * Returns the available receivers
     *
     * @throws Elefunds_Exception_ElefundsCommunicationException
     * @return array
     */
     public function getReceivers() {

            if (count($this->cachedReceivers) === 0) {
                $restUrl = $this->configuration->getApiUrl() . '/receivers/?clientId=' . $this->configuration->getClientId() . '&hashedKey=' . $this->configuration->getHashedKey();
                $rawJson = $this->configuration->getRestImplementation()->get($restUrl);

                $response = json_decode($rawJson, TRUE);
                $this->cachedReceivers = $response;
            }

            // Let's get the country specific receivers
            if (!isset($this->cachedReceivers['receivers'][$this->configuration->getCountrycode()])) {
                throw new Elefunds_Exception_ElefundsCommunicationException(
                    'Requested countrycode was not available. Available country codes are: ' . implode(', ', array_keys($this->cachedReceivers['receivers'])) . '.',
                    1347966301
                );
            }

            $receivers = array();

            foreach ($this->cachedReceivers['receivers'][$this->configuration->getCountrycode()] as $rec) {
                $receiver = $this->createReceiver();
                $receivers[] = $receiver->setId($rec['id'])
                                        ->setName($rec['name'])
                                        ->setDescription($rec['description'])
                                        ->setImages($rec['images'])
                                        ->setValidTime(new DateTime($this->cachedReceivers['meta']['valid']));
            }

            return $receivers;
      }

      /**
       * Adds a single Donation to the API.
       *
       * This is just a wrapper for the addDonations method.
       *
       * @param Elefunds_Model_DonationInterface
       * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
       * @return string Message returned from the API
       */
      public function addDonation(Elefunds_Model_DonationInterface $donation) {
          return $this->addDonations(array($donation));
      }

      /**
       * Cancels a single Donation at the API.
       *
       * This is just a wrapper for the cancelDonations method.
       *
       * @param int $donationId
       * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
       * @return string Message returned from the API
       */
      public function cancelDonation($donationId) {
          return $this->cancelDonations(array($donationId));
      }

    /**
     * Completes a single Donation in the API.
     *
     * This is just a wrapper for the completeDonations method.
     *
     * @param int $donationId
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string Message returned from the API
     */
    public function completeDonation($donationId) {
        return $this->completeDonations(array($donationId));
    }

      /**
       * Sends an array of donations to the API.
       *
       * @param array $donations
       * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
       * @return string Message returned from the API
       */
      public function addDonations(array $donations) {

          if (count($donations) > 0) {
              $restUrl = $this->configuration->getApiUrl() . '/donations/?clientId=' . $this->configuration->getClientId() . '&hashedKey=' . $this->configuration->getHashedKey();
              $donationsArray = array();

              foreach ($donations as $donation) {
                  $donationsArray[] = $this->mapDonationToArray($donation);
              }

              $body = json_encode($donationsArray);

              $response = json_decode($this->configuration->getRestImplementation()->post($restUrl, $body), TRUE);
              return $response['message'];
          } else {
              return 'No donations given.';
          }
      }

      /**
       * Cancels an array of donation from the API.
       *
       * @param array $donationIds with ids
       *
       * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
       * @return string Message returned from the API
       */
      public function cancelDonations(array $donationIds) {

          if (count($donationIds) > 0) {
              $donationIds = array_filter($donationIds, create_function('$donationIds', 'return (string)$donationIds;'));

              $donationIdsString = implode(',', $donationIds);

              $restUrl = $this->configuration->getApiUrl() . '/donations/' . $donationIdsString . '/?clientId=' . $this->configuration->getClientId() . '&hashedKey=' . $this->configuration->getHashedKey();

              $response = json_decode($this->configuration->getRestImplementation()->delete($restUrl), TRUE);
              return $response['message'];
          } else {
              return 'No donations given.';
          }
      }

    /**
     * Completes an array of Donations in the API.
     *
     * @param array $donationIds with ids
     *
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string Message returned from the API
     */
    public function completeDonations(array $donationIds) {

        if (count($donationIds) > 0) {
            $donationIds = array_filter($donationIds, create_function('$donationIds', 'return (string)$donationIds;'));

            $donationIdsString = implode(',', $donationIds);

            $restUrl = $this->configuration->getApiUrl() . '/donations/' . $donationIdsString . '/?clientId=' . $this->configuration->getClientId() . '&hashedKey=' . $this->configuration->getHashedKey();

            $response = json_decode($this->configuration->getRestImplementation()->put($restUrl), TRUE);

            return $response['message'];
        } else {
            return 'No donations given.';
        }
    }

    /**
     * Renders the template.
     *
     * @param string $templateName
     * @param bool $givenTemplateNameIsAbsolutePath
     * @throws Elefunds_Exception_ElefundsException
     * @return string The rendered HTML Snippet
     */
      public function renderTemplate($templateName = 'View', $givenTemplateNameIsAbsolutePath = FALSE) {

          $view = $this->getConfiguration()->getView();

          if ($view === NULL) {
              throw new Elefunds_Exception_ElefundsException('There is no template set in your configuration file. Please refer to the documentation or use one of the sample templates.', 1348051662593);
          }

          return $view->render($templateName, $givenTemplateNameIsAbsolutePath);
      }

      /**
       * Returns the CSS Files required by the template.
       *
       * @throws Elefunds_Exception_ElefundsException if no template is configured
       * @return array with css files (path relative to this library)
       */
      public function getTemplateCssFiles() {
           $view = $this->getConfiguration()->getView();
            if ($view === NULL) {
                 throw new Elefunds_Exception_ElefundsException('There is no template set in your configuration file. Please refer to the documentation or use one of the sample templates.', 1348051662593);
            } else {
                return $view->getCssFiles();
            }
      }

      /**
       * Returns the Javascript Files required by the template.
       *
       * @throws Elefunds_Exception_ElefundsException if no template is configured
       * @return array with javascript files (path relative to this library)
       */
      public function getTemplateJavascriptFiles() {
           $view = $this->getConfiguration()->getView();
            if ($view === NULL) {
                 throw new Elefunds_Exception_ElefundsException('There is no template set in your configuration file. Please refer to the documentation or use one of the sample templates.', 1348051662593);
            } else {
                return $view->getJavascriptFiles();
            }
      }

      /**
       * Maps a Elefunds_Model_DonationInterface to a JSON ready array.
       *
       * @param Elefunds_Model_DonationInterface
       * @throws Elefunds_Exception_ElefundsException if not all information are given that are needed for the API
       * @return array
       */
      protected function mapDonationToArray(Elefunds_Model_DonationInterface $donation) {

          if ($donation->getForeignId() === NULL || $donation->getTime() === NULL || $donation->getAmount() === NULL || $donation->getReceiverIds() === NULL || $donation->getAvailableReceiverIds() === NULL) {
              throw new Elefunds_Exception_ElefundsException('Given donation does not contain all information needed to be send to the API.', 1347975987321);
          }

          $donationAsArray = $donation->toArray();

          if (isset($donationAsArray['donator']) && !isset($donationAsArray['donator']['countryCode'])) {
              $donationAsArray['donator']['countryCode'] = $this->getConfiguration()->getCountrycode();
          }

          return $donationAsArray;
      }

}
