<?php

/*
 * This file is part of the Geotools library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Geotools\Tests\Distance;

use League\Geotools\Coordinate\Ellipsoid;
use League\Geotools\Distance\Distance;
use League\Geotools\Tests\TestCase;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class DistanceTest extends TestCase
{
    protected $distance;
    protected $from;
    protected $to;
    protected $coordA;
    protected $coordB;

    protected function setUp(): void
    {
        parent::setUp();
        $this->distance = new TestableDistance;
        $this->from     = $this->getStubCoordinate();
        $this->to       = $this->getStubCoordinate();
        $this->coordA   = array(48.8234055, 2.3072664);
        $this->coordB   = array(43.296482, 5.36978);
    }

    public function testSetFromValueShouldBeACoordinateInterface()
    {
        $this->distance->setFrom($this->from);
        $from = $this->distance->getFrom();

        $this->assertTrue(is_object($from));
        $this->assertInstanceOf('League\Geotools\Coordinate\CoordinateInterface', $from);
    }

    public function testSetFromShouldReturnTheSameDistanceInstance()
    {
        $distance = $this->distance->setFrom($this->from);

        $this->assertTrue(is_object($distance));
        $this->assertInstanceOf('League\Geotools\Distance\Distance', $distance);
        $this->assertInstanceOf('League\Geotools\Distance\DistanceInterface', $distance);
        $this->assertSame($this->distance, $distance);
    }

    public function testSetToValueShouldBeACoordinateInterface()
    {
        $this->distance->setTo($this->to);
        $to = $this->distance->getTo();

        $this->assertTrue(is_object($to));
        $this->assertInstanceOf('League\Geotools\Coordinate\CoordinateInterface', $to);
    }

    public function testSetToShouldReturnTheSameDistanceInstance()
    {
        $distance = $this->distance->setTo($this->to);

        $this->assertTrue(is_object($distance));
        $this->assertInstanceOf('League\Geotools\Distance\Distance', $distance);
        $this->assertInstanceOf('League\Geotools\Distance\DistanceInterface', $distance);
        $this->assertSame($this->distance, $distance);
    }

    public function testIn()
    {
        $distance = $this->distance->in('foo');

        $this->assertSame('foo', $distance->getIn());
    }

    public function testInShouldReturnTheSameDistanceInstance()
    {
        $distance = $this->distance->in('foo');

        $this->assertTrue(is_object($distance));
        $this->assertInstanceOf('League\Geotools\Distance\Distance', $distance);
        $this->assertInstanceOf('League\Geotools\Distance\DistanceInterface', $distance);
        $this->assertSame($this->distance, $distance);
    }

    /**
     * @dataProvider ellipsoidInstanceAndExpectedResultProvider
     */
    public function testFlatDistance($ellipsoid, $result)
    {
        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['flat']['m'], $this->distance->flat(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['flat']['km'], $this->distance->in('km')->flat(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['flat']['mi'], $this->distance->in('mi')->flat(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['flat']['ft'], $this->distance->in('ft')->flat(), 0.00001);
    }

    /**
     * @dataProvider ellipsoidInstanceAndExpectedResultProvider
     */
    public function testGreatCircleDistance($ellipsoid, $result)
    {
        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['greatCircle']['m'], $this->distance->greatCircle(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['greatCircle']['km'], $this->distance->in('km')->greatCircle(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['greatCircle']['mi'], $this->distance->in('mi')->greatCircle(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['greatCircle']['ft'], $this->distance->in('ft')->greatCircle(), 0.00001);
    }

    /**
     * @dataProvider ellipsoidInstanceAndExpectedResultProvider
     */
    public function testHaversineDistance($ellipsoid, $result)
    {
        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['haversine']['m'], $this->distance->haversine(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['haversine']['km'], $this->distance->in('km')->haversine(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['haversine']['mi'], $this->distance->in('mi')->haversine(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['haversine']['ft'], $this->distance->in('ft')->haversine(), 0.00001);
    }

    /**
     * @dataProvider ellipsoidInstanceAndExpectedResultProvider
     */
    public function testVincentyDistance($ellipsoid, $result)
    {
        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['vincenty']['m'], $this->distance->vincenty(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['vincenty']['km'], $this->distance->in('km')->vincenty(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['vincenty']['mi'], $this->distance->in('mi')->vincenty(), 0.00001);

        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordB, $ellipsoid));
        $this->assertEqualsWithDelta($result['vincenty']['ft'], $this->distance->in('ft')->vincenty(), 0.00001);
    }

    public static function ellipsoidInstanceAndExpectedResultProvider(): array
    {
        return [
            [
                Ellipsoid::createFromName(Ellipsoid::WGS84),
                [
                    'flat' => [
                        'm'  => 659166.5003874175,
                        'km' => 659.1665003874175,
                        'mi' => 409.5870742286407,
                        'ft' => 2162619.7519272,
                    ],
                    'greatCircle' => [
                        'm'  => 659021.9081284694,
                        'km' => 659.0219081284694,
                        'mi' => 409.49722876431,
                        'ft' => 2162145.3678755,
                    ],
                    'haversine' => [
                        'm'  => 659021.9081284694,
                        'km' => 659.02190812846,
                        'mi' => 409.49722876431,
                        'ft' => 2162145.3678755,
                    ],
                    'vincenty' => [
                        'm'  => 658307.48497307,
                        'km' => 658.30748497307,
                        'mi' => 409.05330679648,
                        'ft' => 2159801.4598854,
                    ],
                ],
            ],
            [
                Ellipsoid::createFromName(Ellipsoid::GRS_1980),
                [
                    'flat' => [
                        'm'  => 659166.6037352452,
                        'km' => 659.1666037352452,
                        'mi' => 409.58713844600356,
                        'ft' => 2162620.0909949,
                    ],
                    'greatCircle' => [
                        'm'  => 659022.011453627,
                        'km' => 659.022011453627,
                        'mi' => 409.49729296758,
                        'ft' => 2162145.7068688,
                    ],
                    'haversine' => [
                        'm'  => 659022.01145362,
                        'km' => 659.022011453627,
                        'mi' => 409.49729296758,
                        'ft' => 2162145.7068688,
                    ],
                    'vincenty' => [
                        'm'  => 658307.58818269,
                        'km' => 658.30758818269,
                        'mi' => 409.05337092796,
                        'ft' => 2159801.7984996,
                    ],
                ],
            ],
            [
                Ellipsoid::createFromName(Ellipsoid::CLARKE_1880),
                [
                    'flat' => [
                        'm'  => 659178.1936773828,
                        'km' => 659.1781936773828,
                        'mi' => 409.59434010216756,
                        'ft' => 2162658.1157394,
                    ],
                    'greatCircle' => [
                        'm'  => 659033.598853439,
                        'km' => 659.033598853439,
                        'mi' => 409.50449304402,
                        'ft' => 2162183.7232724,
                    ],
                    'haversine' => [
                        'm'  => 659033.59885343,
                        'km' => 659.033598853439,
                        'mi' => 409.50449304402,
                        'ft' => 2162183.7232724,
                    ],
                    'vincenty' => [
                        'm'  => 658307.4119689,
                        'km' => 658.3074119689,
                        'mi' => 409.05326143379,
                        'ft' => 2159801.2203704,
                    ],
                ],
            ],
            [
                Ellipsoid::createFromName(Ellipsoid::HOUGH),
                [
                    'flat' => [
                        'm'  => 659180.3489963295,
                        'km' => 659.1803489963295,
                        'mi' => 409.5956793552711,
                        'ft' => 2162665.1869958,
                    ],
                    'greatCircle' => [
                        'm'  => 659035.7536996031,
                        'km' => 659.0357536996,
                        'mi' => 409.50583200335,
                        'ft' => 2162190.7929777,
                    ],
                    'haversine' => [
                        'm'  => 659035.7536996031,
                        'km' => 659.0357536996,
                        'mi' => 409.50583200335,
                        'ft' => 2162190.7929777,
                    ],
                    'vincenty' => [
                        'm'  => 658318.26962941,
                        'km' => 658.31826962941,
                        'mi' => 409.06000807124,
                        'ft' => 2159836.8426162,
                    ],
                ],
            ],
        ];
    }

    public function testVincentyDistanceCoIncidentPoint()
    {
        $ellipsoid = Ellipsoid::createFromName(Ellipsoid::WGS84);
        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordA, $ellipsoid));

        $this->assertSame(0.0, $this->distance->vincenty());
    }

    public function testFlatDistanceWithSameCoordinate()
    {
        $ellipsoid = Ellipsoid::createFromName(Ellipsoid::WGS84);
        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordA, $ellipsoid));

        $this->assertSame(0.0, $this->distance->flat());
    }

    public function testGreatCircleDistanceWithSameCoordinate()
    {
        $ellipsoid = Ellipsoid::createFromName(Ellipsoid::WGS84);
        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordA, $ellipsoid));

        $this->assertSame(0.0, $this->distance->greatCircle());
    }

    public function testHaversineDistanceWithSameCoordinate()
    {
        $ellipsoid = Ellipsoid::createFromName(Ellipsoid::WGS84);
        $this->distance->setFrom($this->getMockCoordinateReturns($this->coordA, $ellipsoid));
        $this->distance->setTo($this->getMockCoordinateReturns($this->coordA, $ellipsoid));

        $this->assertSame(0.0, $this->distance->haversine());
    }
}

class TestableDistance extends Distance
{
    public function getIn()
    {
        return $this->unit;
    }
}
