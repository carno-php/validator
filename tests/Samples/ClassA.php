<?php
/**
 * Samples A
 * User: moyo
 * Date: 2018/5/4
 * Time: 3:35 PM
 */

namespace Carno\Validator\Tests\Samples;

use Carno\Validator\Tests\Samples\Input\RequestA;

class ClassA
{
    /**
     * @valid-group hello
     * @valid-named common-int id numeric|positive|min:100
     * @valid name string|in:[NameA,NameB] (Carno\Validator\Tests\Exception\ParamsException|名称只允许是 "NameA" 或者 "NameB")
     * @valid-group
     * @valid sub.sid numeric|between:100,200 (10002|Carno\Validator\Tests\Exception\ParamsException|输入的 SID 必须在 100 和 200 之间)
     * @valid sub.title alpha:!|no_whitespace|ends_with:!!
     * @valid chars.* alpha (Chars 必须为字符串)
     * @valid-clone common-int fields.*.id max:200
     * @valid fields.*.kid min:100 (KID 不能小于100)
     * @param RequestA $request
     * @return RequestA
     */
    public function m1(RequestA $request)
    {
        return $request;
    }

    /**
     * @valid-inherit hello
     * @param RequestA $request
     * @return RequestA
     */
    public function m2(RequestA $request)
    {
        return $request;
    }
}
