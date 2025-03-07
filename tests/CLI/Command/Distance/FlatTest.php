<?php

/*
 * This file is part of the Geotools library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Geotools\Tests\CLI\Command\Distance;

use League\Geotools\CLI\Application;
use League\Geotools\CLI\Command\Distance\Flat;
use League\Geotools\Exception\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class FlatTest extends \League\Geotools\Tests\TestCase
{
    protected $application;
    protected $command;
    protected $commandTester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->application = new Application;
        $this->application->add(new Flat);

        $this->command = $this->application->find('distance:flat');

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
            'command'     => $this->command->getName(),
            'origin'      => 'foo, bar',
            'destination' => ' ',
        ));
    }

    public function testExecuteWithoutOptions()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '40° 26.7717, -79° 56.93172',
            'destination' => '30°16′57″N 029°48′32″W',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/4690203\.0702905/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithKmOption()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '40° 26.7717, -79° 56.93172',
            'destination' => '30°16′57″N 029°48′32″W',
            '--km'        => true,
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/4690\.2030702905/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithMileOption()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '40° 26.7717, -79° 56.93172',
            'destination' => '30°16′57″N 029°48′32″W',
            '--mi'        => 'true',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/2914\.3570736216/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithFtOption()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '40° 26.7717, -79° 56.93172',
            'destination' => '30°16′57″N 029°48′32″W',
            '--ft'        => 'true',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/15387805\.348722/', $this->commandTester->getDisplay());
    }

    public function testExecuteWithEmptyEllipsoidOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '40° 26.7717, -79° 56.93172',
            'destination' => '30°16′57″N 029°48′32″W',
            '--ellipsoid' => ' ',
        ));
    }

    public function testExecuteWithoutAvailableEllipsoidOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '40° 26.7717, -79° 56.93172',
            'destination' => '30°16′57″N 029°48′32″W',
            '--ellipsoid' => 'foo',
        ));
    }

    public function testExecuteWithEllipsoidOption_MODIFIED_FISCHER_1960()
    {
        $this->commandTester->execute(array(
            'command'     => $this->command->getName(),
            'origin'      => '40° 26.7717, -79° 56.93172',
            'destination' => '30°16′57″N 029°48′32″W',
            '--ellipsoid' => 'MODIFIED_FISCHER_1960',
        ));

        $this->assertTrue(is_string($this->commandTester->getDisplay()));
        $this->assertMatchesRegularExpression('/4690217\.0420619/', $this->commandTester->getDisplay());
    }
}
