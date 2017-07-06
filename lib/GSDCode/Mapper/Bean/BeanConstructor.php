<?php

/**
 * MIT License
 *
 * Copyright (c) 2017 Giorgio Maria Santini
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace GSDCode\Mapper\Bean;

/**
 * Description of BeanConstructor
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
final class BeanConstructor
{
 
    /**
     *
     * @var \ReflectionClass
     */
    private $class;
    
    /**
     *
     * @var \ReflectionMethod
     */
    private $constructor;
    
    /**
     *
     * @var boolean
     */
    private $isValid = false;
    
    /**
     *
     * @var string
     */
    private $errorMessage;
    
    /**
     * 
     * @param string $className the full bean class name
     */
    public function __construct($className)
    {
        $this->class = new \ReflectionClass($className);
        $this->constructor = $this->class->getConstructor();
        $this->validate();
    }

    /**
     * 
     * @return mixed a new bean instance
     * @throws BeanInstanceException
     */
    public function newInstance()
    {
        try{
            return $this->class->newInstanceArgs();
        } catch (\Exception $ex) {
            throw new BeanInstanceException("Unable to instantiate bean ".$this->class->getName(), $ex);
        }
    }
    
    /**
     * Check if bean is auto-instantiable
     * 
     * @return boolean
     */
    public function isValid()
    {
        return $this->isValid;
    }
    
    /**
     * 
     * @return string the error message when bean is not auto-instantiable
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
    
    private function validate()
    {
        if(($this->isValid = $this->constructor === null))
        {
            return;
        }
        
        $message = "Unable to instantiate bean ".$this->class->getName().": ";
        
        if(!$this->constructor->isPublic())
        {
            $this->errorMessage = $message."\nBean constructor is not public";
        }
        
        if($this->constructor->getNumberOfRequiredParameters()>0)
        {
            $this->errorMessage = ($this->errorMessage ? $this->errorMessage : $message)."\nBean constructor has required parameters";
        }
        
        $this->isValid = strlen($this->errorMessage) <= 0;
    }
    
}
