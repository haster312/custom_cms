<?php

namespace App\Repositories;
use App\Repositories\BaseRepository;
use App\Models\BlogCategory;

class BlogCategoryRepository extends BaseRepository
{
    public function getModel()
    {
        return BlogCategory::class;
    }
    
}
