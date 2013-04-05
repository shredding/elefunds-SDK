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

require_once dirname(__FILE__) . '/../../../Model/Receiver.php';

/**
 * Unit Test for Elefunds_Model_Receiver.
 * 
 * @package    elefunds API PHP Library
 * @subpackage Test
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Test_Unit_Model_ReceiverTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Elefunds_Model_Receiver
     */
    protected $receiver;

    /**
     * Sets up the class under test.
     */
    public function setUp() {
      $this->receiver = new Elefunds_Model_Receiver();
   }
   
   /**
    * setIdAcceptsPositiveIntegerValuesAsId
    *
    * @test
    */
   public function setIdAcceptsPositiveIntegerValuesAsId() {
       $this->receiver->setId(1000);
       $this->assertSame(1000, $this->receiver->getId());
   }
   
   /**
    * setIdThrowsErrorIfNonPositiveIdIsGiven
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setIdThrowsErrorIfNonPositiveIdIsGiven() {
       $this->receiver->setId(0);
   }
   
   /**
    * setIdThrowsErrorIfGivenTypeIsNotInt
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setIdThrowsErrorIfGivenTypeIsNotInt() {
       $this->receiver->setId(new stdClass());
   }
   
   /**
    * setNameAcceptsString
    *
    * @test
    */
   public function setNameAcceptsString() {
       $this->receiver->setName('Testing is awesome!');
       $this->assertSame('Testing is awesome!', $this->receiver->getName());
   }
   
   
   /**
    * setNameThrowsErrorIfGivenTypeIsNotString
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setNameThrowsErrorIfGivenTypeIsNotString() {
       $this->receiver->setName(new stdClass());
   }
   
   /**
    * setDescriptionAcceptsString
    *
    * @test
    */
   public function setDescriptionAcceptsString() {
       $this->receiver->setDescription('Testing is awesome!');
       $this->assertSame('Testing is awesome!', $this->receiver->getDescription());
   }
   
   
   /**
    * setDescriptionThrowsErrorIfGivenTypeIsNotString
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setDescriptionThrowsErrorIfGivenTypeIsNotString() {
       $this->receiver->setDescription(new stdClass());
   }
   
   /**
    * addImagesAddsAnImageToTheArrayOfImages
    *
    * @test
    */
   public function addImagesAddsAnImageToTheArrayOfImages() {
       $imageUrl = 'http://elefunds.de/img/logo.png';
       $this->receiver->addImage($imageUrl, 'vertical', 'small');
       $this->assertSame($imageUrl, $this->receiver->getImage('vertical', 'small'));
   }
   
   /**
    * addImagesAcceptsOnlyVerticalOrHorizontalAsFirstKey
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function addImagesAcceptsOnlyVerticalOrHorizontalAsFirstKey() {
       $imageUrl = 'http://elefunds.de/img/logo.png';
       $this->receiver->addImage($imageUrl, 'some invalid string', 'small');
   }
   
   /**
    * addImagesAcceptsOnlySmallMediumAndLargeAsSecondKey
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function addImagesAcceptsOnlySmallMediumAndLargeAsSecondKey() {
       $imageUrl = 'http://elefunds.de/img/logo.png';
       $this->receiver->addImage($imageUrl, 'horizontal', 'invalid');
   }
   
   /**
    * getImagesAcceptsOnlyVerticalOrHorizontalAsFirstKey
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function getImagesAcceptsOnlyVerticalOrHorizontalAsFirstKey() {
       $this->receiver->getImage('http://elefunds.de/img/sample.jpg', 'some invalid string', 'small');
   }
   
   /**
    * getImagesAcceptsOnlySmallMediumAndLargeAsSecondKey
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function getImagesAcceptsOnlySmallMediumAndLargeAsSecondKey() {
       $this->receiver->getImage('http://elefunds.de/img/sample.jpg', 'horizontal', 'large');
   }

    /**
     * setImagesAcceptsAValidStructuredArrayOfImages
     *
     * @test
     */
    public function setImagesAcceptsAValidStructuredArrayOfImages() {
       $images = array(
            'horizontal'    =>  array(
            
                'small' =>  'http://elefunds.de/img/logo1.png',
                'medium' =>  'http://elefunds.de/img/logo2.png',
                'large' =>  'http://elefunds.de/img/logo3.png'
                            
            ),
            
            'vertical'    =>  array(
            
                'small' =>  'http://elefunds.de/img/logo1.png',
                'medium' =>  'http://elefunds.de/img/logo2.png',
                'large' =>  'http://elefunds.de/img/logo3.png'
                            
            )
            
       );
       
       $this->receiver->setImages($images);

       $settedArray = $this->receiver->getImages();

       $this->assertArrayHasKey('horizontal', $settedArray);
       $this->assertArrayHasKey('vertical', $settedArray);
       $this->assertArrayHasKey('small', $settedArray['horizontal']);
       $this->assertArrayHasKey('medium', $settedArray['horizontal']);
       $this->assertArrayHasKey('large', $settedArray['horizontal']);
       $this->assertArrayHasKey('small', $settedArray['vertical']);
       $this->assertArrayHasKey('medium', $settedArray['vertical']);
       $this->assertArrayHasKey('large', $settedArray['vertical']);

       $this->assertSame('http://elefunds.de/img/logo1.png', $settedArray['horizontal']['small']);
       $this->assertSame('http://elefunds.de/img/logo2.png', $settedArray['horizontal']['medium']);
       $this->assertSame('http://elefunds.de/img/logo3.png', $settedArray['horizontal']['large']);
       $this->assertSame('http://elefunds.de/img/logo1.png', $settedArray['vertical']['small']);
       $this->assertSame('http://elefunds.de/img/logo2.png', $settedArray['vertical']['medium']);
       $this->assertSame('http://elefunds.de/img/logo3.png', $settedArray['vertical']['large']);

        // Part structures are okay as well
       $images = array(
            'horizontal'    =>  array(
            
                'small' =>  'http://elefunds.de/img/logo1.png',
                'medium' =>  'http://elefunds.de/img/logo2.png',
            )
       );

        $this->receiver->setImages($images);

        $settedArray = $this->receiver->getImages();
        $this->assertArrayHasKey('horizontal', $settedArray);
        $this->assertArrayHasKey('small', $settedArray['horizontal']);
        $this->assertArrayHasKey('medium', $settedArray['horizontal']);
        $this->assertSame('http://elefunds.de/img/logo1.png', $settedArray['horizontal']['small']);
        $this->assertSame('http://elefunds.de/img/logo2.png', $settedArray['horizontal']['medium']);
   }

   /**
    * setImagesThrowsErrorOnInvalidArrayStructure
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setImagesThrowsErrorOnInvalidArrayStructure() {
        $images = array(
            'horizontal'    =>  array(
            
                'small' =>  'http://elefunds.de/img/logo1.png',
                'IM NOT VALID' =>  'http://elefunds.de/img/logo2.png',
                'large' =>  'http://elefunds.de/img/logo3.png'
                            
            ),
            
            'IM NOT VALID'    =>  array(
            
                'small' =>  'http://elefunds.de/img/logo1.png',
                'medium' =>  'http://elefunds.de/img/logo2.png',
                'large' =>  'http://elefunds.de/img/logo3.png'
                            
            )
            
       );
       
       $this->receiver->setImages($images);
  }
   
   /**
    * setImagesThrowsErrorIfNoMultidimensionalArrayGiven
    *
    * @test
    * @expectedException InvalidArgumentException
    */
   public function setImagesThrowsErrorIfNoMultidimensionalArrayGiven() {
        $images = array(
            'horizontal'    =>  array(
            
                'small' =>  'http://elefunds.de/img/logo1.png',
                'medium' =>  'http://elefunds.de/img/logo2.png',
                'large' =>  'http://elefunds.de/img/logo3.png'
                            
            ),
            
            'vertical'    =>  'http://elefunds.de/img/logo1.png'
            
       );
       
       $this->receiver->setImages($images);
   }

    /**
     * isValidReturnsTrueIfValidTimeIsInTheFuture
     *
     * @test
     */
    public function isValidReturnsTrueIfValidTimeIsInTheFuture() {

       $validTime = new DateTime();
       $validTime->modify('+ 1 month');

       $this->receiver->setValidTime($validTime);

       $this->assertSame(TRUE, $this->receiver->isValid());

   }

    /**
     * isValidReturnsFalseIfValidTimeIsInThePast
     *
     * @test
     */
    public function isValidReturnsFalseIfValidTimeIsInThePast() {

       $validTime = new DateTime();
       $validTime->modify('- 1 month');

       $this->receiver->setValidTime($validTime);

       $this->assertSame(FALSE, $this->receiver->isValid());

   }

}