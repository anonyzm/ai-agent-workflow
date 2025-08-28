<?php

namespace App\Kernel;

use Spiral\Tokenizer\ClassesInterface;
use Spiral\Tokenizer\ClassLocator;
use Symfony\Component\Finder\Finder;

class DeclarationLocator
{
    private ClassesInterface $classLocator;

    /**
     * @return \Generator|\ReflectionClass[]
     */
    public function getAvailableDeclarations(): \Generator
    {
        foreach ($this->classLocator->getClasses() as $class) {
            if ($class->isAbstract() || $class->isInterface()) {
                continue;
            }

            yield $class;
        }
    }

    /**
     * @param string $dir
     * @return $this
     */
    public static function create(string $dir): self
    {
        $locator = new self();
        $locator->classLocator = new ClassLocator(
            Finder::create()->files()->in($dir)
        );

        return $locator;
    }


}