<?php
/**
 * Valid builder
 * User: moyo
 * Date: 2018/6/4
 * Time: 3:17 PM
 */

namespace Carno\Validator\Valid;

use Carno\Validator\Chips\ERTrans;
use Respect\Validation\Validator;

class Executor
{
    use ERTrans;

    // suffix with "Type"
    private const C2TYPES = ['string'];

    // suffix with "Val"
    private const C2VALUES = ['array', 'bool', 'true', 'false', 'int', 'float', 'scalar'];

    /**
     * @param string $expr
     * @return Rule
     */
    public function analyzing(string $expr) : Rule
    {
        $field = trim(substr($expr, 0, $fwp = strpos($expr, ' ')));

        $needs = trim(substr($expr, $fwp + 1));

        if ($edp = strrpos($needs, ' (')) {
            $error = trim(substr($needs, $edp + 2, -1));
            $needs = trim(substr($needs, 0, $edp));
        }

        $exists = $this->rules[$field] ?? null;

        $v = $exists ? clone $exists->v() : new Validator;

        foreach (explode('|', $needs) as $want) {
            $params = [];
            if ($cdp = strpos($want, ':')) {
                // rule:params
                $rule = substr($want, 0, $cdp);
                $init = substr($want, $cdp + 1);
                // rule:[av1,av2]
                // rule:iv1,iv2
                $params = substr($init, 0, 1) == '[' ? [explode(',', trim($init, '[]'))] : explode(',', $init);
            } else {
                // rule
                $rule = $want;
            }

            if (in_array($rule, self::C2TYPES)) {
                $rule .= '_type';
            } elseif (in_array($rule, self::C2VALUES)) {
                $rule .= '_val';
            }

            $v = call_user_func_array([$v, $this->vNamed($rule)], $params);
        }

        return $this->rules[$field] = new Rule($field, $v, $this->vMessage($error ?? ''));
    }

    /**
     * @param string $input
     * @return string
     */
    private function vNamed(string $input) : string
    {
        $renamed = '';

        foreach (explode('_', $input) as $word) {
            $renamed .= ucfirst($word);
        }

        return lcfirst($renamed);
    }

    /**
     * @param string $error
     * @return Message
     */
    private function vMessage(string $error) : Message
    {
        if (empty($error)) {
            return new Message;
        }

        $regex = [
            '/^([\d]+)\|([\w\\\]+)\|(.*?)$/' => function (array $matches) {
                return [$matches[2], $matches[1], $matches[3]];
            },
            '/^([\w\\\]+)\|(.*?)$/' => function (array $matches) {
                return [$matches[1], null, $matches[2]];
            },
        ];

        foreach ($regex as $expr => $extractor) {
            if (preg_match($expr, $error, $matches)) {
                list($exception, $code, $tips) = $extractor($matches);
                break;
            }
        }

        return new Message($tips ?? $error, $exception ?? null, $code ?? null);
    }
}
