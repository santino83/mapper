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

namespace Tests\Data\Target;

/**
 * Description of TargetChild2
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class TargetChild2
{
    
    /**
     *
     * @var integer
     */
    private $prop1;
    
    /**
     *
     * @var string
     */
    private $prop2;
    
    /**
     *
     * @var \DateTime
     */
    private $prop3;
    
    /**
     *
     * @var string
     */
    private $prop4;
    
    /**
     *
     * @var \Tests\Data\Bean\OtherBean
     */
    private $prop5;
    
    /**
     *
     * @var \Tests\Data\Bean\OtherBean
     */
    private $prop6;
    
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
     * @return \DateTime
     */
    public function getProp3()
    {
        return $this->prop3;
    }

    /**
     * 
     * @return string
     */
    public function getProp4()
    {
        return $this->prop4;
    }

    /**
     * 
     * @return \Tests\Data\Bean\OtherBean
     */
    public function getProp5()
    {
        return $this->prop5;
    }

    /**
     * 
     * @return \Tests\Data\Bean\OtherBean
     */
    public function getProp6()
    {
        return $this->prop6;
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

    /**
     * 
     * @param \DateTime $prop3
     * @return $this
     */
    public function setProp3(\DateTime $prop3)
    {
        $this->prop3 = $prop3;
        return $this;
    }

    /**
     * 
     * @param string $prop4
     * @return $this
     */
    public function setProp4($prop4)
    {
        $this->prop4 = $prop4;
        return $this;
    }

    /**
     * 
     * @param \Tests\Data\Bean\OtherBean $prop5
     * @return $this
     */
    public function setProp5(\Tests\Data\Bean\OtherBean $prop5)
    {
        $this->prop5 = $prop5;
        return $this;
    }

    /**
     * 
     * @param \Tests\Data\Bean\OtherBean $prop6
     * @return $this
     */
    public function setProp6(\Tests\Data\Bean\OtherBean $prop6)
    {
        $this->prop6 = $prop6;
        return $this;
    }
    
}
