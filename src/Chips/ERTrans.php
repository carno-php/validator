<?php
/**
 * Executor rules trans
 * User: moyo
 * Date: 2018/6/5
 * Time: 11:16 AM
 */

namespace Carno\Validator\Chips;

use Carno\Validator\Valid\Rule;

trait ERTrans
{
    /**
     * @var Rule[]
     */
    private $rules = [];

    /**
     * @return Rule[]
     */
    public function rules() : array
    {
        return $this->rules;
    }

    /**
     * @param Rule $rule
     */
    public function import(Rule $rule) : void
    {
        $this->rules[$rule->f()] = $rule;
    }

    /**
     * @param string $field
     * @return Rule
     */
    public function export(string $field) : Rule
    {
        return $this->rules[$field];
    }
}
