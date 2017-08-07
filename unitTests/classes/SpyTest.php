<?php

namespace SpyMaster;

include APPLICATION_DATA_PATH . '/testClassForSpy.php';

class SpyTest extends \PHPUnit_Framework_TestCase
{

    protected $targetObject;

    protected function setUp()
    {
        $this->targetObject = new \testing\testClassForSpy();
    }

    public function testSpyHasProperties()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        $properties = $spy->listProperties();
        $this->assertCount(3, $properties);
        $this->assertContains('public', $properties);
        $this->assertContains('protected', $properties);
        $this->assertContains('private', $properties);
    }

    public function testSpyGetPublicProperty()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        $propertyValue = $spy->public;
        $this->assertEquals($propertyValue, 'Hello');
    }

    public function testSpyGetProtectedProperty()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        $protectedPropertyValue = $spy->protected;
        $this->assertEquals($protectedPropertyValue, 'World');
    }

    public function testSpyGetPrivateProperty()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        $privatePropertyValue = $spy->private;
        $this->assertEquals($privatePropertyValue, 'ElePHPant');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Property nonExistent does not exist
     */
    public function testSpyGetNonExistentProperty()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        $privatePropertyValue = $spy->nonExistent;
    }

    public function testSpyPropertyIsSet()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        $privatePropertyExists = isset($spy->private);
        $this->assertTrue($privatePropertyExists);
        $nonExistentPropertyExists = isset($spy->nonExistent);
        $this->assertFalse($nonExistentPropertyExists);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Spies are not permitted to unset properties
     */
    public function testSpyPropertyUnset()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        unset($spy->private);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Property nonExistent does not exist
     */
    public function testSpyNonExistentPropertyUnset()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        unset($spy->nonExistent);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage ReadOnly Spies are not permitted to change property values
     */
    public function testReadOnlySpyCannotChangeProperty()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        $spy->private = 'PHP';
    }

    public function testReadWriteSpyCanChangeProperty()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate(SpyMaster::SPY_READ_WRITE);
        $privatePropertyValue = $spy->private;
        $this->assertEquals($privatePropertyValue, 'ElePHPant');
        $spy->private = 'PHP';
        $privatePropertyValue = $spy->private;
        $this->assertEquals($privatePropertyValue, 'PHP');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Property nonExistent does not exist
     */
    public function testReadWriteSpySetNonExistentProperty()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate(SpyMaster::SPY_READ_WRITE);
        $spy->nonExistent = 'PHP';
    }
}
