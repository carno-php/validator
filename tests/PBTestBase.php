<?php
/**
 * PB test base
 * User: moyo
 * Date: 2018/6/8
 * Time: 5:37 PM
 */

namespace Carno\Validator\Tests;

use Carno\Container\DI;
use Carno\Validator\Anno\Loader;
use Carno\Validator\Coordinator;
use Carno\Validator\Inspector;
use Carno\Validator\Sourcing\Protobuf;
use PHPUnit\Framework\TestCase;

abstract class PBTestBase extends TestCase
{
    /**
     * @var Inspector
     */
    protected $inspector = null;

    /**
     * PBTestBase constructor.
     * @param null|string $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->inspector = new Inspector(new Protobuf);

        /**
         * @var Coordinator $coordinator
         */

        $coordinator = DI::object(Coordinator::class);

        $this->parsing(new Loader($coordinator, $this->inspector));

        $coordinator->checking();
    }

    /**
     * @param Loader $loader
     */
    abstract protected function parsing(Loader $loader) : void;
}
