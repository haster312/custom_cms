<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use BaseModel;
    protected $table = "blog";
    protected $fillable = ['title', 'slug', 'description', 'category'];

    public function Category()
    {
        return $this->belongsTo(BlogCategory::class, 'category', 'id');
    }
}
