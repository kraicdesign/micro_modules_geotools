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
use League\Geotools\CLI\Command\Convert\DMS;
use League\Geotools\Exception\InvalidArgumentException;
use League\Geotools\Tests\TestCase;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class DMSTest extends TestCase
{
    protected $application;
    protected $command;
    protected $commandTester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->application = new Application;
        $this->application->add(new DMS);

        $this->command = $this->application->find('convert:dms');

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
            '--format'   => ' ',
        ));
    }

    public function testExecuteWithoutFormatOption()
    {
        $this->commandTester->execute(array(
            'command'    => $this->command->getName(),
            'coordinate' => '48.8234055, 2.3072664',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/48°49′24″N, 2°18′26″E/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithFormatOption()
    {
        $this->commandTester->execute(array(
            'command'    => $this->command->getName(),
            'coordinate' => '40° 26.7717, -79° 56.93172',
            '--format'   => '%P%D:%M:%S, %p%d:%m:%s',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/40:26:46, -79:56:56/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithEmptyFormatOption()
    {
        $this->commandTester->execute(array(
            'command'    => $this->command->getName(),
            'coordinate' => '40° 26.7717, -79° 56.93172',
            '--format'   => ' ',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/<value> <\/value>/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithEmptyEllipsoidOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute([
            'command'     => $this->command->getName(),
            'coordinate'  => '40° 26.7717, -79° 56.93172',
            '--ellipsoid' => ' ',
        ]);
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

    public function testExecuteWithEllipsoidOption()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'coordinate'  => '40° 26.7717, -79° 56.93172',
            '--format'    => '%P%D:%M:%S, %p%d:%m:%s',
            '--ellipsoid' => 'BESSEL_1841',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/40:26:46, -79:56:56/', $this->commandTester->getDisplay());
    }
}
