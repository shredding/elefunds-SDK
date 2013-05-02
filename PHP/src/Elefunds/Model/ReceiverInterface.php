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
 * Elefunds Receiver Interface
 * 
 * @package    elefunds API PHP Library
 * @subpackage Model
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
interface Elefunds_Model_ReceiverInterface  {
     
     /**
      * Sets the ID of the receiver.
      * 
      * @param int
      * @throws InvalidArgumentException if given type is not int
      * @return Elefunds_Model_ReceiverInterface
      */
      public function setId($id);
     
     /**
      * Returns the ID of the receiver. 
      *
      * @return int
      */
     public function getId();

     /**
      * Sets the name of the receiver.
      * 
      * @param string $name
      * @throws InvalidArgumentException if given type is not string
      * @return Elefunds_Model_ReceiverInterface
      */
     public function setName($name);
     /**
      * Returns the name the receiver.
      * 
      * @return string
      */
     public function getName();
     
     /**
      * Sets the description of the receiver.
      * 
      * @param int
      * @throws InvalidArgumentException if given type is not string
      * @return Elefunds_Model_ReceiverInterface
      */
     public function setDescription($description);
     
     /**
      * Returns the description the receiver.
      * 
      * @return string
      */
     public function getDescription();
     
     /**
      * Adds or overwrites an image to the array.
      * 
      * @param string $url url of the image
      * @param string $orientation must be either 'horizontal' or 'vertical'
      * @param string $size either 'small', 'medium' or 'large'
      * @throws InvalidArgumentException if url, orientation or size are not in correct format#
      * @return Elefunds_Model_ReceiverInterface
      */
     public function addImage($url, $orientation, $size);
     
     /**
      * Returns an image.
      * 
      * @param string $orientation either 'vertical' or 'horizontal'
      * @param string $size either 'small', 'medium' or 'large'
      * @throws InvalidArgumentException if orientation or size are not in correct format
      * @return string URL to the image
      */
     public function getImage($orientation, $size);
     
     /**
      * Sets an array with all images.
      * 
      * The structure of the array must be like this:
      * 
      * <code>
      * $images = array(
      *     'vertical'  =>  array(    
      *         'small' =>  'http://url.of/the/image'            
      *         'medium' =>  'http://url.of/the/image'            
      *         'large' =>  'http://url.of/the/image'            
      *      )
      *      'horizontal'  =>  array(    
      *         'small' =>  'http://url.of/the/image'            
      *         'medium' =>  'http://url.of/the/image'            
      *         'large' =>  'http://url.of/the/image'            
      *      )
      * )
      * </code>
      * 
      * @param array $images
      * @throws InvalidArgumentException if array is not of correct structure
      * @return Elefunds_Model_ReceiverInterface
      */
     public function setImages(array $images);
     
     /**
      * Returns an array with all images.
      * 
      * The structure of the array will be like this:
      * 
      * <code>
      * $images = array(
      *     'vertical'  =>  array(    
      *         'small' =>  'http://url.of/the/image'            
      *         'medium' =>  'http://url.of/the/image'            
      *         'large' =>  'http://url.of/the/image'            
      *      )
      *      'horizontal'  =>  array(    
      *         'small' =>  'http://url.of/the/image'            
      *         'medium' =>  'http://url.of/the/image'            
      *         'large' =>  'http://url.of/the/image'            
      *      )
      * )
      * </code>
      * 
      * @return array
      */
     public function getImages();

     /**
      * Checks whether the receiver is valid.
      *
      * When you are retrieving the receiver from the API
      * it should always be valid. However, if you are
      * persisting receivers or cache them, you can check
      * if you need to refresh with this method.
      *
      * @return bool
      */
     public function isValid();

    /**
     * Returns the DateTime on which this receiver
     * expires.
     *
     * @return DateTime
     */
    public function getValidTime();

    /**
     * Setter for the time until which the receiver is
     * valid.
     *
     * @param DateTime $validTime
     *
     * @return Elefunds_Model_ReceiverInterface
     */
    public function setValidTime(DateTime $validTime);
    
}