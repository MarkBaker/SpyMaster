<?php

namespace SpyMaster;

use Error;
use PHPUnit\Framework\TestCase;

include APPLICATION_DATA_PATH . '/testClassForRecruiter.php';
include APPLICATION_DATA_PATH . '/testClassForExecutor.php';

class ManipulatorTest extends TestCase
{
    public function testInstantiateTestClassThrowsFatalError()
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Call to private');

        // Note - This will be flagged as a problem in many IDEs, because it is a problem
        // But we're verifying the problem throws an Error Exception before testing that the
        // Manipulator/Recruit approach bypasses that problem and allows the class to be instantiated
        new \testing\testClassForRecruiter();
    }

    public function testInstantiateManipulator()
    {
        $Manipulator = new Manipulator();
        $this->assertTrue(is_object($Manipulator));
        //    ... of the correct type
        self::assertInstanceOf('SpyMaster\\Manipulator', $Manipulator);
    }

    public function testRecruiterNoArguments()
    {
        $Manipulator = new Manipulator();

        $recruit = $Manipulator->recruit('\\testing\\testClassForRecruiter');
        $this->assertTrue(is_object($recruit));
        //    ... of the correct type
        self::assertInstanceOf('testing\\testClassForRecruiter', $recruit);
        self::assertSame(1, $recruit->value);
    }

    public function testRecruiterWithArguments()
    {
        $Manipulator = new Manipulator();

        $recruit = $Manipulator->recruit('\\testing\\testClassForRecruiter', 2);
        $this->assertTrue(is_object($recruit));
        //    ... of the correct type
        self::assertInstanceOf('testing\\testClassForRecruiter', $recruit);
        self::assertSame(2, $recruit->value);
    }

    public function testRecruiterWithNonObject()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('The first argument passed to recruit must be a valid class name');

        $Manipulator = new Manipulator();

        $recruit = $Manipulator->recruit(true);
    }

    public function testRecruiterWithInternalObject()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('SpyMaster is unable to access PHP internal classes');

        $Manipulator = new Manipulator();

        $recruit = $Manipulator->recruit('DateTime');
    }

    public function testExecutorNoArguments()
    {
        $Manipulator = new Manipulator();

        $targetObject = new \testing\testClassForExecutor();
        $result = $Manipulator->execute($targetObject, 'add');
        self::assertSame(-2, $result);
        self::assertSame(2, $targetObject->value);
    }

    public function testExecutorWithArguments()
    {
        $Manipulator = new Manipulator();

        $targetObject = new \testing\testClassForExecutor();
        $result = $Manipulator->execute($targetObject, 'add', 3);
        self::assertSame(-4, $result);
        self::assertSame(4, $targetObject->value);
    }

    public function tesExecutorWithNonObject()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('The first argument passed to execute must be an object');

        $Manipulator = new Manipulator();

        $result = $Manipulator->execute(true, 'add', 3);
    }

    public function testExecutorWithInternalObject()
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage('SpyMaster is unable to access PHP internal classes');

        $dto = new \DateTime();

        $Manipulator = new Manipulator();

        $result = $Manipulator->execute($dto, 'add', 3);
    }
}
