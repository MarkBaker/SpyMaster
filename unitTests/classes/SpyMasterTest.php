<?php

namespace SpyMaster;

use Yoast\PHPUnitPolyfills\TestCases\XTestCase;

include APPLICATION_DATA_PATH . '/testClassForSpyMaster.php';

class SpyMasterTest extends XTestCase
{

    protected $targetObject;

    /**
     * @before
     */
    protected function setUpFixtures()
    {
        parent::setUpFixtures();

        $this->targetObject = new \testing\testClassForSpyMaster();
    }

    public function testInstantiate()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        $this->assertTrue(is_object($spyMaster));
        //    ... of the correct type
        self::assertInstanceOf('SpyMaster\\SpyMaster', $spyMaster);
    }

    public function testInstantiateWithoutObject()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('You must specify the object that you want to spy on');

        $spyMaster = new SpyMaster();
    }

    public function testInstantiateWithNonObject()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('Argument must be an object');

        $spyMaster = new SpyMaster(true);
    }

    public function testInstantiateWithInternalObject()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('SpyMaster is unable to access PHP internal classes');

        $dto = new \DateTime();

        $spyMaster = new SpyMaster($dto);
    }

    public function testInfiltrateReadOnlySpy()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate();
        $this->assertTrue(is_object($spyMaster));
        //    ... of the correct type
        self::assertInstanceOf('SpyMaster\\Spy', $spy);
    }

    public function testInfiltrateReadWriteSpy()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate(SpyMaster::SPY_READ_WRITE);
        $this->assertTrue(is_object($spyMaster));
        //    ... of the correct type
        self::assertInstanceOf('SpyMaster\\Spy', $spy);
    }

    public function testInfiltrateInvalidSpy()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('SpyMaster does not support Saboteur spies');

        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate('Saboteur');
    }
}
