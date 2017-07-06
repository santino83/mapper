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

namespace Tests\GSDCode\Mapper\Converter;

use GSDCode\Mapper\Converter\AbstractConverter;
use GSDCode\Mapper\Converter\ConverterRegistry;
use GSDCode\Mapper\Converter\ConverterRegistryInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Description of ConverterRegistryTest
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class ConverterRegistryTest extends TestCase
{
    
    /**
     *
     * @var ConverterRegistryInterface
     */
    private $registry;
    
    protected function setUp()
    {
        $this->registry = new ConverterRegistry();
        $this->registry->add("test_1", $this->getConverter("S_1", "T_1"))
                ->add("test_2", $this->getConverter("S_2", "T_2"));
    }
    
    public function testGet()
    {
        $converter = $this->registry->get("test_1");
        $this->assertNotNull($converter);
        $this->assertTrue($converter->supports("S_1", "T_1"));
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Converter\ConverterNotFoundException
     */
    public function testGetNotExistent()
    {
        $this->registry->get("not_existent");
    }
    
    public function testSearch()
    {
        $this->assertNotNull($this->registry->search("S_1", "T_1"));
        $this->assertNotNull($this->registry->search("T_1", "S_1"));
        $this->assertNotNull($this->registry->search("S_2", "T_2"));
        $this->assertNotNull($this->registry->search("T_2", "S_2"));
        $this->assertNull($this->registry->search("S_3", "T_3"));
        $this->assertNull($this->registry->search("T_3", "S_3"));
    }
    
    public function testAdd()
    {
        $this->assertFalse($this->registry->has("test_3"));
        $this->assertNull($this->registry->search("S_3", "T_3"));
        $this->registry->add("test_3", $this->getConverter("S_3", "T_3"));
        $this->assertTrue($this->registry->has("test_3"));
        $this->assertNotNull($this->registry->search("S_3", "T_3"));
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Converter\ConverterAlreadyExistsException
     */
    public function testAddConverterExist()
    {
        $this->registry->add("test_1", $this->getConverter("A_CLASS_BIS"));
    }
    
    public function testRemove()
    {
        $this->assertTrue($this->registry->has("test_1"));
        $this->assertNotNull($this->registry->search("S_1", "T_1"));
        $this->registry->remove("test_1");
        $this->assertFalse($this->registry->has("test_1"));
        $this->assertNull($this->registry->search("S_1", "T_1"));
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Converter\ConverterNotFoundException
     */
    public function testRemoveNotExistent()
    {
        $this->registry->remove("not_existent");
    }
    
    public function testHas()
    {
        $this->assertTrue($this->registry->has("test_1"));
        $this->assertTrue($this->registry->has("test_2"));
        $this->assertFalse($this->registry->has("ANOTHER"));
    }
    
    /**
     * 
     * @param string $classA
     * @param string $classB
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getConverter($classA = "A_CLASS", $classB = "B_CLASS")
    {
        $converter = $this->getMockBuilder(AbstractConverter::class)
                ->setConstructorArgs([$classA, $classB])
                ->enableProxyingToOriginalMethods()
                ->getMockForAbstractClass();
                
        return $converter;
    }
    
}
