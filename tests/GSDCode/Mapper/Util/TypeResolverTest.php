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

namespace Tests\GSDCode\Mapper\Util;

use DateTime;
use GSDCode\Mapper\Util\TypeResolver;
use Tests\GSDCode\Mapper\BaseTestCase;

/**
 * Description of TypeResolverTest
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class TypeResolverTest extends BaseTestCase
{
    
    /**
     *
     * @var TypeResolver
     */
    private $resolver;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->resolver = new TypeResolver($this->propertyAccessor, $this->propertyInfo);
    }
    
    
    public function testGetTypeNoPhpdoc()
    {
        $props = ['string','string','int','float','bool','resource','callable','object','array'];
        $object = new MockForGetType();
        
        foreach($props as $i => $type)
        {
            $this->assertEquals($type, $this->resolver->getType($object, "prop".($i+1))->getBuiltinType());
        }
    }
    
    public function testGetTypePhpdoc()
    {
        $props = ['string','string','int','float','bool','resource','callable','object','array'];
        $object = new MockForGetType();
        
        foreach($props as $i => $type)
        {
            $this->assertEquals($type, $this->resolver->getType($object, "prop".($i+10))->getBuiltinType());
        }
    }
    
    public function testGetProperties()
    {
        $expected = ['prop4','prop1','prop2','prop3']; //public properties are in first places
        $object = new MockForGetProperties();
        
        $this->assertEquals($expected, $this->resolver->getProperties($object));
    }
    
    public function testGetPropertyValue()
    {
        $object = new MockForGetProperties();
        $this->assertEquals(100, $this->resolver->getPropertyValue($object, 'prop1'));
        $this->assertEquals("second", $this->resolver->getPropertyValue($object, 'prop2'));
        $this->assertEquals("public property", $this->resolver->getPropertyValue($object, 'prop4'));
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Util\TypeException
     * @expectedExceptionMessage Unable to read property prop3 from object
     */
    public function testGetUnreadablePropertyValue()
    {
        $object = new MockForGetProperties();
        $this->resolver->getPropertyValue($object, 'prop3');
    }
    
    public function testSetPropertyValue()
    {
        $object = new MockForGetProperties();
        
        $this->assertEquals("second",$object->getProp2());
        $this->assertEquals("public property", $object->prop4);
        
        $this->resolver->setPropertyValue($object, "prop2", "new second");
        $this->resolver->setPropertyValue($object, "prop4", "new public property");
        
        $this->assertEquals("new second",$object->getProp2());
        $this->assertEquals("new public property", $object->prop4);
    }
    
    /**
     * @expectedException \GSDCode\Mapper\Util\TypeException
     * @expectedExceptionMessage Unable to write property prop1 into object 
     */
    public function testSetPropertyUnsettable()
    {
        $object = new MockForGetProperties();
        $this->resolver->setPropertyValue($object, "prop1", 200);
    }
    
}

class MockForGetProperties
{
    private $prop1 = 100;
    private $prop2 = "second";
    private $prop3 = 3.33;
    public $prop4 = "public property";
    
    public function getProp1()
    {
        return $this->prop1;
    }

    public function getProp2()
    {
        return $this->prop2;
    }
    
    public function setProp2($prop2)
    {
        $this->prop2 = $prop2;
        return $this;
    }

    public function setProp3($prop3)
    {
        $this->prop3 = $prop3;
        return $this;
    }
    
}

class MockForGetType
{
    
    public $prop1 = "null";
    public $prop2 = "a string";
    public $prop3 = 100;
    public $prop4 = 100.23;
    public $prop5 = false;
    public $prop6;
    public $prop7;
    public $prop8;
    public $prop9 = [];
    
    /**
     *
     * @var string
     */
    public $prop10;
    
    /**
     *
     * @var string
     */
    public $prop11;
    
    /**
     *
     * @var int
     */
    public $prop12;
    
    /**
     *
     * @var float
     */
    public $prop13;
    
    /**
     *
     * @var bool
     */
    public $prop14;
    
    /**
     *
     * @var resource
     */
    public $prop15;
    
    /**
     *
     * @var callable
     */
    public $prop16;
    
    /**
     *
     * @var DateTime
     */
    public $prop17;
    
    /**
     *
     * @var array
     */
    public $prop18 = [];
    
    public function __construct()
    {
        $this->prop6 = fopen(__FILE__,'r');
        $this->prop7 = function(){ echo "foo"; };
        $this->prop8 = new DateTime();
    }
    
    
}
