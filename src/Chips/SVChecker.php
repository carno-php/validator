<?php
/**
 * Sourcing value checker
 * User: moyo
 * Date: 2018/6/8
 * Time: 3:06 PM
 */

namespace Carno\Validator\Chips;

use Carno\Validator\Valid\Message;
use Carno\Validator\Valid\Rule;
use Respect\Validation\Exceptions\ValidationException;
use Throwable;

trait SVChecker
{
    /**
     * @param Rule $rule
     * @param mixed $input
     * @throws Throwable
     */
    protected function checking(Rule $rule, $input)
    {
        if ($rule->e()->valid()) {
            $rule->v()->validate($input) || $rule->e()->throws();
        } else {
            try {
                $rule->v()->check($input);
            } catch (ValidationException $e) {
                (new Message($e->setName($rule->f())->getMainMessage()))->throws();
            }
        }
    }
}
