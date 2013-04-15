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
 * Helper for request verification.
 *
 * @package    elefunds API PHP Library
 * @subpackage Template\Shop
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.2.1
 */

class Elefunds_Template_Shop_Helper_RequestHelper {

    protected $request;

    /**
     * Accepts the request as array.
     *
     * @param array $request
     */
    public function __construct(array $request = array()) {
        $this->request = $request;
    }

    public function setRequest(array $request) {
        $this->request = $request;
    }

    /**
     * Checks if the given request contains the basic information for the elefunds module and checks
     * if the module is active. The module is active and valid if the following parameters are set:
     *
     * elefunds_agree: if the module is checked
     * elefunds_donation_cent: the donation amount in cent
     * elefunds_receivers[]: list of receivers the donation goes to
     * elefunds_receivers: alternative - comma separated list of receivers as a string (in case the value is read from a hidden field)
     *
     * @return bool
     */
    public function isActiveAndValid() {

        $agreedToElefunds = isset($this->request['elefunds_agree']) && $this->request['elefunds_agree'] !== 'false';
        $hasDonation = isset($this->request['elefunds_donation_cent']) && ctype_digit($this->request['elefunds_donation_cent']) && $this->request['elefunds_donation_cent'] > 0;
        $hasValidReceivers = FALSE;

        if (isset($this->request['elefunds_receivers'])) {
            if (is_array($this->request['elefunds_receivers'])) {
                $hasValidReceivers = count($this->request['elefunds_receivers']) > 0;
            } else {
                $hasValidReceivers = $this->request['elefunds_receivers'] !== '';
            }
        }

        return $agreedToElefunds && $hasDonation && $hasValidReceivers;
    }

    /**
     * Returns the roundup of the request.
     *
     * @return int
     */
    public function getRoundUp() {
        return (int)$this->request['elefunds_donation_cent'];
    }

    /**
     * Returns the roundup as float.
     *
     * @return float
     */
    public function getRoundUpAsFloat() {
        return number_format($this->getRoundUp() / 100, 2);
    }

    /**
     * Returns the receivers id's of the request, mapped to int.
     *
     * @return array
     */
    public function getReceiverIds() {
        if (is_array($this->request['elefunds_receivers'])) {
            return array_map(function($x) { return (int)$x; }, $this->request['elefunds_receivers']);
        } else {
            return array_map(function($x) { return (int)$x; }, explode(',', $this->request['elefunds_receivers']));
        }
    }

    /**
     * Returns the suggested roundup in Cent as integer.
     *
     * @return int
     */
    public function getSuggestedRoundUp() {
        if (isset($this->request['elefunds_suggested_round_up_cent']) && ctype_digit($this->request['elefunds_suggested_round_up_cent'])) {
            return (int)$this->request['elefunds_suggested_round_up_cent'];
        }
        return 0;
    }

    /**
     * Returns true if the customer requested a donation receipt.
     *
     * @return bool
     */
    public function isDonationReceiptRequested() {
        return isset($this->request['elefunds_receipt']) && $this->request['elefunds_receipt'] !== 'false';
    }

}