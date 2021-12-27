<?php

namespace AppBundle\Factory;

use AppBundle\Entity\Dinosaur;

class DinosaurFactory
{
    public function growVelociraptor(int $length): Dinosaur
    {
        return $this->createDinosaur('Velociraptor', true, $length);
    }

    public function growFromSpecification(string $specification): Dinosaur
    {
        $codeName = 'InG-' . random_int(1, 99999);
        $length = $this->getLengthFromSpecification($specification);
        $isCarnivorous = false;

        if (stripos($specification, 'carnivorous') !== false) {
            $isCarnivorous = true;
        }

        return $this->createDinosaur($codeName, $isCarnivorous, $length);
    }

    private function createDinosaur(string $genus, bool $isCarnivorous, int $length): Dinosaur
    {
        $dino = new Dinosaur($genus, $isCarnivorous);
        $dino->setLength($length);

        return $dino;
    }

    private function getLengthFromSpecification(string $specification): int
    {
        $availableLengths = [
            'huge' => ['min' => Dinosaur::HUGE, 'max' => 100],
            'omg' => ['min' => Dinosaur::HUGE, 'max' => 100],
            'icon' => ['min' => Dinosaur::HUGE, 'max' => 100],
            'large' => ['min' => Dinosaur::LARGE, 'max' => Dinosaur::HUGE - 1],
        ];
        $minLength = 1;
        $maxLength = Dinosaur::LARGE - 1;

        foreach (explode(' ', $specification) as $keyword) {
            $keyword = strtolower($keyword);

            if (array_key_exists($keyword, $availableLengths)) {
                $minLength = $availableLengths[$keyword]['min'];
                $maxLength = $availableLengths[$keyword]['max'];

                break;
            }
        }

        return random_int($minLength, $maxLength);
    }
}
