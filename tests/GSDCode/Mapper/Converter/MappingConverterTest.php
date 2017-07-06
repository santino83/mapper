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

use DateTime;
use GSDCode\Mapper\Bean\BeanFactory;
use GSDCode\Mapper\Converter\ConverterInterface;
use GSDCode\Mapper\Converter\ConverterRegistry;
use GSDCode\Mapper\Converter\MappingConverter;
use GSDCode\Mapper\Util\TypeResolver;
use Tests\Data\Bean\ChildBean1;
use Tests\Data\Bean\ChildBean2;
use Tests\Data\Bean\ChildBean3;
use Tests\Data\Bean\OtherBean;
use Tests\Data\Target\TargetChild1;
use Tests\Data\Target\TargetChild2;
use Tests\Data\Target\TargetChild3;
use Tests\Data\Target\TargetOtherBean;
use Tests\GSDCode\Mapper\BaseTestCase;

/**
 * Description of MappingConverterTest
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class MappingConverterTest extends BaseTestCase
{
    
    private $config1 = ['mappings' => [
       ['classA' => 'Tests\Data\Bean\ChildBean1','classB' => 'Tests\Data\Target\TargetChild1', 'fields' => []],
       ['classA' => 'Tests\Data\Bean\ChildBean2','classB' => 'Tests\Data\Target\TargetChild2', 'fields' => [
           ['fieldA' => 'childProp1','fieldB' => 'prop4','converter' => null],
           ['fieldA' => 'childProp2','fieldB' => 'prop5','converter' => null],
           ['fieldA' => 'childProp3','fieldB' => 'prop6','converter' => null]
       ]]
    ]];
    
    private $config2 = ['mappings' => [
        ['classA' => 'Tests\Data\Bean\ChildBean3','classB' => 'Tests\Data\Target\TargetChild3', 'fields' => []],
        ['classA' => 'Tests\Data\Bean\OtherBean','classB' => 'Tests\Data\Target\TargetOtherBean', 'fields' => []],
    ]];
    
    public function testSupports()
    {
        $converter = $this->getConverter($this->config1);
        $this->assertTrue($converter->supports('Tests\Data\Bean\ChildBean1', 'Tests\Data\Target\TargetChild1'));
        $this->assertTrue($converter->supports('Tests\Data\Bean\ChildBean2', 'Tests\Data\Target\TargetChild2'));
        $this->assertFalse($converter->supports('Tests\Data\Bean\ChildBean1', 'Tests\Data\Target\TargetChild2'));
        $this->assertFalse($converter->supports('Tests\Data\Bean\ChildBean2', 'Tests\Data\Target\TargetChild1'));
        $this->assertFalse($converter->supports('My\Class\A', 'My\Class\B'));
    }
    
    public function testMapping_fields_same_name()
    {
        $converter = $this->getConverter($this->config1);
        $bean = $this->getSetupChild1();
        
        $target = $converter->convert($bean, TargetChild1::class);
        /*@var $target TargetChild1 */
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof TargetChild1);
        $this->assertEquals(['a','b','c'], $target->getChildProp1());
        $this->assertEquals(true, $target->getChildProp2());
        $this->assertEquals("public property", $target->getChildProp3());
        $this->assertEquals(100, $target->getProp1());
        $this->assertEquals("second prop", $target->getProp2()); 
        $this->assertEquals(new DateTime("2016-02-01 16:23:00"), $target->getProp3()); 
    }
    
    public function testMapping_fields_different_name()
    {
        $converter = $this->getConverter($this->config1);
        $bean = $this->getSetupChild2();
        
        $target = $converter->convert($bean, TargetChild2::class);
        /*@var $target TargetChild2 */
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof TargetChild2);
        $this->assertEquals("first prop", $target->getProp4());
        $this->assertEquals($bean->getChildProp2(), $target->getProp5());
        $this->assertEquals($bean->childProp3, $target->getProp6());
        $this->assertEquals(100, $target->getProp1());
        $this->assertEquals("second prop", $target->getProp2()); 
        $this->assertEquals(new DateTime("2016-02-01 16:23:00"), $target->getProp3()); 
    }
    
    public function testMapping_deep()
    {
        $converter = $this->getConverter($this->config2);
        $bean = $this->getSetupChild3();
        
        $target = $converter->convert($bean, TargetChild3::class);
        /*@var $target TargetChild3 */
        
        $this->assertNotNull($target);
        $this->assertTrue($target instanceof TargetChild3);
        $this->assertNotNull($target->getProp());
        $this->assertTrue($target->getProp() instanceof TargetOtherBean);
        
        $targetOtherBean = $target->getProp();
        
        $this->assertEquals("one", $targetOtherBean->getOtherProp1());
        $this->assertEquals("two", $targetOtherBean->getOtherProp2());
        $this->assertEquals("three", $targetOtherBean->getOtherProp3());
    }
    
    /**
     * 
     * @param array $config
     * @return ConverterInterface
     */
    private function getConverter(Array $config)
    {
        $typeResolver = new TypeResolver($this->propertyAccessor, $this->propertyInfo);
        return new MappingConverter($config, new ConverterRegistry(), new BeanFactory(), $typeResolver);
    }
    
    /**
     * 
     * @return ChildBean3
     */
    private function getSetupChild3()
    {
        $child = new ChildBean3();
        
        $otherBean = new OtherBean();
        $otherBean->setOtherProp1("one")
                ->setOtherProp2("two")
                ->setOtherProp3("three");
        
        $child->setProp($otherBean);
        
        return $child;
    }
    
    /**
     * 
     * @return ChildBean2
     */
    private function getSetupChild2()
    {
        $child = new ChildBean2();
        $child->childProp3 = new OtherBean();
        $child->prop3 = new DateTime("2016-02-01 16:23:00");
        $child->setChildProp1("first prop")
                ->setChildProp2(new OtherBean())
                ->setProp1(100)
                ->setProp2("second prop");
        
        return $child;
    }
    
    /**
     * 
     * @return ChildBean1
     */
    private function getSetupChild1()
    {
        $child = new ChildBean1();
        $child->prop3 = new DateTime("2016-02-01 16:23:00");
        $child->childProp3 = "public property";
        $child->setChildProp1(['a','b','c'])
                ->setChildProp2(true)
                ->setProp1(100)
                ->setProp2("second prop");
        
        return $child;
    }
    
}
