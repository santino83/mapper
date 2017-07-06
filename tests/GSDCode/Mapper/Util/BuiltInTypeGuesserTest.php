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

use GSDCode\Mapper\Util\BuiltInTypeGuesser;
use GSDCode\Mapper\Util\TypeGuesser;
use PHPUnit\Framework\TestCase;

/**
 * Description of BuiltInTypeGuesserTest
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class BuiltInTypeGuesserTest extends TestCase
{
    
    /**
     *
     * @var TypeGuesser
     */
    private $guesser;
    
    protected function setUp()
    {
        $this->guesser = new BuiltInTypeGuesser();
    }
    
    public function testCallabe()
    {
        $fun = function(){ echo "foo"; };
        $this->assertThatTypeIs($fun, "callable");
    }
    
    public function testArray()
    {
        $this->assertThatTypeIs([], "array");
    }
    
    public function testObject()
    {
        $this->assertThatTypeIs(new \DateTime(), "object");
    }
    
    public function testResource()
    {
        $this->assertThatTypeIs(fopen(__FILE__, 'r'), "resource");
    }
    
    public function testBool()
    {
        $this->assertThatTypeIs(false, 'bool');
    }
    
    public function testFloat()
    {
        $this->assertThatTypeIs(10.24, "float");
    }
    
    public function testInt()
    {
        $this->assertThatTypeIs(100, 'int');
    }
    
    public function testString()
    {
        $this->assertThatTypeIs("a string", "string");
    }
    
    public function testNull()
    {
        $this->assertThatTypeIs(null, "null");
    }
    
    private function assertThatTypeIs($value, $typeName)
    {
        $type = $this->guesser->guess($value);
        $this->assertNotNull($type);
        $this->assertEquals($typeName, $type->getBuiltinType());
    }
}
