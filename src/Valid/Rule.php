<?php
/**
 * Valid rule
 * User: moyo
 * Date: 2018/6/5
 * Time: 10:59 AM
 */

namespace Carno\Validator\Valid;

use Respect\Validation\Validator;

class Rule
{
    /**
     * @var string
     */
    private $field = null;

    /**
     * @var Validator
     */
    private $validator = null;

    /**
     * @var Message
     */
    private $message = null;

    /**
     * Rule constructor.
     * @param string $field
     * @param Validator $validator
     * @param Message $message
     */
    public function __construct(string $field, Validator $validator, Message $message)
    {
        $this->field = $field;
        $this->validator = $validator;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function f() : string
    {
        return $this->field;
    }

    /**
     * @return Validator
     */
    public function v() : Validator
    {
        return $this->validator;
    }

    /**
     * @return Message
     */
    public function e() : Message
    {
        return $this->message;
    }
}
