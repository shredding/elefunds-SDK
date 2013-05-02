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
 * Elefunds Facade Interface
 *
 * @package    elefunds API PHP Library
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.3.0
 */
interface Elefunds_FacadeInterface {


    /**
     * Returns a brand new donation.
     *
     * @return Elefunds_Model_DonationInterface
     */
    public function createDonation();

    /**
     * Returns a brand new Receiver.
     *
     * @return Elefunds_Model_ReceiverInterface
     */
    public function createReceiver();

    /**
     * Sets the configuration.
     *
     * @param Elefunds_Configuration_ConfigurationInterface $configuration
     * @return Elefunds_Facade
     */
    public function setConfiguration(Elefunds_Configuration_ConfigurationInterface $configuration);

    /**
     * Returns the configuration instance.
     *
     * @return Elefunds_Configuration_ConfigurationInterface
     */
    public function getConfiguration();
    /**
     * Returns the available receivers
     *
     * @throws Elefunds_Exception_ElefundsCommunicationException
     * @return array
     */
    public function getReceivers();

    /**
     * Adds a single Donation to the API.
     *
     * This is just a wrapper for the addDonations method.
     *
     * @param Elefunds_Model_DonationInterface
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string Message returned from the API
     */
    public function addDonation(Elefunds_Model_DonationInterface $donation);
    /**
     * Cancels a single Donation at the API.
     *
     * This is just a wrapper for the cancelDonations method.
     *
     * @param int $donationId
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string Message returned from the API
     */
    public function cancelDonation($donationId);

    /**
     * Completes a single Donation in the API.
     *
     * This is just a wrapper for the completeDonations method.
     *
     * @param int $donationId
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string Message returned from the API
     */
    public function completeDonation($donationId);

    /**
     * Sends an array of donations to the API.
     *
     * @param array $donations
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string Message returned from the API
     */
    public function addDonations(array $donations);

    /**
     * Cancels an array of donation from the API.
     *
     * @param array $donationIds with ids
     *
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string Message returned from the API
     */
    public function cancelDonations(array $donationIds);

    /**
     * Completes an array of Donations in the API.
     *
     * @param array $donationIds with ids
     *
     * @throws Elefunds_Exception_ElefundsCommunicationException if connection or authentication fails or retrieved http code is not 200
     * @return string Message returned from the API
     */
    public function completeDonations(array $donationIds);

    /**
     * Renders the template.
     *
     * @param string $templateName
     * @param bool $givenTemplateNameIsAbsolutePath
     * @throws Elefunds_Exception_ElefundsException
     * @return string The rendered HTML Snippet
     */
    public function renderTemplate($templateName = 'View', $givenTemplateNameIsAbsolutePath = FALSE);

    /**
     * Returns the CSS Files required by the template.
     *
     * @throws Elefunds_Exception_ElefundsException if no template is configured
     * @return array with css files (path relative to this library)
     */
    public function getTemplateCssFiles();

    /**
     * Returns the Javascript Files required by the template.
     *
     * @throws Elefunds_Exception_ElefundsException if no template is configured
     * @return array with javascript files (path relative to this library)
     */
    public function getTemplateJavascriptFiles();

}
