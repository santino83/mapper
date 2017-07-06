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
 * Description of OtherBean
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class OtherBean
{
    
    /**
     *
     * @var string
     */
    private $otherProp1;
    
    /**
     *
     * @var string
     */
    private $otherProp2;
    
    /**
     *
     * @var string
     */
    private $otherProp3;
    
    /**
     * 
     * @return string
     */
    public function getOtherProp1()
    {
        return $this->otherProp1;
    }

    /**
     * 
     * @return string
     */
    public function getOtherProp2()
    {
        return $this->otherProp2;
    }

    /**
     * 
     * @return string
     */
    public function getOtherProp3()
    {
        return $this->otherProp3;
    }

    /**
     * 
     * @param string $otherProp1
     * @return $this
     */
    public function setOtherProp1($otherProp1)
    {
        $this->otherProp1 = $otherProp1;
        return $this;
    }

    /**
     * 
     * @param string $otherProp2
     * @return $this
     */
    public function setOtherProp2($otherProp2)
    {
        $this->otherProp2 = $otherProp2;
        return $this;
    }

    /**
     * 
     * @param string $otherProp3
     * @return $this
     */
    public function setOtherProp3($otherProp3)
    {
        $this->otherProp3 = $otherProp3;
        return $this;
    }
    
}
