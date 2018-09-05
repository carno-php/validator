<?php
/**
 * Samples B
 * User: moyo
 * Date: 2018/6/8
 * Time: 5:33 PM
 */

namespace Carno\Validator\Tests\Samples;

use Carno\Validator\Tests\Samples\Input\RequestA;

class ClassB
{
    /**
     * @valid-inherit base-test
     * @valid-clone custom-fields fields.*.id
     * @param RequestA $request
     * @return RequestA
     */
    public function m1(RequestA $request)
    {
        return $request;
    }
}
