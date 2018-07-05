<?php

namespace CobrancaPHP\Test;

use PHPUnit\Framework\TestCase;

/**
 * Class BaseTestCase
 * @package CobrancaPHP\Test
 */
abstract class BaseTestCase extends TestCase
{
    /**
     * @param string $version
     * @return mixed
     */
    public function isVersionGreaterOrEqualsThan($version)
    {
        return $this->compareWithCurrentVersion($version, '>=');
    }

    /**
     * @param string $version
     * @return mixed
     */
    public function isVersionGreaterThan($version)
    {
        return $this->compareWithCurrentVersion($version, '>');
    }

    /**
     * @param string $version
     * @return mixed
     */
    public function isVersionLessOrEqualsThan($version)
    {
        return $this->compareWithCurrentVersion($version, '<=');
    }

    /**
     * @param string $version
     * @return mixed
     */
    public function isVersionLessThan($version)
    {
        return $this->compareWithCurrentVersion($version, '<');
    }

    /**
     * @param string $version
     * @return mixed
     */
    public function isVersionEqualsTo($version)
    {
        return $this->compareWithCurrentVersion($version, '=');
    }

    /**
     * @link http://php.net/manual/en/function.version-compare.php
     * @param string $version
     * @param string $operator
     * @return mixed
     */
    public function compareWithCurrentVersion($version, $operator)
    {
        return version_compare(PHP_VERSION, $version, $operator);
    }

    /**
     * @return void
     */
    public abstract function test();
}
