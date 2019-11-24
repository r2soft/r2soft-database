<?php


namespace R2Soft\R2Database\Contracts;


interface CriteriaCollection
{
    public function addCriteria(CriteriaInterface $criteria);

    public function getCriteriaCollection();

    public function getByCriteria(CriteriaInterface $criteriaInterface);

    public function applyCriteria();

    public function ignoreCriteria($isIgnore=true);

    public function clearCriteria();
}
