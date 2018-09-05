<?php
/**
 * A simple test
 * User: moyo
 * Date: 2018/6/4
 * Time: 11:19 AM
 */

namespace Carno\Validator\Tests;

use Carno\Validator\Anno\Loader;
use Carno\Validator\Tests\Samples\ClassA;
use Carno\Validator\Tests\Samples\Input\RequestA;
use Carno\Validator\Tests\Samples\Input\RField;
use Carno\Validator\Tests\Samples\Input\SubRequestA;
use Throwable;

class SimplePBTest extends PBTestBase
{
    protected function parsing(Loader $loader) : void
    {
        $loader->parsing(ClassA::class);
    }

    public function testValid()
    {
        $request = new RequestA;

        $request->setId(300);
        $request->setName('NameA');

        $sub = new SubRequestA();

        $sub->setSid(188);
        $sub->setTitle('hello!!');

        $request->setSub($sub);

        $request->setChars(['a', 'b', 'c']);

        $f = new RField;
        $f->setId(123);
        $f->setKid(999);

        $request->setFields([$f]);

        $e = null;
        try {
            $this->inspector->valid(ClassA::class, 'm1', $request);
        } catch (Throwable $ee) {
            $e = $ee->getMessage();
        }

        $this->assertNull($e);
    }
}
