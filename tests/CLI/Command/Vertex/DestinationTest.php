<?php

/*
 * This file is part of the Geotools library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Geotools\Tests\CLI\Command\Vertex;

use League\Geotools\CLI\Application;
use League\Geotools\CLI\Command\Vertex\Destination;
use League\Geotools\Exception\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class DestinationTest extends \League\Geotools\Tests\TestCase
{
    protected $application;
    protected $command;
    protected $commandTester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->application = new Application;
        $this->application->add(new Destination);

        $this->command = $this->application->find('vertex:destination');

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
            'command'  => $this->command->getName(),
            'origin'   => 'foo, bar',
            'bearing'  => ' ',
            'distance' => '',
        ));
    }

    public function testExecute()
    {
        $this->commandTester->execute(array(
            'command'  => $this->command->getName(),
            'origin'   => '48.8234055, 2.3072664',
            'bearing'  => 180,
            'distance' => 200000,
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/47\.026774650075, 2\.3072664/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithEmptyEllipsoidOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '48.8234055, 2.3072664',
            'bearing'     => 180,
            'distance'    => 200000,
            '--ellipsoid' => ' ',
        ));
    }

    public function testExecuteWithoutAvailableEllipsoidOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '48.8234055, 2.3072664',
            'bearing'     => 180,
            'distance'    => 200000,
            '--ellipsoid' => 'foo',
        ));
    }

    public function testExecuteWithEllipsoid_GRS_1980()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '48.8234055, 2.3072664',
            'bearing'     => 110,
            'distance'    => 2000000,
            '--ellipsoid' => 'GRS_1980',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/40\.279971519453, 24\.637336894406/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithEllipsoid_AUSTRALIAN_NATIONAL()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '48.8234055, 2.3072664',
            'bearing'     => 110,
            'distance'    => 2000000,
            '--ellipsoid' => 'AUSTRALIAN_NATIONAL',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/40\.280009426711, 24\.637268024987/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithEllipsoid_BESSEL_1841()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '48.8234055, 2.3072664',
            'bearing'     => 110,
            'distance'    => 2000000,
            '--ellipsoid' => 'BESSEL_1841',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/40\.278751982466, 24\.639552452771/', $this->commandTester->getDisplay());
    }
}
