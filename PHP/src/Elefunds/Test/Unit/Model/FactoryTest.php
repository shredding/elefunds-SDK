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

require_once dirname(__FILE__) . '/../../../Model/Factory.php';
require_once dirname(__FILE__) . '/../../../Model/Donation.php';
require_once dirname(__FILE__) . '/../../../Model/Receiver.php';


/**
 * Unit Test for Elefunds_Model_Factory.
 * 
 * @package    elefunds API PHP Library
 * @subpackage Test
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Test_Unit_Model_FactoryTest extends PHPUnit_Framework_TestCase {
   
   
   /**
    * getDonationProducesDonationPrototype
    *
    * @test
    */ 
   public function getDonationProducesDonationPrototype() {
       
       Elefunds_Model_Factory::setDonationImplementation('Elefunds_Model_Donation');
       $prototype = Elefunds_Model_Factory::getDonation();
       
       $this->assertInstanceOf('Elefunds_Model_DonationInterface', $prototype);
   }
   
   /**
    * getReceiverProducesReceiverPrototype
    *
    * @test
    */ 
   public function getReceiverProducesReceiverPrototype() {
       
       Elefunds_Model_Factory::setReceiverImplementation('Elefunds_Model_Receiver');
       $prototype = Elefunds_Model_Factory::getReceiver();
       
       $this->assertInstanceOf('Elefunds_Model_ReceiverInterface', $prototype);
   }
   
   /**
    * getDonationProducesErrorIfGivenInstanceIsNotDonationInterface
    *
    * @test
    * @expectedException Elefunds_Exception_ElefundsException
    */ 
   public function getDonationProducesErrorIfGivenInstanceIsNotDonationInterface() {
       Elefunds_Model_Factory::setDonationImplementation('Elefunds_Model_Receiver');
       Elefunds_Model_Factory::getDonation();
   }
   
   /**
    * getReceiverProducesErrorIfGivenInstanceIsNotReceiverInterface
    *
    * @test
    * @expectedException Elefunds_Exception_ElefundsException
    */ 
   public function getReceiverProducesErrorIfGivenInstanceIsNotReceiverInterface() {
       Elefunds_Model_Factory::setReceiverImplementation('Elefunds_Model_Donation');
       Elefunds_Model_Factory::getReceiver();
   }
   
}
