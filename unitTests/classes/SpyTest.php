<?php

namespace SpyMaster;

include APPLICATION_DATA_PATH . '/testClassForSpy.php';

class SpyTest extends \PHPUnit\Framework\TestCase
{

    protected $targetObject;

    protected function setUp(): void
    {
        $this->targetObject = new \testing\testClassForSpy();
    }

    public function testSpyHasClassName()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        $className = $spy->getClassName();
        $this->assertEquals($className, 'testing\\testClassForSpy');
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

    public function testSpyGetNonExistentProperty()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('Property nonExistentProperty does not exist');

        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        $privatePropertyValue = $spy->nonExistentProperty;
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

    public function testSpyPropertyUnset()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('Spies are not permitted to unset properties');

        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        unset($spy->private);
    }

    public function testSpyNonExistentPropertyUnset()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('Property nonExistentProperty does not exist');

        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        unset($spy->nonExistentProperty);
    }

    public function testReadOnlySpyCannotChangeProperty()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('ReadOnly Spies are not permitted to change property values');

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

    public function testReadWriteSpySetNonExistentProperty()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('Property nonExistentProperty does not exist');

        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate(SpyMaster::SPY_READ_WRITE);
        $spy->nonExistentProperty = 'PHP';
    }
}
