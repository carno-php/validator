<?php
/**
 * Valid message
 * User: moyo
 * Date: 2018/6/5
 * Time: 11:02 AM
 */

namespace Carno\Validator\Valid;

use Throwable;
use UnexpectedValueException;

class Message
{
    /**
     * @var string
     */
    private $tips = null;

    /**
     * @var string
     */
    private $exception = null;

    /**
     * @var int
     */
    private $code = null;

    /**
     * Message constructor.
     * @param string $tips
     * @param string $exception
     * @param int $code
     */
    public function __construct(string $tips = '', string $exception = null, int $code = null)
    {
        $this->tips = $tips;
        $this->exception = $exception;
        $this->code = $code;
    }

    /**
     * @return bool
     */
    public function valid() : bool
    {
        return ! empty($this->tips);
    }

    /**
     * @throws Throwable
     */
    public function throws() : void
    {
        $exception = $this->exception ?? UnexpectedValueException::class;
        throw new $exception($this->tips, $this->code ?? 0);
    }
}
