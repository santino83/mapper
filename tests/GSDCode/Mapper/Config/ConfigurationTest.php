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

namespace Tests\GSDCode\Mapper\Config;

use GSDCode\Mapper\Config\Configuration;
use GSDCode\Mapper\Config\YamlLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;

/**
 * Description of ConfigurationTest
 *
 * @author <a href="mailto:gsantini@voiptech.it">Giorgio M. Santini</a>
 */
class ConfigurationTest extends TestCase
{
    
    public function testLoadEmpty()
    {
        $configuration = new Configuration();
        $config = $configuration->load();
        
        $this->assertArrayHasKey("settings", $config);
        $this->assertArrayHasKey("converters", $config);
        $this->assertArrayHasKey("mappings", $config);
    }
    
    public function testLoadFromResourceOK()
    {
        $configuration = new Configuration();
        $config = $configuration->loadFromResource("simple_config.yml", $this->getLoader());
        
        $this->checkSimpleConfigLoaded($config);
    }
    
    public function testLoadFromArrayOK()
    {
        $configuration = new Configuration();
        $array = $this->getLoader()->load("simple_config.yml");
        
        $config = $configuration->load($array);
        
        $this->checkSimpleConfigLoaded($config);
    }
    
    public function testLoadExtra1FromArrayOK()
    {
        $configuration = new Configuration();
        $base = $this->getLoader()->load("simple_config.yml");
        $extra1 = $this->getLoader()->load("extra_1.yml");
        
        $config = $configuration->load([$base, $extra1]);
        
        $this->assertNotEmpty($config);
        $this->assertNotEmpty($config['mappings']);
        $this->assertCount(3, $config['mappings']);
        
        $this->assertEquals('Tests\Data\Bean\NotExistent1', $config['mappings'][2]['classA']);
        $this->assertEquals('Tests\Data\Target\NotExistent1', $config['mappings'][2]['classB']);
    }
    
    public function testLoadExtra2FromArrayOK()
    {
        $configuration = new Configuration();
        $base = $this->getLoader()->load("simple_config.yml");
        $extra2 = $this->getLoader()->load("extra_2.yml");
        
        $config = $configuration->load([$base, $extra2]);
        
        $this->assertNotEmpty($config);
        $this->assertNotEmpty($config['settings']);
        $this->assertArrayHasKey('date_format', $config['settings']);
        $this->assertEquals("Y-m-d", $config['settings']['date_format']);
        $this->assertNotEmpty($config['converters']);
        $this->assertCount(3, $config['converters']);
        
        $this->assertContains("extra_converter", $config['converters']);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testLoadWrong()
    {
        $configuration = new Configuration();
        $base = $this->getLoader()->load("wrong.yml");
        $configuration->load($base);
    }
    
    private function checkSimpleConfigLoaded(Array $config = [])
    {
        $this->assertNotEmpty($config);
        $this->assertNotEmpty($config['settings']);
        $this->assertArrayHasKey('date_format', $config['settings']);
        $this->assertEquals(\DateTime::W3C, $config['settings']['date_format']);
        $this->assertNotEmpty($config['converters']);
        $this->assertCount(2, $config['converters']);
        $this->assertNotEmpty($config['mappings']);
        $this->assertCount(2, $config['mappings']);
        
        $converters = $config['converters'];
        $mappings = $config['mappings'];
        
        $this->assertContains("my_first_converter", $converters);
        $this->assertContains("my_second_converter", $converters);
        
        $this->assertEquals('Tests\Data\Bean\ChildBean1', $mappings[0]['classA']);
        $this->assertEquals('Tests\Data\Target\TargetChild1', $mappings[0]['classB']);
        $this->assertEmpty($mappings[0]['fields']);
        
        $this->assertEquals('Tests\Data\Bean\ChildBean2', $mappings[1]['classA']);
        $this->assertEquals('Tests\Data\Target\TargetChild2', $mappings[1]['classB']);
        
        $this->assertNotEmpty($mappings[1]['fields']);
        $this->assertCount(3, $mappings[1]['fields']);
        
        $fields = $mappings[1]['fields'];
        $this->assertEquals("childProp1", $fields[0]['fieldA']);
        $this->assertEquals("prop4", $fields[0]['fieldB']);
        $this->assertEquals("childProp2", $fields[1]['fieldA']);
        $this->assertEquals("prop5", $fields[1]['fieldB']);
        $this->assertEquals("childProp3", $fields[2]['fieldA']);
        $this->assertEquals("prop6", $fields[2]['fieldB']);
    }
    
    /**
     * 
     * @return YamlLoader
     */
    private function getLoader()
    {
        return new YamlLoader(new FileLocator(__DIR__.'/../../../Data/Config'));
    }
    
}
