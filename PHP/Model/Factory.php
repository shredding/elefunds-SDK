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

require_once 'ReceiverInterface.php';

/**
 * Elefunds Model Factory
 *
 * @package    elefunds API PHP Library
 * @subpackage Model
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Model_Factory {
    
    /**
     * @var string
     */
    protected static $donationPrototype;
    
    /**
     * @var string
     */
    protected static $receiverPrototype;
    
    /**
     * Sets the prototype for the implementation.
     * 
     * Be sure to require the class, if you opt to use your own implementation.
     * 
     * @param string $donation the class name of the implementation.
     * @return void
     */
    public static function setDonationImplementation($donation) {
        self::$donationPrototype = $donation;
    }
    
    /**
     * Sets the prototype for the implementation.
     * 
     * Be sure to require the class, if you opt to use your own implementation.
     * 
     * @param string $receiver the class name of the implementation.
     * @return void
     */
    public static function setReceiverImplementation($receiver) {
        self::$receiverPrototype = $receiver;
    }
    
    /**
     * Returns a copy of the provided donation.
     * 
     * @throws Elefunds_Exception_ElefundsException if class does not implement the DonationInterface
     * @return Elefunds_Model_DonationInterface
     */
    public static function getDonation() {
        $donationPrototype = new self::$donationPrototype();
        if ($donationPrototype instanceof Elefunds_Model_DonationInterface === FALSE) {
            throw new Elefunds_Exception_ElefundsException(
                'Given prototype for donation does not implement Elefunds_Model_DonationInterface',
                1347883795444);
        }
        return $donationPrototype;
    }
    
    /**
     * Returns a copy of the provided receiver. 
     *
     * @throws Elefunds_Exception_ElefundsException if class does not implement the ReceiverInterface
     * @return Elefunds_Model_ReceiverInterface
     */
    public static function getReceiver() {
        $receiverPrototype = new self::$receiverPrototype();
        if ($receiverPrototype instanceof Elefunds_Model_ReceiverInterface === FALSE) {
            throw new Elefunds_Exception_ElefundsException(
                'Given prototype for donation does not implement Elefunds_Model_ReceiverInterface',
                1347883795444);
        }
        return $receiverPrototype;
    }
      
}
    