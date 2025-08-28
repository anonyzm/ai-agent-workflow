<?php

namespace App\Temporal;

use App\Kernel\DeclarationLocator;

class WorkflowActivityLocator extends DeclarationLocator
{
    /**
     * Finds all activity declarations using Activity suffix.
     *
     * @return  \Generator
     */
    public function getActivityTypes(): \Generator
    {
        foreach ($this->getAvailableDeclarations() as $class) {
            if ($this->endsWith($class->getName(), 'Activity')) {
                yield $class->getName();
            }
        }
    }

    /**
     * Finds all workflow declarations using Workflow suffix.
     *
     * @return  \Generator
     */
    public function getWorkflowTypes(): \Generator
    {
        foreach ($this->getAvailableDeclarations() as $class) {
            if ($this->endsWith($class->getName(), 'Workflow')) {
                yield $class->getName();
            }
        }
    }

    
    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    private function endsWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }
}