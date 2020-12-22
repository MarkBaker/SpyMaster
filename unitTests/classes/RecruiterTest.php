<?php

namespace SpyMaster;

use PHPUnit\Framework\TestCase;

include APPLICATION_DATA_PATH . '/testClassForRecruiter.php';

class RecruiterTest extends TestCase
{
    public function testInstantiateTestClassThrowsFatalError()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Call to private');

        new \testing\testClassForRecruiter();
    }

    public function testInstantiate()
    {
        $recruiter = new Recruiter();
        $this->assertTrue(is_object($recruiter));
        //    ... of the correct type
        self::assertInstanceOf('SpyMaster\\Recruiter', $recruiter);
    }

    public function testRecruiterNoArguments()
    {
        $recruiter = new Recruiter();

        $recruit = $recruiter->recruit('\\testing\\testClassForRecruiter');
        $this->assertTrue(is_object($recruit));
        //    ... of the correct type
        self::assertInstanceOf('testing\\testClassForRecruiter', $recruit);
        self::assertSame(1, $recruit->value);
    }

    public function testRecruiterWithArguments()
    {
        $recruiter = new Recruiter();

        $recruit = $recruiter->recruit('\\testing\\testClassForRecruiter', 2);
        $this->assertTrue(is_object($recruit));
        //    ... of the correct type
        self::assertInstanceOf('testing\\testClassForRecruiter', $recruit);
        self::assertSame(2, $recruit->value);
    }
}
