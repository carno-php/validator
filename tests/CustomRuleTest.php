<?php
/**
 * Custom rule test
 * User: moyo
 * Date: 2018/6/8
 * Time: 5:32 PM
 */

namespace Carno\Validator\Tests;

use Carno\Validator\Anno\Loader;
use Carno\Validator\Tests\Samples\ClassB;
use Carno\Validator\Tests\Samples\Input\RequestA;

class CustomRuleTest extends PBTestBase
{
    protected function parsing(Loader $loader) : void
    {
        return;
        $loader->parsing(ClassB::class);
    }

    public function testValid()
    {
        $this->markTestSkipped('TODO');

        $request = new RequestA;

        $this->inspector->valid(ClassB::class, 'm1', $request);
    }
}
