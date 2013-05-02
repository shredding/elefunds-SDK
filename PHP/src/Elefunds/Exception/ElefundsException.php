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
 * Elefunds custom error class.
 * 
 * This error is thrown if anything fatal goes wrong that needs a developer to fix it.
 * 
 * @package    elefunds API PHP Library
 * @subpackage Exception
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Exception_ElefundsException extends Exception {
         
    /**
     * An elefunds error.
     * 
     * @param string $message The error message
     * @param int $code An integer of the code, defined as the timestamp at the time where the error was defined
     * @param array $additionalInformation
     */
    public function __construct($message, $code = 0, array $additionalInformation=array()) {

        $this->additionalInformation = $additionalInformation;
        parent::__construct($message, $code);
        
    }
    
    
    /**
     * Add additional information.
     * 
     * @param array $additionalInformation
     * @return Elefunds_Exception_ElefundsException
     */
    public function addAdditionalInformation(array $additionalInformation) {
       $this->additionalInformation = array_merge($this->additionalInformation, $additionalInformation);
       return $this;
    }

        
    /**
     * Additional Information
     * 
     * Some Exceptions are upgraded with additional information. In this case, refer to
     * the exception message to find out about their nature.
     * 
     * @return array
     */
    public function getAdditionalInformation() {
        return $this->additionalInformation;   
    }

    /**
     * Transforms the error to string.
     * 
     * All additional information are added if they are of type string.
     * 
     * @return string
     */
    public function __toString() {
        
        $additionalInformation = '';

        foreach ($this->additionalInformation as $key => $value) {
            if (is_string($value)) {
                  $additionalInformation .= (string)$key . ' = ' . $value . PHP_EOL;   
            }
        }
        
        $string = __CLASS__ . ' [Errorcode: ' . $this->code . '] : ' . $this->message . PHP_EOL;
        
        if (strlen($additionalInformation) > 0) {
            $string .= 'Additional information:' . PHP_EOL . $additionalInformation;
        }
        
        return $string;
               
    }
}