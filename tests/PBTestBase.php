<?php
/**
 * PB test base
 * User: moyo
 * Date: 2018/6/8
 * Time: 5:37 PM
 */

namespace Carno\Validator\Tests;

use Carno\Validator\Anno\Loader;
use Carno\Validator\Coordinator;
use Carno\Validator\Inspector;
use Carno\Validator\Sourcing\Protobuf;
use Carno\Validator\Valid\Cloning;
use Carno\Validator\Valid\Group;
use Carno\Validator\Valid\Inherit;
use Carno\Validator\Valid\Named;
use Google\Protobuf\Internal\Message;
use PHPUnit\Framework\TestCase;
use Throwable;

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

        $coordinator = new Coordinator(new Group, new Inherit, new Named, new Cloning);

        $this->parsing($loader = new Loader($coordinator, $this->inspector));

        $loader->walking(include 'validates.php');

        $coordinator->checking();
    }

    protected function validating(
        string $class,
        string $method,
        Message $obj,
        array $rules,
        string $exception = null,
        string $message = null
    ) : void {
        $this->pbAssign($obj, $rules);

        $ec = null;
        $em = null;
        try {
            $this->inspector->valid($class, $method, $obj);
        } catch (Throwable $e) {
            $ec = get_class($e);
            $em = $e->getMessage();
        }

        if ($exception) {
            $this->assertEquals($exception, $ec);
            $message && $this->assertEquals($message, $em);
        } else {
            $this->assertEquals(null, $ec);
        }
    }

    private function pbAssign(Message $obj, array $fields) : void
    {
        foreach ($fields as $name => $value) {
            if (is_array($value)) {
                $raw = $value;
                $sub = array_shift($value);
                if (is_object($sub)) {
                    // [Obj, [fields]]
                    $this->pbAssign($sub, array_shift($value));
                    $value = $sub;
                } elseif (is_array($sub)) {
                    // [[Obj, [fields]]]
                    $list = [];
                    foreach ($raw as $subb) {
                        $list[] = $subo = array_shift($subb);
                        $this->pbAssign($subo, array_shift($subb));
                    }
                    $value = $list;
                } else {
                    $value = $raw;
                }
            }

            if (method_exists($obj, $func = 'set'.ucfirst($name))) {
                $obj->$func($value);
            }
        }
    }

    /**
     * @param Loader $loader
     */
    abstract protected function parsing(Loader $loader) : void;
}
