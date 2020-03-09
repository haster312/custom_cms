<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Blog extends BaseModel
{
    protected $table = "blog";
    protected $fillable = ['title', 'slug', 'description', 'category', 'status'];

    public function Category()
    {
        return $this->belongsTo(BlogCategory::class, 'category', 'id');
    }
}
