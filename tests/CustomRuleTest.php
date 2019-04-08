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
use Carno\Validator\Tests\Samples\Input\RField;
use UnexpectedValueException;

class CustomRuleTest extends PBTestBase
{
    protected function parsing(Loader $loader) : void
    {
        $loader->parsing(ClassB::class);
    }

    public function testValid()
    {
        $this->validating(ClassB::class, 'm1', new RequestA, [
        ], UnexpectedValueException::class);

        $this->validating(ClassB::class, 'm1', new RequestA, [
            'name' => 'NameA',
        ], UnexpectedValueException::class);

        $this->validating(ClassB::class, 'm1', new RequestA, [
            'name' => 'NameC',
            'fields' => [[new RField, ['id' => 1]]]
        ], UnexpectedValueException::class);

        $this->validating(ClassB::class, 'm1', new RequestA, [
            'name' => 'NameC',
            'fields' => [[new RField, ['id' => 101]]]
        ], UnexpectedValueException::class);

        $this->validating(ClassB::class, 'm1', new RequestA, [
            'name' => 'NameD',
            'fields' => [[new RField, ['id' => 50]]],
        ]);
    }
}
