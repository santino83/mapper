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

namespace Tests\GSDCode\Mapper;

use GSDCode\Mapper\Mapper;
use GSDCode\Mapper\ObjectMapperBuilder;

/**
 * Description of ObjectMapperTest
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class ObjectMapperTest extends BaseTestCase
{
    
    /**
     *
     * @var Mapper
     */
    private $mapper;
    
    private $baseConfig = ['settings' => ['date_format'=>'Y-m-d'], 'converters'=>[],'mappings'=>[]];
    private $beanConfig = ['settings' => ['date_format'=>'Y-m-d'], 'converters'=>[],'mappings'=>[
        ['classA'=>Bean1::class,'classB'=>Test1::class,'fields'=>[]],
        ['classA'=>Bean2::class,'classB'=>Test2::class,'fields'=>[]],
        ['classA'=>DeepBean1::class,'classB'=>DeepTest1::class,'fields'=>[]],
        ['classA'=>DeepBean2::class,'classB'=>DeepTest2::class,'fields'=>[]]
    ]];
    
    private $beanConfigCustom = ['settings' => ['date_format'=>'Y-m-d'], 'converters'=>[CustomBeanConverter::class],'mappings'=>[]];
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->mapper = (new ObjectMapperBuilder())
                ->withPropertyAccessor($this->propertyAccessor)
                ->withPropertyInfoExtractor($this->propertyInfo)
                ->build($this->baseConfig);
    }
    
    public function testMapStringToInt()
    {
        $source = "100";
        $expected = 100;
        $destinationClass = "int";
        
        $this->assertEquals($expected, $this->mapper->map($source, $destinationClass));
    }
    
    public function testMapIntToString()
    {
        $source = 100;
        $expected = "100";
        $destinationClass = "string";
        
        $this->assertEquals($expected, strval($this->mapper->map($source, $destinationClass)));
    }
    
    public function testMapStringToBool()
    {
        $source = "TRUE";
        $expected = true;
        $destinationClass = "bool";
        
        $this->assertEquals($expected, $this->mapper->map($source, $destinationClass));
    }
    
    public function testMapBoolToString()
    {
        $source = true;
        $expected = "TRUE";
        $destinationClass = "string";
        
        $this->assertEquals($expected, $this->mapper->map($source, $destinationClass));
    }
    
    public function testMapIntToBool()
    {
        $source = 100;
        $expected = false;
        $destinationClass = "bool";
        
        $this->assertEquals($expected, $this->mapper->map($source, $destinationClass));
    }
    
    public function testMapBoolToInt()
    {
        $source = true;
        $expected = 1;
        $destinationClass = "int";
        
        $this->assertEquals($expected, $this->mapper->map($source, $destinationClass));
    }
    
    public function testMapDateTimeToString()
    {
        $source = new \DateTime("2016-01-01 15:30:45");
        $expected = "2016-01-01";
        $destinationClass = "string";
        
        $this->assertEquals($expected, $this->mapper->map($source, $destinationClass));
    }
    
    public function testMapStringToDateTime()
    {
        $source = "2016-01-01";
        $expected = \DateTime::createFromFormat('Y-m-d', $source);
        $destinationClass = "DateTime";
        
        $this->assertEquals($expected, $this->mapper->map($source, $destinationClass));
    }
    
    public function testMapDateTimeToInt()
    {
        $source = new \DateTime();
        $expected = $source->getTimestamp();
        $destinationClass = "int";
        
        $this->assertEquals($expected, $this->mapper->map($source, $destinationClass));
    }
    
    public function testMapIntToDateTime()
    {
        $time = new \DateTime();
        $source = $time->getTimestamp();
        $expected = $time;
        $destinationClass = "DateTime";
        
        $this->assertEquals($expected, $this->mapper->map($source, $destinationClass));
    }
    
    public function testMapArrayStringToArrayBool()
    {
        $source = ["TRUE","FALSE","1","0"];
        $expected = [true, false, true, false];
        $destinationClass = "bool";
        
        $this->assertEquals($expected, $this->mapper->mapAll($source, $destinationClass));
    }
    
    /**
     * @expectedException \GSDCode\Mapper\MapperException
     */
    public function testException()
    {
        $this->mapper->map("error!","exception");
    }
    
    public function testBean1ToTest1()
    {
        $source = new Bean1();
        $source->prop = "hello";
        
        $target = $this->getBeanMapper()->map($source, Test1::class);
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof Test1);
        $this->assertEquals("hello", $target->prop);
    }
    
    public function testTest1ToBean1()
    {
        $source = new Test1();
        $source->prop = "hello";
        
        $target = $this->getBeanMapper()->map($source, Bean1::class);
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof Bean1);
        $this->assertEquals("hello", $target->prop);
    }
    
    public function testBean2ToTest2()
    {
        $source = new Bean2();
        $source->prop = "1";
        
        $target = $this->getBeanMapper()->map($source, Test2::class);
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof Test2);
        $this->assertEquals(true, $target->prop);
    }
    
    public function testTest2ToBean2()
    {
        $source = new Test2();
        $source->prop = true;
        
        $target = $this->getBeanMapper()->map($source, Bean2::class);
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof Bean2);
        $this->assertEquals("TRUE", $target->prop);
    }
    
    public function testDeepBean1ToDeepTest1()
    {
        $source = new DeepBean1();
        $source->prop = new Bean1();
        $source->prop->prop = "hello";
        
        $target = $this->getBeanMapper()->map($source, DeepTest1::class);
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof DeepTest1);
        $this->assertNotNull($target->prop);
        $this->assertTrue($target->prop instanceof Test1);
        $this->assertEquals("hello", $target->prop->prop);
    }
    
    public function testDeepTest1ToDeepBean1()
    {
        $source = new DeepTest1();
        $source->prop = new Test1();
        $source->prop->prop = "hello";
        
        $target = $this->getBeanMapper()->map($source, DeepBean1::class);
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof DeepBean1);
        $this->assertNotNull($target->prop);
        $this->assertTrue($target->prop instanceof Bean1);
        $this->assertEquals("hello", $target->prop->prop);
    }
    
    public function testDeepBean2ToDeepTest2()
    {
        $source = new DeepBean2();
        $source->prop = new Bean2();
        $source->prop->prop = "1";
        
        $target = $this->getBeanMapper()->map($source, DeepTest2::class);
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof DeepTest2);
        $this->assertNotNull($target->prop);
        $this->assertTrue($target->prop instanceof Test2);
        $this->assertEquals(true, $target->prop->prop);
    }
    
    public function testDeepTest2ToDeepBean2()
    {
        $source = new DeepTest2();
        $source->prop = new Test2();
        $source->prop->prop = true;
        
        $target = $this->getBeanMapper()->map($source, DeepBean2::class);
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof DeepBean2);
        $this->assertNotNull($target->prop);
        $this->assertTrue($target->prop instanceof Bean2);
        $this->assertEquals("TRUE", $target->prop->prop);
    }
    
    public function testArrayBean1ToArrayTest1()
    {
        $source = [];
        for($i = 0; $i<10; $i++)
        {
            $bean = new Bean1();
            $bean->prop = "hello".$i;
            $source[] = $bean;
        }
        
        $target = $this->getBeanMapper()->mapAll($source, Test1::class);
        
        $this->assertNotEmpty($target);
        $this->assertCount(count($source), $target);
        
        for($i = 0; $i<10; $i++)
        {
            $this->assertEquals("hello".$i, $target[$i]->prop);
        }
    }
    
    /**
     * @expectedException \GSDCode\Mapper\MapperException
     */
    public function testBeanException()
    {
        $this->getBeanMapper()->map(new Bean1(), DeepTest2::class);
    }
    
    /**
     * @expectedException \GSDCode\Mapper\MapperException
     */
    public function testTestException()
    {
        $this->getBeanMapper()->map(new Test1(), DeepBean2::class);
    }
    
    public function testCustomBeanConverterFrom()
    {
        $source = new Bean1();
        $source->prop = "hello";
        
        $target = $this->getCustomBeanMapper()->map($source, Test1::class);
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof Test1);
        $this->assertEquals("HELLO", $target->prop);
    }
    
    public function testCustomBeanConverterTo()
    {
        $source = new Test1();
        $source->prop = "hello";
        
        $target = $this->getCustomBeanMapper()->map($source, Bean1::class);
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof Bean1);
        $this->assertEquals("HELLO PROP", $target->prop);
    }
    
    /**
     * 
     * @return Mapper
     */
    private function getBeanMapper()
    {
        return (new ObjectMapperBuilder())
                ->withPropertyAccessor($this->propertyAccessor)
                ->withPropertyInfoExtractor($this->propertyInfo)
                ->build($this->beanConfig);
    }
    
    /**
     * 
     * @return Mapper
     */
    private function getCustomBeanMapper()
    {
        return (new ObjectMapperBuilder())
                ->withPropertyAccessor($this->propertyAccessor)
                ->withPropertyInfoExtractor($this->propertyInfo)
                ->build($this->beanConfigCustom);
    }
    
}

class CustomBeanConverter extends \GSDCode\Mapper\Converter\AbstractConverter
{
    
    public function __construct()
    {
        parent::__construct(Bean1::class, Test1::class);
    }
    
    protected function convertFrom($object, $classB)
    {
        $target = new Test1();
        $target->prop = strtoupper($object->prop);
        return $target;
    }

    protected function convertTo($object, $classA)
    {
        $target = new Bean1();
        $target->prop = strtoupper($object->prop)." PROP";
        return $target;
    }

}

class Bean1{
    
    /**
     *
     * @var string
     */
    public $prop;
}

class Test1{
    
    /**
     *
     * @var string
     */
    public $prop;
}

class Bean2{
    
    /**
     * 
     * @var string
     */
    public $prop;
}

class Test2{
    
    /**
     *
     * @var bool
     */
    public $prop;
}

class DeepBean1{
    
    /**
     *
     * @var Bean1 
     */
    public $prop;
}

class DeepTest1{
    
    /**
     *
     * @var Test1
     */
    public $prop;
}

class DeepBean2{
    
    /**
     *
     * @var Bean2
     */
    public $prop;
}

class DeepTest2{
    
    /**
     *
     * @var Test2
     */
    public $prop;
}