<?php

namespace Tests\AppBundle\Factory;

use AppBundle\Entity\Dinosaur;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class DinosaurFactoryTest extends TestCase
{
    /**
     * @var DinosaurFactory
     */
    private $factory;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $lengthDeterminator;

    public function setUp()
    {
        $this->lengthDeterminator = $this->createMock(DinosaurLengthDeterminator::class);
        $this->factory = new DinosaurFactory(
            $this->lengthDeterminator
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
        $this->lengthDeterminator->expects($this->once())
            ->method('getLengthFromSpecification')
            ->with($spec)
            ->willReturn(20);

        $dino = $this->factory->growFromSpecification($spec);

        $this->assertSame($expectedIdCarnivorous, $dino->isCarnivorous());
        $this->assertSame(20, $dino->getLength());
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
