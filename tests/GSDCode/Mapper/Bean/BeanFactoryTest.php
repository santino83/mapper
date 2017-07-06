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

namespace Tests\GSDCode\Mapper\Bean;

use GSDCode\Mapper\Bean\BeanFactoryInterface;
use GSDCode\Mapper\Bean\BeanFactory;
use PHPUnit\Framework\TestCase;

/**
 * Description of BeanFactoryTest
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class BeanFactoryTest extends TestCase
{
    
    /**
     *
     * @var BeanFactory
     */
    protected $factory;
    
    protected function setUp()
    {
        $this->factory = new BeanFactory();
    }
    
    public function testNoConstructor()
    {
        $className = BeanNoConstructor::class;        
        $instance = $this->factory->createBean($className);
        $this->assertNotNull($instance);
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Bean\BeanInstanceException
     * @expectedExceptionMessage Bean constructor is not public
     */
    public function testProtectedConstructor()
    {
        $className = BeanProtectedConstructor::class;        
        $this->factory->createBean($className);
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Bean\BeanInstanceException
     * @expectedExceptionMessage Bean constructor is not public
     */
    public function testPrivateConstructor()
    {
        $className = BeanPrivateConstructor::class;        
        $this->factory->createBean($className);
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Bean\BeanInstanceException
     * @expectedExceptionMessage Bean constructor has required parameters
     */
    public function testRequiredParamsConstructor()
    {
        $className = BeanRequiredParamsConstructor::class;        
        $this->factory->createBean($className);
    }
    
}

class BeanProtectedConstructor
{
    private $prop;
    
    protected function __construct()
    {
        $this->prop = "Test";
    }
    
}

class BeanPrivateConstructor
{
    private $prop;
    
    private function __construct()
    {
        $this->prop = "Test";
    }
    
}

class BeanNoConstructor
{
    private $prop;
    
    public function getProp()
    {
        return $this->prop;
    }

    public function setProp($prop)
    {
        $this->prop = $prop;
        return $this;
    }
    
}

class BeanRequiredParamsConstructor
{
    
    private $prop;
    
    public function __construct($prop)
    {
        $this->prop = $prop;
    }

    public function getProp()
    {
        return $this->prop;
    }

}


