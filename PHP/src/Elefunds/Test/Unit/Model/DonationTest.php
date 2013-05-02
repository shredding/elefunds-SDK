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

require_once dirname(__FILE__) . '/../../../Model/Donation.php';

/**
 * Unit Test for Elefunds_Models_Donation.
 * 
 * @package    elefunds API PHP Library
 * @subpackage Test
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Test_Unit_Model_DonationTest extends PHPUnit_Framework_TestCase {

   /**
    * @var Elefunds_Model_Donation
    */
   protected $donation;

   /**
    * Sets up the class under test.
    */
    public function setUp() {
      $this->donation = new Elefunds_Model_Donation();
   }
   
   /**
    * setForeignIdAcceptsNumbersOrDigitOnlyString
    *
    * @test
    */ 
   public function setForeignIdAcceptsNumbersOrDigitOnlyString() {
        $this->donation->setForeignId('12345');
        $this->assertSame('12345', $this->donation->getForeignId());
        
        $this->donation->setForeignId(12345);
        $this->assertSame('12345', $this->donation->getForeignId());
   }
   
   /**
    * setAmountMustBeOfTypeIntegerOrADigitOnlyString
    *
    * @test
    */
   public function setAmountMustBeOfTypeIntegerOrADigitOnlyString() {
       $this->donation->setAmount(1234);
       $this->assertSame(1234, $this->donation->getAmount());
   
       $this->donation->setAmount('125');
       $this->assertSame(125, $this->donation->getAmount());
       
   }
   
   /**
    * setAmountReturnsZeroIfANegativeValueIsGiven
    *
    * @test
    */
   public function setAmountReturnsZeroIfANegativeValueIsGiven() {
       $this->donation->setAmount(-125);
       $this->assertSame(0, $this->donation->getAmount());
   }
   
   /**
    * setAmountThrowsErrorIfGivenStringIsNotCastableToInt
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setAmountThrowsErrorIfGivenStringIsNotCastableToInt() {
       $this->donation->setAmount('This string is not castable to int.');
   }

   /**
    * setAmountThrowsErrorIfGivenValueIsNeitherCastableStringOrInt
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setAmountThrowsErrorIfGivenValueIsNeitherCastableStringOrInt() {
       $this->donation->setAmount(array());
   }
   
   /**
    * setSuggestedAmountMustBeOfTypeIntOrADigitOnlyString
    *
    * @test
    */
   public function setSuggestedAmountMustBeOfTypeIntOrADigitOnlyString() {
       $this->donation->setSuggestedAmount(125);
       $this->assertSame(125, $this->donation->getSuggestedAmount());
   
       $this->donation->setSuggestedAmount('125');
       $this->assertSame(125, $this->donation->getSuggestedAmount());
   }
   
   /**
    * setSuggestedAmountReturnsZeroIfANegativeValueIsGiven
    *
    * @test
    */
   public function setSuggestedAmountReturnsZeroIfANegativeValueIsGiven() {
       $this->donation->setSuggestedAmount(-125);
       $this->assertSame(0, $this->donation->getSuggestedAmount());
   }
   
   /**
    * setSuggestedAmountThrowsErrorIfGivenStringIsNotCastableToInt
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setSuggestedAmountThrowsErrorIfGivenStringIsNotCastableToInt() {
       $this->donation->setSuggestedAmount('This string is not castable to int.');
   }
   
   
   /**
    * setSuggestedAmountThrowsErrorIfGivenValueIsNeitherCastableStringOrInt
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setSuggestedAmountThrowsErrorIfGivenValueIsNeitherCastableStringOrInt() {
       $this->donation->setSuggestedAmount(array());
   }
   
   /**
    * addReceiverIdAddsAnIntToTheArrayOfReceiverIds
    *
    * @test
    */
   public function addReceiverIdAddsAnIntToTheArrayOfReceiverIds() {
       $this->donation->addReceiverId(1234567890);
       $this->assertTrue(in_array(1234567890, $this->donation->getReceiverIds()));
   }
   
   /**
    * addReceiverThrowsErrorIfGivenTypeIsNotInt
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function addReceiverThrowsErrorIfGivenTypeIsNotInt() {
       $this->donation->addReceiverId('A string is given here.');
   }
   
   /**
    * addReceiverThrowsErrorIfGivenTypeNegativeInt
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function addReceiverThrowsErrorIfGivenTypeNegativeInt() {
       $this->donation->addReceiverId(-5);
   }
   
   /**
    * setReceiverIdsSetsIdsIfAllArrayValuesArePositiveInteger
    *
    * @test
    */
   public function setReceiverIdsSetsIdsIfAllArrayValuesArePositiveInteger() {
       $ids = array(1,2,3,4,5,6,7,8,9);
       $this->donation->setReceiverIds($ids);
       $this->assertSame($this->donation->getReceiverIds(), $ids);
   }
   
   /**
    * setReceiverIdsThrowsErrorIfNotAllArrayValuesArePositiveInteger
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setReceiverIdsThrowsErrorIfNotAllArrayValuesArePositiveInteger() {
       $ids = array(1,2,3,4,-5,6,7,8,9);
       $this->donation->setReceiverIds($ids);
   }
   
   /**
    * setReceiverIdsThrowsErrorIfNotAllArrayValuesAreInteger
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setReceiverIdsThrowsErrorIfNotAllArrayValuesAreInteger() {
       $ids = array(1,2,3,4, 'A string is injected into the array.',6,7,8,9);
       $this->donation->setReceiverIds($ids);
   }
   
   /**
    * addAvailableReceiverIdAddsAnIntToTheArrayOfReceiverIds
    *
    * @test
    */
   public function addAvailableReceiverIdAddsAnIntToTheArrayOfReceiverIds() {
       $this->donation->addAvailableReceiverId(1234567890);
       $this->assertTrue(in_array(1234567890, $this->donation->getAvailableReceiverIds()));
   }
   
   /**
    * addAvailableReceiverThrowsErrorIfGivenTypeIsNotInt
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function addAvailableReceiverThrowsErrorIfGivenTypeIsNotInt() {
       $this->donation->addAvailableReceiverId('A string is given here.');
   }
   
   /**
    * addAvailableReceiverThrowsErrorIfGivenTypeNegativeInt
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function addAvailableReceiverThrowsErrorIfGivenTypeNegativeInt() {
       $this->donation->addAvailableReceiverId(-5);
   }
   
   /**
    * setAvailableReceiverIdsSetsIdsIfAllArrayValuesArePositiveInteger
    *
    * @test
    */
   public function setAvailableReceiverIdsSetsIdsIfAllArrayValuesArePositiveInteger() {
       $ids = array(1,2,3,4,5,6,7,8,9);
       $this->donation->setAvailableReceiverIds($ids);
       $this->assertSame($this->donation->getAvailableReceiverIds(), $ids);
   }
   
   /**
    * setAvailableReceiverIdsThrowsErrorIfNotAllArrayValuesArePositiveInteger
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setAvailableReceiverIdsThrowsErrorIfNotAllArrayValuesArePositiveInteger() {
       $ids = array(1,2,3,4,-5,6,7,8,9);
       $this->donation->setAvailableReceiverIds($ids);
   }
   
   /**
    * setAvailableReceiverIdsThrowsErrorIfNotAllArrayValuesAreInteger
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setAvailableReceiverIdsThrowsErrorIfNotAllArrayValuesAreInteger() {
       $ids = array(1,2,3,4, 'A string is injected into the array.',6,7,8,9);
       $this->donation->setAvailableReceiverIds($ids);
   }
   
   /**
    * setGrandTotalMustBeOfTypeIntOrDigitOnlyString
    *
    * @test
    */
   public function setGrandTotalMustBeOfTypeIntOrDigitOnlyString() {
       $this->donation->setGrandTotal(125);
       $this->assertSame(125, $this->donation->getGrandTotal());
   
       $this->donation->setGrandTotal('125');
       $this->assertSame(125, $this->donation->getGrandTotal());
   }
   
   /**
    * setGrandTotaReturnsZeroIfANegativeValueIsGiven
    *
    * @test
    */
   public function setGrandTotaReturnsZeroIfANegativeValueIsGiven() {
       $this->donation->setGrandTotal(-125);
       $this->assertSame(0, $this->donation->getGrandTotal());
   }
   
   /**
    * setGrandTotalThrowsErrorIfGivenStringIsNotCastableToInt
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setGrandTotalThrowsErrorIfGivenStringIsNotCastableToInt() {
       $this->donation->setGrandTotal('This string is not castable to int.');
   }
    
   /**
    * setGrandTotalThrowsErrorIfGivenValueIsNeitherCastableStringOrInt
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setGrandTotalThrowsErrorIfGivenValueIsNeitherCastableStringOrInt() {
       $this->donation->setGrandTotal(array());
   }
   
   /**
    * setTimeStoresTimeInProperty
    *
    * @test
    */
   public function setTimeStoresTimeInProperty() {
       date_default_timezone_set('Europe/Berlin');
       $time = new DateTime();
       $this->donation->setTime($time);
       $this->assertSame($time, $this->donation->getTime());
   }

    /**
     * setDonatorAcceptsAValidUser
     *
     * @test
     */
    public function setDonatorAcceptsAValidUser() {
        $this->donation->setDonator('hello@elefunds.de', 'Christian', 'Peters', 'Schönhauser Allee 124', 10243, 'Berlin', 'de');

        $donator = $this->donation->getDonatorInformation();

        $this->assertSame($donator['email'], 'hello@elefunds.de');
        $this->assertSame($donator['firstName'], 'Christian');
        $this->assertSame($donator['lastName'], 'Peters');
        $this->assertSame($donator['streetAddress'], 'Schönhauser Allee 124');
        $this->assertSame($donator['zip'], 10243);
        $this->assertSame($donator['city'], 'Berlin');
        $this->assertSame($donator['countryCode'], 'de');

    }

    /**
     * setDonatorNeedsAValidEmail
     *
     * @test
     * @expectedException InvalidArgumentException
     */
    public function setDonatorNeedsAValidEmail() {
        $this->donation->setDonator('hello@elefunds', 'Christian', 'Peters', 'Schönhauser Allee 124', 10243, 'Berlin', 'de');
    }

    /**
     * setDonatorDoesNotUseInvalidCountryCodes
     *
     * @test
     */
    public function setDonatorDoesNotUseInvalidCountryCodes() {
        $this->donation->setDonator('hello@elefunds.de', 'Christian', 'Peters', 'Schönhauser Allee 124', 10243, 'Berlin', 'invalid');

        $donator = $this->donation->getDonatorInformation();
        $this->assertSame(FALSE, isset($donator['countryCode']));
    }

    /**
     * toArrayReturnsArrayWithEverythingThatIsSet
     *
     * @test
     */
    public function toArrayReturnsArrayWithEverythingThatIsSet() {

        $now = new DateTime();

        $this->donation
            ->setAmount(100)
            ->setAvailableReceiverIds(array(1,2,3))
            ->setForeignId('1234')
            ->setReceiverIds(array(1,2))
            ->setTime($now);

        $array = $this->donation->toArray();

        $this->assertSame($array['donationAmount'], 100);
        $this->assertSame(implode(',', $array['receiversAvailable']), '1,2,3');
        $this->assertSame($array['foreignId'], '1234');
        $this->assertSame(implode(',', $array['receivers']), '1,2');
        $this->assertSame($array['donationTimestamp'], $now->format(DateTime::ISO8601));

        $this->assertSame(FALSE, isset($array['grandTotal']));
        $this->assertSame(FALSE, isset($array['donationAmountSuggested']));
        $this->assertSame(FALSE, isset($array['donator']));

        $this->donation->setGrandTotal(999);
        $this->donation->setSuggestedAmount(888);
        $this->donation->setDonator('hello@elefunds.de', 'Christian', 'Peters', 'Schönhauser Allee 124', 10243, 'Berlin', 'invalid');

        $array = $this->donation->toArray();
        $this->assertSame($array['grandTotal'], 999);
        $this->assertSame($array['donationAmountSuggested'], 888);
        $this->assertSame(TRUE, isset($array['donator']));

    }

}
