<?php

namespace SpyMaster;

include APPLICATION_DATA_PATH . '/testClassForSpyMaster.php';

class SpyMasterTest extends \PHPUnit\Framework\TestCase
{

    protected $targetObject;

    protected function setUp()
    {
        $this->targetObject = new \testing\testClassForSpyMaster();
    }

    public function testInstantiate()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        $this->assertTrue(is_object($spyMaster));
        //    ... of the correct type
        self::assertInstanceOf('SpyMaster\\SpyMaster', $spyMaster);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage You must specify the object that you want to spy on
     */
    public function testInstantiateWithoutObject()
    {
        $spyMaster = new SpyMaster();
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Argument must be an object
     */
    public function testInstantiateWithNonObject()
    {
        $spyMaster = new SpyMaster(true);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage SpyMaster is unable to access PHP internal classes
     */
    public function testInstantiateWithInternalObject()
    {
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

    /**
     * @expectedException Exception
     * @expectedExceptionMessage SpyMaster does not support Saboteur spies
     */
    public function testInfiltrateInvalidSpy()
    {
        $spyMaster = new SpyMaster($this->targetObject);
        
        $spy = $spyMaster->infiltrate('Saboteur');
    }

}
