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
 * Hooks for the shop template.
 *
 * @package    elefunds API PHP Library
 * @subpackage Template\Shop
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Library_Elefunds_Template_Shop_Hooks_ShopHooks {

    /**
     * Calculates the suggested roundup to be displayed in the shop.
     *
     * @param Library_Elefunds_View_ViewInterface $view
     * @param int $total in the smallest unit of currency
     * @return void
     */
    public static function calculateRoundup(Library_Elefunds_View_ViewInterface $view, $total) {

        //Change is the difference to the next full unit
        $change = 100 - ($total % 100);

        if($total < 2500) {
            $minRoundup = 100;
        } else {
            $minRoundup = 200;
        }

        if($total < 10000) {
            // Change is the roundup to the next full five unit
            $additionalChange = 500 - ($total % 500);
        } else {
            // Change is the roundup to the next full ten units
            $additionalChange = 1000 - ($total % 1000);
        }

        if($total < 50000) {
            $suggestedDonationAmount = $minRoundup + $change;
        } elseif($additionalChange < $minRoundup) {
            $suggestedDonationAmount = $minRoundup + $additionalChange + $change;
        } else {
            $suggestedDonationAmount = $additionalChange + $change;
        }

        $view->assign('suggestedDonationAmountCent', $suggestedDonationAmount);

        $assigns = $view->getAssignments();
        $donationAmountString = number_format($suggestedDonationAmount / 100, 2, $assigns['currencyDelimiter'], '');
        $roundedSum = number_format(($suggestedDonationAmount + $total) / 100, 2, $assigns['currencyDelimiter'], '');

        $view->assign('suggestedDonationAmount', $donationAmountString);
        $view->assign('roundedSum', $roundedSum);
    }

    /**
     * Calculates number of providers displayed and their sizes
     *
     * @param Library_Elefunds_View_ViewInterface $view
     * @param int $width
     * @return void
     */
    public static function calculatePadding(Library_Elefunds_View_ViewInterface $view, $width) {

        $receiversCount = (int) floor($width / 210);

        // Adjust receivers & override assignments
        $assigns = $view->getAssignments();
        $receivers = array_slice($assigns['receivers'], 0, $receiversCount);
        $view->assign('receivers', $receivers);

        if ($width % 210 == 0) {
            $paddingLeft = 8;
        } else {
            $paddingLeft = 9;
        }

        $paddingRight = 35;

        //subtract the borders between the buttons
        $padding = $width - ($receiversCount - 1);

        //subtract image width
        $padding = $padding - (166 * $receiversCount);

        //subtract min padding
        $padding = $padding - ($receiversCount * ($paddingLeft + $paddingRight));

        $paddingEach = $padding / $receiversCount;

        $mod = $padding % $receiversCount;

        if ($mod == 0) {
            $paddingEach = intval($paddingEach);
            $bonus = 0;
        } elseif ($mod == 1) {
            $paddingEach = floor($paddingEach);
            $bonus = 1;
        } else {
            $paddingEach = ceil($paddingEach);
            $bonus = $mod - $receiversCount;
        }
        
        //Padding of the receivers buttons
        $paddingLeft = $paddingLeft + ceil($paddingEach / 2);
        $paddingRight = $paddingRight + floor($paddingEach / 2);
        
        //Padding of the last receiver buttons
        $paddingLeftLast = $paddingLeft + floor($bonus / 2);
        $paddingRightLast = $paddingRight + ceil($bonus / 2);

        $view->assignMultiple(
            array(
                'paddingLeft' => $paddingLeft . 'px',
                'paddingRight' => $paddingRight . 'px',
                'paddingLeftLast' => $paddingLeftLast . 'px',
                'paddingRightLast' => $paddingRightLast . 'px',
            )
        );

    }

    /**
     * Hooks in after receivers are set and calculates the text that will be displayed
     * in the facebook share.
     *
     * @param Library_Elefunds_View_ViewInterface $view
     * @param array $receivers
     * @return void
     */
    public static function calculateReceiversText(Library_Elefunds_View_ViewInterface $view, array $receivers) {
        $assigns = $view->getAssignments();

        $shopname = $assigns['shopName'];

        $baseText = $assigns['IDonatedAndWantToTellAboutIt'];
        $baseText = str_replace('%shopname%', $shopname, $baseText);

        $num = count($receivers);
        if(count($receivers) === 1) {
            $baseText = str_replace('%receivers%', $receivers[0], $baseText);
        } else {
            $conjunctionText = '';
            $i = 0;
            foreach($receivers as $receiver) {

                if($i < $num - 2) {
                    $conjunctionText .= $receiver . ', ';
                } else if ($i === $num - 2) {
                    $conjunctionText .= $receiver . ' & ';
                } else {
                    $conjunctionText .= $receiver;
                }

                ++$i;
            }
            $baseText = str_replace('%receivers%', $conjunctionText, $baseText);
        }

        // Override with calculated text.
        $view->assign('IDonatedAndWantToTellAboutIt', $baseText);

    }
    
    /**
     * Encrypts the information for the facebook share.
     *
     * @param Library_Elefunds_View_ViewInterface $view
     * @param int $foreignId
     * @return void
     */
     public static function encryptFacebookShare(Library_Elefunds_View_ViewInterface $view, $foreignId) {

        require_once dirname(__FILE__) . '/../phpseclib/Math/BigInteger.php';
        require_once dirname(__FILE__) . '/../phpseclib/Crypt/Hash.php';
        require_once dirname(__FILE__) . '/../phpseclib/Crypt/Random.php';
        require_once dirname(__FILE__) . '/../phpseclib/Crypt/RSA.php';
        require_once dirname(__FILE__) . '/../phpseclib/Crypt/AES.php';

        $assigns = $view->getAssignments();

        $receivers = $assigns['donationReceivers'];

        $aes = new Crypt_Rijndael(2);
        $aes->setKey($assigns['hashedKey']);

        $foreignIdEncrypted = strtr(base64_encode($aes->encrypt($foreignId)), '+/=', '-_,');
        $view->assign('encryptedForeignId', $foreignIdEncrypted);

        $receiverIds = implode(',',array_keys($receivers));
        $receiverIdsEncrypted = strtr(base64_encode($aes->encrypt($receiverIds)), '+/=', '-_,');
        $view->assign('encryptedReceiverIds', $receiverIdsEncrypted);
    }

}
