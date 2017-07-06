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
use GSDCode\Mapper\Converter\ConverterRegistryLoader;
use GSDCode\Mapper\Converter\ConverterRegistryLoaderInterface;
use PHPUnit\Framework\TestCase;

/**
 * Description of ConverterRegistryLoaderTest
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class ConverterRegistryLoaderTest extends TestCase
{
    
    /**
     *
     * @var ConverterRegistryLoaderInterface
     */
    private $loader;
    
    /**
     *
     * @var ConverterRegistryInterface
     */
    private $registry;
    
    protected function setUp()
    {
        $this->loader = new ConverterRegistryLoader();
        $this->registry = new ConverterRegistry();
    }

    public function testLoad()
    {
        $config = ['converters' => [FooConverter::class, BarConverter::class],'settings'=>['date_format'=>'Y-m-d H:i:s'],'mappings'=>[]];
        $this->loader->load($config, $this->registry);
        
        $this->assertTrue($this->registry->has(FooConverter::class));
        $this->assertTrue($this->registry->has(BarConverter::class));
    }
    
    public function testLoadDefaults()
    {
        $this->loader->load(['converters'=>[],'settings'=>['date_format'=>'Y-m-d H:i:s'],'mappings'=>[]], $this->registry);
        
        $ids = [
        \GSDCode\Mapper\Converter\BuiltIn\StringToBoolConverter::class,
        \GSDCode\Mapper\Converter\BuiltIn\StringToIntConverter::class,
        \GSDCode\Mapper\Converter\BuiltIn\IntToBoolConverter::class,
        \GSDCode\Mapper\Converter\BuiltIn\DateTimeToStringConverter::class,
        \GSDCode\Mapper\Converter\BuiltIn\DateTimeToIntConverter::class
        ];
        
        foreach($ids as $id)
        {
            $this->assertTrue($this->registry->has($id));
        }
        
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Converter\ConverterNotFoundException
     */
    public function testLoadNotExistent()
    {
        $config = ['converters' => ["NOT_EXISTENT"]];
        $this->loader->load($config, $this->registry);
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Converter\ConverterException
     */
    public function testLoadWrong()
    {
        $config = ['converters' => [ConverterRegistryLoaderTest::class]];
        $this->loader->load($config, $this->registry);
    }
    
}


class FooConverter extends AbstractConverter
{
    
    public function __construct()
    {
        parent::__construct("A_CLASS_FOO", "B_CLASS_FOO");
    }
    
    protected function convertFrom($a, $classB)
    {
        //do nothing
    }

    protected function convertTo($b, $classA)
    {
        //do nothing
    }

}

class BarConverter extends AbstractConverter
{
    
    public function __construct()
    {
        parent::__construct("A_CLASS_BAR", "B_CLASS_BAR");
    }
    
    protected function convertFrom($a, $classB)
    {
        //do nothing
    }

    protected function convertTo($b, $classA)
    {
        //do nothing
    }

}