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

use GSDCode\Mapper\Bean\BeanFactoryInterface;
use GSDCode\Mapper\Converter\ConverterFactory;
use GSDCode\Mapper\Converter\ConverterInterface;
use GSDCode\Mapper\Converter\ConverterRegistryInterface;
use GSDCode\Mapper\Converter\MappingConverter;
use Tests\GSDCode\Mapper\BaseTestCase;

/**
 * Description of ConverterFactoryTest
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class ConverterFactoryTest extends BaseTestCase
{
    
    private $blankConfig = ['converters' => [], 'mappings' => [], 'settings' => ['date_format' => 'Y-m-d H:i:s']];
    
    private $config = ['converters' => [], 'settings' => ['date_format' => 'Y-m-d H:i:s'], 'mappings' => [
       ['classA' => "My\Classes\A", 'classB' => "My\Classes\B", 'fields' => []] 
    ]];
    
    public function testCreate()
    {
        $factory = new ConverterFactory($this->blankConfig);
        $this->assertNull($factory->getBeanFactory());
        $this->assertNull($factory->getConverterRegistry());
        $this->assertNull($factory->getConverterRegistryLoader());
        $this->assertNull($factory->getDefaultConverter());
        $this->assertNull($factory->getTypeResolver());
    }
    
    public function testSetUpDefaults()
    {
        $factory = new ConverterFactory($this->blankConfig);
        $factory->setUp();
        $this->assertNotNull($factory->getBeanFactory());
        $this->assertNotNull($factory->getConverterRegistry());
        $this->assertNotNull($factory->getConverterRegistryLoader());
        $this->assertNotNull($factory->getDefaultConverter());
        $this->assertNotNull($factory->getTypeResolver());
    }
    
    /**
     * @expectedException \BadMethodCallException
     */
    public function testSetAfterSetUp()
    {
        $factory = new ConverterFactory($this->blankConfig);
        $factory->setUp();
        $factory->setBeanFactory($this->createMock(BeanFactoryInterface::class));
    }
    
    public function testGetDefaultConverter()
    {
        $factory = new ConverterFactory($this->config);
        $converter = $factory->getConverter(new \My\Classes\A(), "My\Classes\B");
        $this->assertNotNull($converter);
        $this->assertEquals(MappingConverter::class, get_class($converter));
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Converter\ConverterNotFoundException
     */
    public function testNotFound()
    {
        $factory = new ConverterFactory($this->blankConfig);
        $factory->getConverter(new \My\Classes\A(), "My\Classes\B");
    }
    
    public function testNotDefault_no_mappings()
    {
        $factory = new ConverterFactory($this->blankConfig);
        $factory->setConverterRegistry($this->getConverterRegistryMock());
        
        $converter = $factory->getConverter(new \My\Classes\A(), "My\Classes\B");
        
        $this->assertNotNull($converter);
        $this->assertFalse($converter instanceof MappingConverter);
    }
    
    public function testNotDefault_mappings()
    {
        $factory = new ConverterFactory($this->config);
        $factory->setConverterRegistry($this->getConverterRegistryMock());
        
        $converter = $factory->getConverter(new \My\Classes\A(), "My\Classes\B");
        
        $this->assertNotNull($converter);
        $this->assertFalse($converter instanceof MappingConverter);
    }
    
    private function getConverterRegistryMock()
    {
        $mock = $this->createMock(ConverterRegistryInterface::class);
        $mock->expects($this->any())
                ->method("search")
                ->willReturn($this->createMock(ConverterInterface::class));
        
        return $mock;
    }
    
}


namespace My\Classes;

class A{}

interface B{}