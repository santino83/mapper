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
 * Description of ChildBean1
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class ChildBean1 extends BaseBean
{
    
    /**
     *
     * @var array
     */
    private $childProp1 = [];
    
    /**
     *
     * @var boolean
     */
    protected $childProp2 = false;
    
    /**
     *
     * @var string
     */
    public $childProp3 = "test child";
    
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
    
}
