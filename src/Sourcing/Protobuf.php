<?php
/**
 * Sourcing by protobuf
 * User: moyo
 * Date: 2018/6/4
 * Time: 10:48 AM
 */

namespace Carno\Validator\Sourcing;

use Carno\Validator\Chips\SVChecker;
use Carno\Validator\Contracts\Sourcing;
use Carno\Validator\Exception\IllegalNestedRuleExprException;
use Carno\Validator\Exception\UnknownNestedTypeException;
use Carno\Validator\Valid\Executor;
use Google\Protobuf\Internal\Message;
use Google\Protobuf\Internal\RepeatedField;
use Throwable;

class Protobuf implements Sourcing
{
    use SVChecker;

    private const WK_SCALAR = 2;
    private const WK_ITERATE = 4;

    /**
     * @param Executor $executor
     * @param Message $input
     * @throws Throwable
     */
    public function validating(Executor $executor, $input)
    {
        foreach ($executor->rules() as $field => $rule) {
            list($type, $data) = $this->walking($field, $input);
            if ($type === self::WK_SCALAR) {
                $this->checking($rule, $data);
            } elseif ($type === self::WK_ITERATE) {
                if (empty($data)) {
                    $this->checking($rule, null);
                } else {
                    foreach ($data as $getter) {
                        $this->checking($rule, $getter());
                    }
                }
            }
        }
    }

    /**
     * @param string $field
     * @param Message $input
     * @return array
     */
    private function walking(string $field, Message $input) : array
    {
        if (count($parts = explode('.', $field)) > 1) {
            // has nested field
            $part = array_shift($parts);
            $nested = $this->pbGet($input, $part);
            if ($nested instanceof Message) {
                return $this->walking(implode('.', $parts), $nested);
            } elseif ($nested instanceof RepeatedField) {
                if (array_shift($parts) !== '*') {
                    throw new IllegalNestedRuleExprException;
                }
                $iterates = [];
                foreach ($nested as $data) {
                    $iterates[] = function () use ($parts, $data) {
                        return $data instanceof Message
                            ? $this->walking(implode('.', $parts), $data)[1]
                            : $data
                        ;
                    };
                }
                return [self::WK_ITERATE, $iterates];
            } elseif (is_null($nested)) {
                // maybe nested is message and have no data present
                return [self::WK_SCALAR, null];
            } else {
                throw new UnknownNestedTypeException;
            }
        } else {
            // direct val getting
            return [self::WK_SCALAR, $this->pbGet($input, $field)];
        }
    }

    /**
     * @param Message $payload
     * @param string $field
     * @return mixed
     */
    private function pbGet(Message $payload, string $field)
    {
        return call_user_func([$payload, sprintf('get%s', ucfirst($field))]);
    }
}
