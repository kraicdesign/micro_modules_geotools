<?php

/*
 * This file is part of the Geotools library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Geotools\Tests\CLI\Command\Convert;

use League\Geotools\CLI\Application;
use League\Geotools\CLI\Command\Convert\UTM;
use League\Geotools\Exception\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class UTMTest extends \League\Geotools\Tests\TestCase
{
    protected $application;
    protected $command;
    protected $commandTester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->application = new Application;
        $this->application->add(new UTM);

        $this->command = $this->application->find('convert:utm');

        $this->commandTester = new CommandTester($this->command);
    }

    public function testExecuteWithoutArguments()
    {
        $this->expectException(RuntimeException::class);
        $this->commandTester->execute(array(
            'command' => $this->command->getName(),
        ));
    }

    public function testExecuteInvalidArguments()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute(array(
            'command'    => $this->command->getName(),
            'coordinate' => 'foo, bar',
        ));
    }

    public function testExecute()
    {
        $this->commandTester->execute(array(
            'command'    => $this->command->getName(),
            'coordinate' => '48.8234055, 2.3072664',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/31U 449152 5408055/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithEmptyEllipsoidOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'coordinate'  => '40° 26.7717, -79° 56.93172',
            '--ellipsoid' => ' ',
        ));
    }

    public function testExecuteWithoutAvailableEllipsoidOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'coordinate'  => '40° 26.7717, -79° 56.93172',
            '--ellipsoid' => 'foo',
        ));
    }

    public function testExecuteWithEllipsoidOption_MODIFIED_FISCHER_1960()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'coordinate'  => '40° 26.7717, -79° 56.93172',
            '--ellipsoid' => 'MODIFIED_FISCHER_1960',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/17T 589139 4477828/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithEllipsoidOption_BESSEL_1841_NAMBIA()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'coordinate'  => '40° 26.7717, -79° 56.93172',
            '--ellipsoid' => 'BESSEL_1841_NAMBIA',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/17T 589129 4477424/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithEllipsoidOption_CLARKE_1866()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'coordinate'  => '40° 26.7717, -79° 56.93172',
            '--ellipsoid' => 'CLARKE_1866',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/17T 589141 4477602/', $this->commandTester->getDisplay());
    }
}
