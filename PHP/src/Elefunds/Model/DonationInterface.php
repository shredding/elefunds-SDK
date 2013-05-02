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

/**
 * elefunds Donation Interface
 * 
 * @package    elefunds API PHP Library
 * @subpackage Model
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
interface Elefunds_Model_DonationInterface  {
    
    /**
     * Sets the foreign ID. A unique ID to identify the order or transaction.
     *
     * @param int $foreignId
     * @throws InvalidArgumentException if type can't be casted to integer
     * @return Elefunds_Model_DonationInterface
     */
    public function setForeignId($foreignId);
    
    /**
     * Returns the foreign ID. A unique ID to identify the order or transaction.
     * 
     * @return int
     */
    public function getForeignId();

    /**
     * Sets the time when the donation took place.
     *
     * @param DateTime $time
     * @return Elefunds_Model_DonationInterface
     */
    public function setTime(DateTime $time);

    /**
     * Returns the time when the donation took place.
     *
     * @return DateTime
     */
    public function getTime();
    
    /**
     * Sets the donated amount.
     *
     * @param int $amount
     * @return Elefunds_Model_DonationInterface
     */
    public function setAmount($amount);
  
    /**
     * Returns the donated amount in the smallest unit available in your currency (e.g. cent in EUR or $).
     * 
     * @return int
     */
    public function getAmount();
    
    /**
     * Sets the amount that was suggested to the customer.
     *
     * @param int $amount
     * @return Elefunds_Model_DonationInterface
     */
    public function setSuggestedAmount($amount);
    
    /**
     * Returns the amount that was suggested to the customer in the smallest unit available in your currency (e.g. cent in EUR or $).
     * 
     * @return int
     */
    public function getSuggestedAmount();
    
    /**
     * Adds a receiverId to the list of receivers who preserve a share of
     * the donation.
     *
     * @param int $receiverId
     * @throws InvalidArgumentException if given id is not a positive integer
     * @return Elefunds_Model_DonationInterface
     */
    public function addReceiverId($receiverId);
    
    /**
     * Set the list of receivers who preserve a share of
     * the donation.
     *
     * @param array $receiverIds array with receiverIds (integers)
     * @throws InvalidArgumentException if given array contains a value that is not a positive integer
     * @return Elefunds_Model_DonationInterface
     */
    public function setReceiverIds(array $receiverIds);
    
    /**
     * Returns all receivers who preserve a share of
     * the donation.
     * 
     * @return array with receiverIds (integers)
     */
    public function getReceiverIds();
    
    /**
     * Adds a receiverId to the list of receivers that were available to the customer.
     *
     * @param int $receiverId
     * @throws InvalidArgumentException if given id is not a positive integer
     * @return Elefunds_Model_DonationInterface
     */
    public function addAvailableReceiverId($receiverId);
    
    /**
     * Sets an array of integers to the list of receivers that were available to the customer
     *
     * @param array $receiverIds array with receiverIds (integers)
     * @throws InvalidArgumentException if given array contains a value that is not a positive integer
     * @return Elefunds_Model_DonationInterface
     */
    public function setAvailableReceiverIds(array $receiverIds);
    
    /**
     * Returns all receivers that were available to the customer.
     * 
     * @return array array with receiverIds (integers)
     */
    public function getAvailableReceiverIds();

    /**
     * Returns the overall total of the process that is associated with the donation.
     * In the smallest unit available in your currency (e.g. cent in EUR or $).
     * 
     * @return int
     */
    public function getGrandTotal();
    
    /**
     * Sets the overall total of the process that is associated with the donation.
     *
     * @param int $grandTotal 
     * @throws InvalidArgumentException if type can't be casted to integer
     * @return Elefunds_Model_DonationInterface
     */
    public function setGrandTotal($grandTotal);

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
    public function setDonator($email, $firstName, $lastName, $streetAddress, $zip, $city, $countryCode = NULL);

    /**
     * Returns the array with donator information.
     *
     * @return array
     */
    public function getDonatorInformation();

    /**
     * Returns an associative array with all available information
     * about this donation instance.
     *
     * @return array
     */
    public function toArray();
    
}