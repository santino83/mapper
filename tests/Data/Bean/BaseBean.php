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

namespace Tests\Data\Bean;

/**
 * Description of BaseBean
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class BaseBean
{
    
    /**
     *
     * @var integer
     */
    private $prop1 = 0;
    
    /**
     *
     * @var string
     */
    protected $prop2 = "test";
    
    /**
     *
     * @var \DateTime
     */
    public $prop3;
    
    public function __construct()
    {
        $this->prop3 = new \DateTime();
    }
    
    /**
     * 
     * @return integer
     */
    public function getProp1()
    {
        return $this->prop1;
    }

    /**
     * 
     * @return string
     */
    public function getProp2()
    {
        return $this->prop2;
    }

    /**
     * 
     * @param integer $prop1
     * @return $this
     */
    public function setProp1($prop1)
    {
        $this->prop1 = $prop1;
        return $this;
    }

    /**
     * 
     * @param string $prop2
     * @return $this
     */
    public function setProp2($prop2)
    {
        $this->prop2 = $prop2;
        return $this;
    }
    
}
