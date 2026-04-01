<?php

namespace R2Soft\R2Database\Contracts;

interface CriteriaCollection
{
    public function addCriteria(CriteriaInterface $criteria): static;

    public function getCriteriaCollection(): array;

    public function getByCriteria(CriteriaInterface $criteriaInterface): static;

    public function applyCriteria(): static;

    public function ignoreCriteria(bool $isIgnore = true): static;

    public function clearCriteria(): static;
}
