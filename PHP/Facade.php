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
class Library_Elefunds_Facade {
    
      /**
       * @var Library_Elefunds_Configuration_ConfigurationInterface
       */
      protected $configuration;

      /**
       * @param Library_Elefunds_Configuration_ConfigurationInterface $configuration
       */
      public function __construct(Library_Elefunds_Configuration_ConfigurationInterface $configuration = NULL) {
          if($configuration !== NULL) {
              $this->setConfiguration($configuration);
          }
      }

     /**
      * Returns a brand new donation.
      *
      * @return Library_Elefunds_Model_DonationInterface
      */
      public function createDonation() {
          return Library_Elefunds_Model_Factory::getDonation();
      }

     /**
      * Returns a brand new Receiver.
      *
      * @return Library_Elefunds_Model_ReceiverInterface
      */
      public function createReceiver() {
          return Library_Elefunds_Model_Factory::getReceiver();
      }
      
      /**
       * Sets the configuration.
       * 
       * @param Library_Elefunds_Configuration_ConfigurationInterface $configuration
       * @return Library_Elefunds_Facade
       */
      public function setConfiguration(Library_Elefunds_Configuration_ConfigurationInterface $configuration) {
          $this->configuration = $configuration;
          $this->configuration->setFacade($this);
          $this->configuration->init();          
          return $this;
      }
      
      /**
       * Returns the configuration instance.
       * 
       * @return Library_Elefunds_Configuration_ConfigurationInterface
       */
      public function getConfiguration() {
          return $this->configuration;
      }
      
      /**
       * Returns the available receivers
       */
      public function getReceivers() {
            $restUrl = $this->configuration->getApiUrl() . '/receivers/?clientId=' . $this->configuration->getClientId() . '&hashedKey=' . $this->configuration->getHashedKey();
            $rawJson = $this->configuration->getRestImplementation()->get($restUrl);
            
            $response = json_decode($rawJson, TRUE);

            // Let's get the country specific receivers
            if(!isset($response['receivers'][$this->configuration->getCountrycode()])) {
                throw new Library_Elefunds_Exception_ElefundsCommunicationException(
                    'Requested countrycode was not available. Available country codes are: ' . implode(', ', array_keys($response['receivers'])) . '.',
                    1347966301
                );
            }
            
            $receivers = array();
            
            foreach ($response['receivers'][$this->configuration->getCountrycode()] as $rec) {
                $receiver = $this->createReceiver();
                $receivers[] = $receiver->setId($rec['id'])
                                        ->setName($rec['name'])
                                        ->setDescription($rec['description'])
                                        ->setImages($rec['images'])
                                        ->setValidTime(new DateTime($response['meta']['valid']));
            }

            return($receivers);
      }

      /**
       * Adds a single Donation to the API.
       * 
       * This is just a wrapper for the addDonations method.
       * 
       * @param Library_Elefunds_Model_DonationInterface
       * @throws Library_Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
       * @return string Message returned from the API
       */
      public function addDonation(Library_Elefunds_Model_DonationInterface $donation) {
          return $this->addDonations(array($donation));
      }
      
      /**
       * Deletes a single Donation at the API.
       * 
       * This is just a wrapper for the deleteDonations method.
       * 
       * @param int $donationId
       * @throws InvalidArgumentException if given donationId is not of type integer
       * @throws Library_Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
       * @return string Message returned from the API
       */
      public function deleteDonation($donationId) {
          if(!is_int($donationId) || $donationId <= 0) {
              throw new InvalidArgumentException('Given donationId must be of type integer.', 1348239496967);
          }
          $restUrl = $this->configuration->getApiUrl() . '/donation/' . $donationId . '/?clientId=' . $this->configuration->getClientId() . '&hashedKey=' . $this->configuration->getHashedKey();

          $response = json_decode($this->getConfiguration()->getRestImplementation()->delete($restUrl), TRUE);

          return $response['message'];
      }
      
      /**
       * Sends an array of donations to the API.
       * 
       * @param array $donations
       * @throws Library_Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
       * @return string Message returned from the API
       */
      public function addDonations(array $donations) {
           $restUrl = $this->configuration->getApiUrl() . '/donations/?clientId=' . $this->configuration->getClientId() . '&hashedKey=' . $this->configuration->getHashedKey();
           $donationsArray = array();

           foreach ($donations as $donation) {
               $donationsArray[] = $this->mapDonationToArray($donation);
           }

           $body = json_encode($donationsArray);


           $response = json_decode($this->configuration->getRestImplementation()->post($restUrl, $body), TRUE);
           return $response['message'];
      }

      /**
       * Deletes an array of donation from the API.
       *
       * @param array $donationIds with ids
       *
       * @throws InvalidArgumentException if given array is not made up of integers
       * @throws Library_Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
       * @return string Message returned from the API
       */
      public function deleteDonations(array $donationIds) {
          $isValidArray = $donationIds === array_filter($donationIds, create_function('$receiverIds', 'return is_int($receiverIds) && $receiverIds > 0;'));

          if(!$isValidArray) {
              throw new InvalidArgumentException('Given array is not made up of integers only', 1348237365947);
          }
          $restUrl = $this->configuration->getApiUrl() . '/donations/delete/?clientId=' . $this->configuration->getClientId() . '&hashedKey=' . $this->configuration->getHashedKey();

          $body = json_encode($donationIds);

          $response = json_decode($this->configuration->getRestImplementation()->post($restUrl, $body), TRUE);
          return $response['message'];
      }

    /**
     * Renders the template.
     *
     * @param string $templateName
     *
     * @throws Library_Elefunds_Exception_ElefundsException if template is not set
     * @return string The rendered HTML Snippet
     */
      public function renderTemplate($templateName = 'View') {

          $view = $this->getConfiguration()->getView();

          if($view === NULL) {
              throw new Library_Elefunds_Exception_ElefundsException('There is no template set in your configuration file. Please refer to the documentation or use one of the sample templates.', 1348051662593);
          }

          return $view->render($templateName);
      }
      
      /**
       * Returns the CSS Files required by the template.
       *
       * @throws Library_Elefunds_Exception_ElefundsException if no template is configured
       * @return array with css files (path relative to this library)     
       */
      public function getTemplateCssFiles() {
           $view = $this->getConfiguration()->getView();
            if($view === NULL) {
                 throw new Library_Elefunds_Exception_ElefundsException('There is no template set in your configuration file. Please refer to the documentation or use one of the sample templates.', 1348051662593);                
            } else {
                return $view->getCssFiles();
            }
      }
      
      /**
       * Returns the Javascript Files required by the template.
       *
       * @throws Library_Elefunds_Exception_ElefundsException if no template is configured
       * @return array with javascript files (path relative to this library)     
       */
      public function getTemplateJavascriptFiles() {
           $view = $this->getConfiguration()->getView();
            if($view === NULL) {
                 throw new Library_Elefunds_Exception_ElefundsException('There is no template set in your configuration file. Please refer to the documentation or use one of the sample templates.', 1348051662593);                
            } else {
                return $view->getJavascriptFiles();
            }
      }
      
      /**
       * Maps a Library_Elefunds_Model_DonationInterface to a JSON ready array.
       * 
       * @param Library_Elefunds_Model_DonationInterface
       * @throws Library_Elefunds_Exception_ElefundsException if not all information are given that are needed for the API
       * @return array
       */
      protected function mapDonationToArray(Library_Elefunds_Model_DonationInterface $donation) {
          
          if($donation->getForeignId() === NULL || $donation->getTime() === NULL || $donation->getAmount() === NULL || $donation->getReceiverIds() === NULL || $donation->getAvailableReceiverIds() === NULL) {
              throw new Library_Elefunds_Exception_ElefundsException('Given donation does not contain all information needed to be send to the API.', 1347975987321);
          }
          
          $donationAsArray = array(
                'foreignId'             =>  $donation->getForeignId(),
                'donationTimestamp'     =>  $donation->getTime()->format(DateTime::ISO8601),
                'donationAmount'        =>  $donation->getAmount(),
                'receivers'             =>  $donation->getReceiverIds(),
                'receiversAvailable'    =>  $donation->getAvailableReceiverIds()
           );
           
           // Optional vars
           if($donation->getGrandTotal() !== NULL) {
               $donationAsArray['grandTotal'] = $donation->getGrandTotal();
           }
           if($donation->getSuggestedAmount() !== NULL) {
               $donationAsArray['donationAmountSuggested'] = $donation->getSuggestedAmount();
           }
           
           return $donationAsArray;
          
      }

}