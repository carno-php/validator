<?php
/**
 * A simple test
 * User: moyo
 * Date: 2018/6/4
 * Time: 11:19 AM
 */

namespace Carno\Validator\Tests;

use Carno\Validator\Anno\Loader;
use Carno\Validator\Tests\Exception\ParamsException;
use Carno\Validator\Tests\Samples\ClassA;
use Carno\Validator\Tests\Samples\Input\RequestA;
use Carno\Validator\Tests\Samples\Input\RField;
use Carno\Validator\Tests\Samples\Input\SubRequestA;
use UnexpectedValueException;

class SimplePBTest extends PBTestBase
{
    protected function parsing(Loader $loader) : void
    {
        $loader->parsing(ClassA::class);
    }

    public function testValid()
    {
        $this->initialize();

        $this->validating(ClassA::class, 'm1', new RequestA, [
            'id' => 10,
        ], UnexpectedValueException::class);

        $this->validating(ClassA::class, 'm1', new RequestA, [
            'id' => 100,
            'name' => 'Name',
        ], ParamsException::class, '名称只允许是 "NameA" 或者 "NameB"');

        $this->validating(ClassA::class, 'm1', new RequestA, [
            'id' => 100,
            'name' => 'NameA',
            'sub' => [new SubRequestA, ['sid' => 80]]
        ], ParamsException::class, '输入的 SID 必须在 100 和 200 之间');

        $this->validating(ClassA::class, 'm1', new RequestA, [
            'id' => 100,
            'name' => 'NameA',
            'sub' => [new SubRequestA, ['sid' => 123, 'title' => 'hello']]
        ], UnexpectedValueException::class, 'sub.title must end with ("!!")');

        $this->validating(ClassA::class, 'm1', new RequestA, [
            'id' => 100,
            'name' => 'NameA',
            'sub' => [new SubRequestA, ['sid' => 123, 'title' => 'HELLO!!']]
        ], UnexpectedValueException::class, 'Chars 必须为字符串');

        $this->validating(ClassA::class, 'm1', new RequestA, [
            'id' => 100,
            'name' => 'NameA',
            'sub' => [new SubRequestA, ['sid' => 123, 'title' => 'HELLO!!']],
            'chars' => ['a', 'b', 'c'],
            'fields' => [[new RField, ['id' => 33]]]
        ], UnexpectedValueException::class, 'fields.*.id must be greater than or equal to "100"');

        $this->validating(ClassA::class, 'm1', new RequestA, [
            'id' => 100,
            'name' => 'NameA',
            'sub' => [new SubRequestA, ['sid' => 123, 'title' => 'HELLO!!']],
            'chars' => ['a', 'b', 'c'],
            'fields' => [[new RField, ['id' => 123]]]
        ], UnexpectedValueException::class, 'KID 不能小于100');

        $this->validating(ClassA::class, 'm1', new RequestA, [
            'id' => 100,
            'name' => 'NameA',
            'sub' => [new SubRequestA, ['sid' => 123, 'title' => 'HELLO!!']],
            'chars' => ['a', 'b', 'c'],
            'fields' => [[new RField, ['id' => 123, 'kid' => 200]]]
        ]);
    }
}
