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

require_once 'DonationInterface.php';

/**
 * Elefunds Donation Model
 *
 * @package    elefunds API PHP Library
 * @subpackage Model
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Model_Donation implements Elefunds_Model_DonationInterface {

    /**
     * Order or unique transaction ID
     *
     * @var string
     */
    protected $foreignId;

    /**
     * The amount that was donated (in Cents)
     *
     * @var int
     */
    protected $amount;

    /**
     * The amount that was suggested during the checkout (in Cents)
     *
     * @var int
     */
     protected $suggestedAmount;

     /**
      * Array containing integers of receiver IDs.
      *
      * @var array
      */
     protected $receiverIds = array();

     /**
      * Array containing integers of all available receivers.
      *
      * @var array
      */
     protected $availableReceiverIds = array();

     /**
      * Grand total of the order / transaction that is associated with this donation (in Cents)
      *
      * @var int
      */
     protected $grandTotal;

     /**
      * The time, when the donation was made.
      *
      * @var DateTime
      */
     protected $time;

    /**
     * Donator information in the following structure
     *
     * <code>
     *  array(
     *      'email'          =>      'hello@elefunds.de',
     *      'firstName'      =>      'Christian',
     *      'lastName'       =>      'Peters',
     *      'streetAddress'  =>      'Schönhauser Allee 124',
     *      'zip'            =>      10437,
     *      'city'           =>      'Berlin',
     *      'countryCode'    =>      'de'
     *  );
     * </code>
     *
     * @var array
     */
    protected $donator = array();

    /**
     * Returns the foreign ID. A unique ID to identify the order or transaction.
     *
     * @return string
     */
    public function getForeignId() {
        return $this->foreignId;
    }

    /**
     * Sets the foreign ID. A unique ID to identify the order or transaction.
     *
     * @param string $foreignId
     * @throws InvalidArgumentException if type can't be casted to integer
     * @return Elefunds_Model_DonationInterface
     */
    public function setForeignId($foreignId) {
        if (preg_match('/^[A-Za-z0-9_+\-#:.,äüöÄÜÖß]*$/', $foreignId)) {
            $this->foreignId = (string)$foreignId;
        } else {
            throw new InvalidArgumentException('Given foreignId was not of of a type that can be casted to integer.', 1347557226);
        }

        return $this;
    }

    /**
     * Returns the donated amount in the smallest unit available in your currency (e.g. cent in EUR or $).
     *
     * @return int
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * Sets the donated amount.
     *
     * @param int $amount
     *
     * @throws InvalidArgumentException
     * @return Elefunds_Model_DonationInterface
     */
    public function setAmount($amount) {
        if (is_int($amount) || ctype_digit($amount)) {
            $this->amount = $amount > 0 ? (int)$amount : 0 ;
        } else {
            throw new InvalidArgumentException('Given amount was not of of a type that can be casted to integer.', 1347557226);
        }
        return $this;
    }

    /**
     * Returns the amount that was suggested to the customer in the smallest unit available in your currency (e.g. cent in EUR or $).
     *
     * @return int
     */
    public function getSuggestedAmount() {
        return $this->suggestedAmount;
    }

    /**
     * Sets the time when the donation took place.
     *
     * @param DateTime $time
     * @return Elefunds_Model_DonationInterface
     */
    public function setTime(DateTime $time) {
        $this->time = $time;
        return $this;
    }

    /**
     * Returns the time when the donation took place.
     *
     * @return DateTime
     */
    public function getTime() {
        return $this->time;
    }

    /**
     * Sets the amount that was suggested to the customer.
     *
     * @param int $amount
     *
     * @throws InvalidArgumentException
     * @return Elefunds_Model_DonationInterface
     */
    public function setSuggestedAmount($amount) {
        if (is_int($amount) || ctype_digit($amount)) {
            $this->suggestedAmount = $amount > 0 ? (int)$amount : 0 ;
        } else {
            throw new InvalidArgumentException('Given amount was not of of a type that can be casted to integer.', 1347557226);
        }
        return $this;
    }

    /**
     * Adds a receiverId to the list of receivers who preserve a share of
     * the donation.
     *
     * @param int $receiverId
     * @throws InvalidArgumentException if given id is not a positive integer
     * @return Elefunds_Model_DonationInterface
     */
    public function addReceiverId($receiverId) {
        if (is_int($receiverId) && $receiverId > 0) {
            $this->receiverIds[] = $receiverId;
        } else {
            throw new InvalidArgumentException('Given value must be a positive integer.', 1347721362);
        }
        return $this;
    }

    /**
     * Set the list of receivers who preserve a share of
     * the donation.
     *
     * @param array $receiverIds array with receiverIds (integers)
     * @throws InvalidArgumentException if given array contains a value that is not a positive integer
     * @return Elefunds_Model_DonationInterface
     */
    public function setReceiverIds(array $receiverIds) {

        $isValidArray = $receiverIds === array_filter($receiverIds, create_function('$receiverIds', 'return is_int($receiverIds) && $receiverIds > 0;'));

        if ($isValidArray) {
            $this->receiverIds = $receiverIds;
        } else {
           throw new InvalidArgumentException('Given array may only contain positive integers.', 1347721363);
        }

        return $this;

    }

    /**
     * Returns all receivers who preserve a share of
     * the donation.
     *
     * @return array $receiverIds array with receiverIds (integers)
     */
    public function getReceiverIds() {
        return $this->receiverIds;
    }

    /**
     * Adds a receiverId to the list of receivers that were available to the customer.
     *
     * @param int $receiverId
     * @throws InvalidArgumentException if given id is not a positive integer
     * @return Elefunds_Model_DonationInterface
     */
    public function addAvailableReceiverId($receiverId) {
        if (is_int($receiverId) && $receiverId > 0) {
            $this->availableReceiverIds[] = $receiverId;
        } else {
            throw new InvalidArgumentException('Given value must be a positive integer.', 1347721366);
        }
        return $this;
    }

    /**
     * Sets an array of integers to the list of receivers that were available to the customer
     *
     * @param array $receiverIds array with receiverIds (integers)
     * @throws InvalidArgumentException if given array contains a value that is not a positive integer
     * @return Elefunds_Model_DonationInterface
     */
    public function setAvailableReceiverIds(array $receiverIds) {

        $isValidArray = $receiverIds === array_filter($receiverIds, create_function('$receiverIds', 'return is_int($receiverIds) && $receiverIds > 0;'));

        if ($isValidArray) {
            $this->availableReceiverIds = $receiverIds;
        } else {
           throw new InvalidArgumentException('Given array may only contain positive integers.', 1347721363);
        }

        return $this;

    }

    /**
     * Returns all receivers that were available to the customer.
     *
     * @return array $receiverIds array with receiverIds (integers)
     */
    public function getAvailableReceiverIds() {
        return $this->availableReceiverIds;
    }

    /**
     * Returns the overall total of the process that is associated with the donation (in Cents).
     *
     * @return int
     */
    public function getGrandTotal() {
        return $this->grandTotal;
    }

    /**
     * Sets the overall total of the process that is associated with the donation (in Cents).
     *
     * @param int $grandTotal
     * @throws InvalidArgumentException if type can't be casted to integer
     * @return Elefunds_Model_DonationInterface
     */
    public function setGrandTotal($grandTotal) {
        if (is_int($grandTotal) || ctype_digit($grandTotal)) {
            $this->grandTotal = $grandTotal > 0 ? (int)$grandTotal : 0;
        } else {
            throw new InvalidArgumentException('Given total was not of of a type that can be casted to integer.', 1347557227);
        }
        return $this;
    }

    /**
     * Sets the donator information.
     *
     * The setting of the donator information is optional, but required if the donator want to get a donation receipt.
     * If needed, all you need to do is to provide these information, everything else is taken care of be the
     * elefunds foundation.
     *
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $streetAddress
     * @param int $zip
     * @param string $city
     * @param string $countryCode two digit country code; if not given, the code from your settings will be used
     *
     * @return Elefunds_Model_Donation
     * @throws InvalidArgumentException
     */
    public function setDonator($email, $firstName, $lastName, $streetAddress, $zip, $city, $countryCode = NULL) {
        $validMail = filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE;

        if ($validMail && is_string($firstName) && is_string($lastName) && is_string($streetAddress) && is_int($zip) && is_string($city)) {

            $this->donator = array(
                'email'             =>  $email,
                'firstName'         =>  $firstName,
                'lastName'          =>  $lastName,
                'streetAddress'     =>  $streetAddress,
                'zip'               =>  $zip,
                'city'              =>  $city,
            );


            if ($countryCode !== NULL && is_string($countryCode) && strlen($countryCode) === 2) {
                $this->donator['countryCode'] = $countryCode;
            }

        } else {
            throw new InvalidArgumentException('Given donator information are not in the correct format.', 1352721709);
        }

        return $this;
    }

    /**
     * Returns the array with donator information.
     *
     * @return array
     */
    public function getDonatorInformation() {
        return $this->donator;
    }

    /**
     * Returns an associative array with all available information
     * about this donation instance.
     *
     * @return array
     */
    public function toArray() {

        $donationAsArray = array(
            'foreignId'             =>  $this->getForeignId(),
            'donationTimestamp'     =>  $this->getTime()->format(DateTime::ISO8601),
            'donationAmount'        =>  $this->getAmount(),
            'receivers'             =>  $this->getReceiverIds(),
            'receiversAvailable'    =>  $this->getAvailableReceiverIds()
        );

        // Optional vars
        $donator = $this->getDonatorInformation();
        if (count($donator) > 0) {
            $donationAsArray['donator'] = $donator;
        }

        if ($this->getGrandTotal() !== NULL) {
            $donationAsArray['grandTotal'] = $this->getGrandTotal();
        }

        if ($this->getSuggestedAmount() !== NULL) {
            $donationAsArray['donationAmountSuggested'] = $this->getSuggestedAmount();
        }

        return $donationAsArray;
    }

}
