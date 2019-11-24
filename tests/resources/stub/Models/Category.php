<?php
namespace R2Soft\R2Database\Models;



use Illuminate\Database\Eloquent\Model;


class Category extends Model
{

    protected $table = "code_categories";
    protected $fillable = [
        'name',
        'description',
    ];
}
