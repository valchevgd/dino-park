<?php

namespace Tests\AppBundle\Factory;

use AppBundle\Entity\Dinosaur;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\TestCase;

class DinosaurFactoryTest extends TestCase
{
    /**
     * @var DinosaurFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new DinosaurFactory(
            $this->createMock(DinosaurLengthDeterminator::class)
        );
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
    public function testItGrowDinosaurFromASpecification(string $spec,  bool $expectedIdCarnivorous)
    {
        $dino = $this->factory->growFromSpecification($spec);

        $this->assertSame($expectedIdCarnivorous, $dino->isCarnivorous());
    }

    public function getSpecificationTest(): array
    {
        return [
            //specification, is carnivorous
            ['large carnivorous dinosaur', true],
            ['give me all the cookie', false],
            ['large herbivore', false],
        ];
    }
}
