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
 * Elefunds Receiver
 *
 * @package    elefunds API PHP Library
 * @subpackage Model
 * @author     Christian Peters <christian@elefunds.de>
 * @copyright  2012 elefunds GmbH <hello@elefunds.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.elefunds.de
 * @since      File available since Release 1.0.0
 */
class Elefunds_Model_Receiver implements Elefunds_Model_ReceiverInterface {
    
     const IMAGE_ORIENTATION_VERTICAL = 'vertical';
     const IMAGE_ORIENTATION_HORIZONTAL = 'horizontal';
     const IMAGE_SIZE_SMALL = 'small';
     const IMAGE_SIZE_MEDIUM = 'medium';
     const IMAGE_SIZE_LARGE = 'large';
     
     /**
      * @var int
      */
      protected $id;
      
      /**
       * @var string
       */
      protected $name;
      
      /**
       * @var string
       */
      protected $description;
      
     /**
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
      * @var array
      */
      protected $images;

     /**
      * @var DateTime
      */
      protected $validTime;

    /**
     * Initializes the indices of the images array.
     */
    public function __construct() {
          
          $this->images = array(
                'vertical'  => array(
                    'small'      =>     '',
                    'medium'     =>     '',
                    'large'      =>     ''
                ),
                'horizontal'  => array(
                    'small'      =>     '',
                    'medium'     =>     '',
                    'large'      =>     ''
                )
          );

      }
      
     /**
      * Sets the id of the receiver.
      * 
      * @param int
      * @throws InvalidArgumentException if given type is not int
      * @return Elefunds_Model_ReceiverInterface
      */
      public function setId($id) {
          if (is_int($id) && $id > 0) {
            $this->id = $id;   
          } else {
            throw new InvalidArgumentException('Given value must be a positive integer.', 1347734104);
          }
          return $this;
      }
      
     
     /**
      * Returns the id of the receiver. 
      *
      * @return int
      */
     public function getId() {
         return $this->id;
     }
     
     /**
      * Sets the name of the receiver.
      * 
      * @param string $name
      * @throws InvalidArgumentException if given type is not string
      * @return Elefunds_Model_ReceiverInterface
      */
     public function setName($name) {
         if (is_string($name)) {
             $this->name = $name;
         } else {
             throw new InvalidArgumentException('Given value must be of type string.', 1347734105);
         }
         
         return $this;   
     }
     
     /**
      * Returns the name the receiver.
      * 
      * @return string
      */
     public function getName() {
         return $this->name;
     }
     
     /**
      * Sets the description of the receiver.
      * 
      * @param string $description
      * @throws InvalidArgumentException if given type is not string
      * @return Elefunds_Model_ReceiverInterface
      */
     public function setDescription($description) {
         if (is_string($description)) {
             $this->description = $description;
         } else {
             throw new InvalidArgumentException('Given value must be of type string.', 1347734105);
         }
         
         return $this;   
     }
     
     /**
      * Returns the description the receiver.
      * 
      * @return string
      */
     public function getDescription() {
         return $this->description;
     }
    
     /**
      * Adds or overwrites an image to the array.
      * 
      * @param string $url Url of the image
      * @param string $orientation must be either 'horizontal' or 'vertical'
      * @param string $size either 'small', 'medium' or 'large'
      * @throws InvalidArgumentException if url, orientation or size are not in correct format#
      * @return Elefunds_Model_ReceiverInterface
      */
     public function addImage($url, $orientation, $size) {

         // @todo remove once 5.3.3 or higher is supported php version
         // Fix for PHP <= 5.3.2
         // https://bugs.php.net/bug.php?id=51192
         $tempUrl = str_replace('-', '', $url);

         $validUrl = filter_var($tempUrl, FILTER_VALIDATE_URL) !== FALSE;

         if ($validUrl && isset($this->images[$orientation]) && isset($this->images[$orientation][$size])) {
             $this->images[$orientation][$size] = $url;
         } else {
             throw new InvalidArgumentException('URL, orientation and size have to be valid. Please refer to documentation for permitted input.', 1347872590);
         }
         
         return $this;
     }
     
     /**
      * Returns an image.
      * 
      * @param string $orientation either 'vertical' or 'horizontal'
      * @param string $size either 'small', 'medium' or 'large'
      * @throws InvalidArgumentException if orientation or size are not in correct format
      * @return string URL to the image
      */
     public function getImage($orientation, $size) {
         if (isset($this->images[$orientation]) && isset($this->images[$orientation][$size])) {
            return $this->images[$orientation][$size];
         } else {
             throw new InvalidArgumentException('Orientation and size have to be valid. Please refer to documentation for permitted input.', 1347872591);
         }
     }
     
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
     public function setImages(array $images) {
         foreach ($images as $orientation => $sizes) {
             if (is_array($sizes)) {
                 foreach ($sizes as $size => $url) {
                     $this->addImage($url, $orientation, $size);
                 }        
             } else {
                throw new InvalidArgumentException('Array is not of correct multidimensional structure.', 1347873638);
             }
         }
         return $this;
     }
     
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
     public function getImages() {
         return $this->images;
     }

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
    public function isValid() {
        return new DateTime() < $this->validTime;
    }

    /**
     * Returns the DateTime on which this receiver
     * expires.
     *
     * @return DateTime
     */
    public function getValidTime() {
        return $this->validTime;
    }

    /**
     * Setter for the time until which the receiver is
     * valid.
     *
     * @param DateTime $validTime
     *
     * @return Elefunds_Model_ReceiverInterface
     */
    public function setValidTime(DateTime $validTime) {

        $this->validTime = $validTime;
        return $this;

    }

}
    