x<?php

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

require_once dirname(__FILE__) . '/../../Facade.php';

/**
 * Unit Test for Elefunds_Facade
 *
 * @package    elefunds API PHP Library
 * @subpackage Test
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Test_Unit_Facade extends PHPUnit_Framework_TestCase {

   /**
    * @var Elefunds_Facade
    */
   protected $facade;

    /**
     * @var DateTime
     */
    protected $uniqueTimestampForAllTests;

    /**
     * Sets up the class under test.
     */
    public function setUp() {
      $this->facade = new Elefunds_Facade();

       date_default_timezone_set('Europe/Berlin');
       $this->uniqueTimestampForAllTests = new DateTime();
   }

   /**
    * setConfigurationCallsInit
    *
    * @test
    */
   public function setConfigurationCallsInit() {

        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
                      ->method('init');

       $this->facade->setConfiguration($configuration);
    }

    /**
     * When a donation is persisted, there are a few properties required to be set.
     * We test that here.
     *
     * @test
     * @expectedException Elefunds_Exception_ElefundsException
     */
    public function addDonationsThrowsErrorIfDonationIsNotRichEnough() {

        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
            ->method('getApiUrl')
            ->will($this->returnValue('https://api.elefunds.de'));

        $configuration->expects($this->once())
            ->method('getClientId')
            ->will($this->returnValue(1234));

        $configuration->expects($this->once())
            ->method('getHashedKey')
            ->will($this->returnValue('3382a100edcb335c6af4efc1d5fb37b4ec264553'));

        $this->facade->setConfiguration($configuration);

        $donations = array($this->getMock('Elefunds_Model_DonationInterface'));

        $this->facade->addDonations($donations);
    }

    /**
     * cancelDonationCalculatesCorrectApiUrl
     *
     * @test
     */
    public function cancelDonationCalculatesCorrectApiUrl() {
        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
                       ->method('getApiUrl')
                       ->will($this->returnValue('https://api.elefunds.de'));

        $configuration->expects($this->once())
                      ->method('getClientId')
                      ->will($this->returnValue(1234));

        $configuration->expects($this->once())
                       ->method('getHashedKey')
                       ->will($this->returnValue('3382a100edcb335c6af4efc1d5fb37b4ec264553'));

        $rest = $this->getMock('Elefunds_Communication_RestInterface');

        $rest->expects($this->once())
             ->method('delete')
             ->with($this->equalTo('https://api.elefunds.de/donations/1234/?clientId=1234&hashedKey=3382a100edcb335c6af4efc1d5fb37b4ec264553'))
             ->will($this->returnValue(json_encode(array('message' => 'Works like a charm!'))));

        $configuration->expects($this->once())
                      ->method('getRestImplementation')
                      ->will($this->returnValue($rest));


        $this->facade->setConfiguration($configuration);

        $result = $this->facade->cancelDonation(1234);
        $this->assertSame('Works like a charm!', $result);
    }

    /**
     * completeDonationCalculatesCorrectApiUrl
     *
     * @test
     */
    public function completeDonationCalculatesCorrectApiUrl() {
        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
            ->method('getApiUrl')
            ->will($this->returnValue('https://api.elefunds.de'));

        $configuration->expects($this->once())
            ->method('getClientId')
            ->will($this->returnValue(1234));

        $configuration->expects($this->once())
            ->method('getHashedKey')
            ->will($this->returnValue('3382a100edcb335c6af4efc1d5fb37b4ec264553'));

        $rest = $this->getMock('Elefunds_Communication_RestInterface');

        $rest->expects($this->once())
            ->method('put')
            ->with($this->equalTo('https://api.elefunds.de/donations/1234/?clientId=1234&hashedKey=3382a100edcb335c6af4efc1d5fb37b4ec264553'))
            ->will($this->returnValue(json_encode(array('message' => 'Works like a charm!'))));

        $configuration->expects($this->once())
            ->method('getRestImplementation')
            ->will($this->returnValue($rest));


        $this->facade->setConfiguration($configuration);

        $result = $this->facade->completeDonation(1234);
        $this->assertSame('Works like a charm!', $result);
    }

    /**
     * addDonationsCallsCorrectAPiUrl
     *
     * @test
     */
    public function addDonationsCallsCorrectAPiUrl() {

        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
                       ->method('getApiUrl')
                       ->will($this->returnValue('https://api.elefunds.de'));

        $configuration->expects($this->once())
                      ->method('getClientId')
                      ->will($this->returnValue(1234));

        $configuration->expects($this->once())
                      ->method('getHashedKey')
                      ->will($this->returnValue('3382a100edcb335c6af4efc1d5fb37b4ec264553'));

        $rest = $this->getMock('Elefunds_Communication_RestInterface');

        $rest->expects($this->once())
              ->method('post')
              ->with(
                    $this->equalTo('https://api.elefunds.de/donations/?clientId=1234&hashedKey=3382a100edcb335c6af4efc1d5fb37b4ec264553'),
                    json_encode(
                        array(
                            array(
                                'foreignId'             =>  1234,
                                'donationTimestamp'     =>  $this->uniqueTimestampForAllTests->format(DateTime::ISO8601),
                                'donationAmount'        =>  1000,
                                'receivers'             =>  array(1,2,3),
                                'receiversAvailable'    =>  array(1,2,3)
                            )
                        )
                    )

              )
              ->will($this->returnValue(json_encode(array('message' => 'Works like a charm!'))));

        $configuration->expects($this->once())
                      ->method('getRestImplementation')
                      ->will($this->returnValue($rest));

        $donation = $this->getMock('Elefunds_Model_DonationInterface');

        $donation->expects($this->any())
                  ->method('getForeignId')
                  ->will($this->returnValue(1234));

        $donation->expects($this->any())
                  ->method('toArray')
                  ->will($this->returnValue(
                        array(
                            'foreignId'             =>  1234,
                            'donationTimestamp'     =>  $this->uniqueTimestampForAllTests->format(DateTime::ISO8601),
                            'donationAmount'        =>  1000,
                            'receivers'             =>  array(1,2,3),
                            'receiversAvailable'    =>  array(1,2,3)
                        )
                   ));


        $donation->expects($this->any())
                  ->method('getTime')
                  ->will($this->returnValue($this->uniqueTimestampForAllTests));

        $donation->expects($this->any())
                  ->method('getAmount')
                  ->will($this->returnValue(1000));

        $donation->expects($this->any())
                 ->method('getReceiverIds')
                 ->will($this->returnValue(array(1,2,3)));

        $donation->expects($this->any())
                  ->method('getAvailableReceiverIds')
                  ->will($this->returnValue(array(1,2,3)));


        $this->facade->setConfiguration($configuration);
        $result = $this->facade->addDonations(array($donation));
        $this->assertSame($result, 'Works like a charm!');
    }

    /**
     * cancelDonationsCallsCorrectAPiUrl
     *
     * @test
     */
    public function cancelDonationsCallsCorrectAPiUrl() {

        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
                      ->method('getApiUrl')
                      ->will($this->returnValue('https://api.elefunds.de'));

        $configuration->expects($this->once())
                      ->method('getClientId')
                      ->will($this->returnValue(1234));

        $configuration->expects($this->once())
                      ->method('getHashedKey')
                      ->will($this->returnValue('3382a100edcb335c6af4efc1d5fb37b4ec264553'));

        $rest = $this->getMock('Elefunds_Communication_RestInterface');

        $rest->expects($this->once())
                ->method('delete')
                ->with($this->equalTo('https://api.elefunds.de/donations/1,2,3,4/?clientId=1234&hashedKey=3382a100edcb335c6af4efc1d5fb37b4ec264553'))
                ->will($this->returnValue(json_encode(array('message' => 'Works like a charm!'))));

        $configuration->expects($this->once())
            ->method('getRestImplementation')
            ->will($this->returnValue($rest));

        $this->facade->setConfiguration($configuration);
        $result = $this->facade->cancelDonations(array(1, 2, 3, 4));
        $this->assertSame($result, 'Works like a charm!');
    }

    /**
     * completeDonationsCallsCorrectAPiUrl
     *
     * @test
     */
    public function completeDonationsCallsCorrectAPiUrl() {

        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
            ->method('getApiUrl')
            ->will($this->returnValue('https://api.elefunds.de'));

        $configuration->expects($this->once())
            ->method('getClientId')
            ->will($this->returnValue(1234));

        $configuration->expects($this->once())
            ->method('getHashedKey')
            ->will($this->returnValue('3382a100edcb335c6af4efc1d5fb37b4ec264553'));

        $rest = $this->getMock('Elefunds_Communication_RestInterface');

        $rest->expects($this->once())
            ->method('put')
            ->with($this->equalTo('https://api.elefunds.de/donations/1,2,3,4/?clientId=1234&hashedKey=3382a100edcb335c6af4efc1d5fb37b4ec264553'))
            ->will($this->returnValue(json_encode(array('message' => 'Works like a charm!'))));

        $configuration->expects($this->once())
            ->method('getRestImplementation')
            ->will($this->returnValue($rest));

        $this->facade->setConfiguration($configuration);
        $result = $this->facade->completeDonations(array(1, 2, 3, 4));
        $this->assertSame($result, 'Works like a charm!');
    }

    /**
     * getReceiversCallsCorrectApiUrlAndThrowsErrorIfWrongCountryCodeIsSet
     *
     * @test
     * @expectedException Elefunds_Exception_ElefundsCommunicationException
     */
    public function getReceiversCallsCorrectApiUrlAndThrowsErrorIfWrongCountryCodeIsSet() {
        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
                      ->method('getApiUrl')
                      ->will($this->returnValue('https://api.elefunds.de'));

        $configuration->expects($this->once())
                       ->method('getClientId')
                       ->will($this->returnValue(1234));

        $configuration->expects($this->once())
                       ->method('getHashedKey')
                       ->will($this->returnValue('3382a100edcb335c6af4efc1d5fb37b4ec264553'));


        $rest = $this->getMock('Elefunds_Communication_RestInterface');

        $rest->expects($this->once())
              ->method('get')
              ->with($this->equalTo('https://api.elefunds.de/receivers/?clientId=1234&hashedKey=3382a100edcb335c6af4efc1d5fb37b4ec264553'))
              ->will($this->returnValue(json_encode(
                 array(
                    'receivers' => array(
                         array(
                            'de'    =>
                            array(
                                'id'            =>  1234,
                                'name'          =>  'TestReceiver',
                                'description'   =>  'Some description',
                                'images'        =>  array(
                                        'vertical'  =>  array(
                                            'small'     =>  'http://elefunds.de/image1.jpg',
                                            'medium'    =>  'http://elefunds.de/image2.jpg',
                                            'large'     =>  'http://elefunds.de/image3.jpg',
                                        ),
                                        'horizontal'  =>  array(
                                            'small'     =>  'http://elefunds.de/image4.jpg',
                                            'medium'    =>  'http://elefunds.de/image5.jpg',
                                            'large'     =>  'http://elefunds.de/image6.jpg',
                                        )
                                )
                            )
                        )
                    )
                )
        )));

        $configuration->expects($this->once())
                       ->method('getRestImplementation')
                       ->will($this->returnValue($rest));

        $configuration->expects($this->once())
                      ->method('getCountrycode')
                      ->will($this->returnValue('de'));

        $this->facade->setConfiguration($configuration);
        $this->facade->getReceivers();

    }

    /**
     * getTemplateCssFilesReturnsArray
     *
     * @test
     */
    public function getTemplateCssFilesReturnsArray() {

        $view = $this->getMock('Elefunds_View_ViewInterface');
        $view->expects($this->once())
             ->method('getCssFiles')
             ->will($this->returnValue(array('http://path/to/css.css')));

        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
                       ->method('getView')
                       ->will($this->returnValue($view));

        $this->facade->setConfiguration($configuration);
        $files = $this->facade->getTemplateCssFiles();

        $this->assertSame(array('http://path/to/css.css'), $files);
    }

    /**
     * renderTemplateReturnsStringFromViewIfViewIsSet
     *
     * @test
     */
    public function renderTemplateReturnsStringFromViewIfViewIsSet() {

        $view = $this->getMock('Elefunds_View_ViewInterface');
        $view->expects($this->once())
            ->method('render')
            ->will($this->returnValue('<p>Hello World!</p>'));

        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
            ->method('getView')
            ->will($this->returnValue($view));

        $this->facade->setConfiguration($configuration);
        $html = $this->facade->renderTemplate();

        $this->assertSame('<p>Hello World!</p>', $html);
    }

    /**
     * renderTemplateThrowsErrorIfNoViewIsGiven
     *
     * @test
     * @expectedException Elefunds_Exception_ElefundsException
     */
    public function renderTemplateThrowsErrorIfNoViewIsGiven() {
        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
            ->method('getView');

        $this->facade->setConfiguration($configuration);
        $this->facade->renderTemplate();
    }

    /**
     * getTemplateJavascriptFilesReturnsArray
     *
     * @test
     */
    public function getTemplateJavascriptFilesReturnsArray() {

        $view = $this->getMock('Elefunds_View_ViewInterface');
        $view->expects($this->once())
            ->method('getJavascriptFiles')
            ->will($this->returnValue(array('http://path/to/script.js')));

        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
            ->method('getView')
            ->will($this->returnValue($view));

        $this->facade->setConfiguration($configuration);
        $files = $this->facade->getTemplateJavascriptFiles();

        $this->assertSame(array('http://path/to/script.js'), $files);
    }

    /**
     * getTemplateCssFilesThrowsErrorIfNoViewGiven
     *
     * @test
     * @expectedException Elefunds_Exception_ElefundsException
     */
    public function getTemplateCssFilesThrowsErrorIfNoViewGiven() {
        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
                      ->method('getView');

        $this->facade->setConfiguration($configuration);
        $this->facade->getTemplateCssFiles();
    }

    /**
     * getTemplateJavascriptFilesThrowsErrorIfNoViewGiven
     *
     * @test
     * @expectedException Elefunds_Exception_ElefundsException
     */
    public function getTemplateJavascriptFilesThrowsErrorIfNoViewGiven() {
        $configuration = $this->getMock('Elefunds_Configuration_ConfigurationInterface');

        $configuration->expects($this->once())
            ->method('getView');

        $this->facade->setConfiguration($configuration);
        $this->facade->getTemplateJavascriptFiles();
    }

}
