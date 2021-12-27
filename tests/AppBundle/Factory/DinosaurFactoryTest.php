<?php

namespace Tests\AppBundle\Factory;

use AppBundle\Entity\Dinosaur;
use AppBundle\Factory\DinosaurFactory;
use PHPUnit\Framework\TestCase;

class DinosaurFactoryTest extends TestCase
{
    /**
     * @var DinosaurFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new DinosaurFactory();
    }

    public function testItGrowAVelociraptor()
    {
        $dino = $this->factory->growVelociraptor(5);

        $this->assertInstanceOf(Dinosaur::class, $dino);
        $this->assertInternalType('string', $dino->getGenus());
        $this->assertSame('Velociraptor', $dino->getGenus());
        $this->assertSame(5, $dino->getLength());
    }

    public function testItGrowATriceratops()
    {
        $this->markTestIncomplete('Waiting for confirmation from GenLab');
    }

    public function testItGrowABabyVelociraptor()
    {
        if (!class_exists('Nanny')) {
            $this->markTestSkipped('There is nobody to watch the baby');
        }

        $dino = $this->factory->growVelociraptor(1);
        $this->assertSame(1, $dino->getLength());
    }

    /**
     * @dataProvider getSpecificationTest
     */
    public function testItGrowDinosaurFromASpecification(string $spec, bool $expectedIsLarge, bool $expectedIdCarnivorous)
    {
        $dino = $this->factory->growFromSpecification($spec);

        if ($expectedIsLarge) {
            $this->assertGreaterThanOrEqual(Dinosaur::LARGE, $dino->getLength());
        } else {
            $this->assertLessThan(Dinosaur::LARGE, $dino->getLength());
        }


        $this->assertSame($expectedIdCarnivorous, $dino->isCarnivorous());
    }

    public function getSpecificationTest(): array
    {
        return [
            //specification, is large, is carnivorous
            ['large carnivorous dinosaur', true, true],
            ['give me all the cookie', false, false],
            ['large herbivore', true, false],
        ];
    }

    /**
     * @dataProvider getHugeDinosaurSpecTest
     */
    public function testItGrowsAHugeDinosaur(string $spec)
    {
        $dino = $this->factory->growFromSpecification($spec);
        $this->assertGreaterThanOrEqual(Dinosaur::HUGE, $dino->getLength());
    }

    public function getHugeDinosaurSpecTest(): array
    {
        return [
            ['huge dinosaur'],
            ['huge dino'],
            ['huge'],
            ['OMG'],
            ['icon'],
        ];
    }
}
