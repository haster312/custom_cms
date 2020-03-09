<?php

namespace App\Repositories;
use App\Repositories\BaseRepository;
use App\Models\Blog;

class BlogRepository extends BaseRepository
{
    public function getModel()
    {
        return Blog::class;
    }
}
