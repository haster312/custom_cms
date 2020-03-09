<?php

namespace App\Services;


use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogRepository;

class BlogService extends BaseService
{
    public $blogRepo;
    public $blogCategoryRepo;

    public function __construct(
        BlogRepository $blogRepository,
        BlogCategoryRepository $blogCategoryRepository
    )
    {
        parent::__construct();
        $this->blogRepo = $blogRepository;
        $this->blogCategoryRepo = $blogCategoryRepository;
    }

    public function getBlogList()
    {
        return $this->blogRepo->model->orderBy('updated_at', 'DESC')->paginate($this->size)->toArray();
    }
}
