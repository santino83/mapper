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
 * Description of TargetChild1
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class TargetChild1
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
     * @var array
     */
    private $childProp1;
    
    /**
     *
     * @var boolean
     */
    private $childProp2;
    
    /**
     *
     * @var string
     */
    private $childProp3;
    
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
     * @return array
     */
    public function getChildProp1()
    {
        return $this->childProp1;
    }

    /**
     * 
     * @return boolean
     */
    public function getChildProp2()
    {
        return $this->childProp2;
    }

    /**
     * 
     * @return string
     */
    public function getChildProp3()
    {
        return $this->childProp3;
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
     * @param array $childProp1
     * @return $this
     */
    public function setChildProp1($childProp1 = [])
    {
        $this->childProp1 = $childProp1;
        return $this;
    }

    /**
     * 
     * @param boolean $childProp2
     * @return $this
     */
    public function setChildProp2($childProp2)
    {
        $this->childProp2 = $childProp2;
        return $this;
    }

    /**
     * 
     * @param string $childProp3
     * @return $this
     */
    public function setChildProp3($childProp3)
    {
        $this->childProp3 = $childProp3;
        return $this;
    }
    
}
