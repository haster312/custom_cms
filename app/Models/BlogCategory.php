<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use BaseModel;
    protected $table = "blog_category";
    protected $fillable = ['name', 'icon'];
}
