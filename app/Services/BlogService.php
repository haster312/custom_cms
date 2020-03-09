<?php

namespace App\Services;


use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogRepository;
use Illuminate\Support\Facades\DB;

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

    public function getBlogPaginate()
    {
        $blogs =  $this->blogRepo->model->orderBy('updated_at', 'DESC')->paginate($this->size);

        foreach($blogs->items() as $blog) {
            $blog->category = $blog->Category ? $blog->Category->name : null;
            unset($blog->Category);
        }

        return $blogs->toArray();
    }

    public function getBlog($id)
    {
        return $this->blogRepo->getModelById($id);
    }

    public function addBlog($data)
    {
        return $this->blogRepo->create($data);
    }

    public function updateBlog($id, $data)
    {
        return $this->blogRepo->update($id, $data);
    }

    public function deleteBlog($id)
    {
        $blog = $this->blogRepo->getModelById($id);

        if (!$blog) {
            return false;
        }

        return $this->blogRepo->destroy($id);
    }
}
