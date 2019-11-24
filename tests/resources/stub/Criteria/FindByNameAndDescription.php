<?php


namespace R2soft\R2Database\Criteria;


use Illuminate\Database\Eloquent\Model;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Contracts\RepositoryInterface;

class FindByNameAndDescription implements CriteriaInterface
{
    private $name;
    private $description;

    public function __construct($name, $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('name', $this->name)
            ->where('description', $this->description);
    }
}
