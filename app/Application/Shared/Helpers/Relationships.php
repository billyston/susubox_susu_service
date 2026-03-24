<?php

namespace App\Application\Shared\Helpers;

trait Relationships
{
    protected array $relationships = [];

    /**
     * @return bool
     */
    public function loadRelationships(
    ): bool {
        return ! empty($this->relationships);
    }
}
