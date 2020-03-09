<?php

namespace App\Http\Controllers\CMS;


use App\Http\Controllers\Controller;
use App\Http\Traits\CustomRequest;
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use CustomRequest;
    private $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function modelObject()
    {
        $modelObject = $this->blogService->blogRepo->getModelObject();

        $this->success($modelObject);
    }

    /**
     * Get cms blog list
     * @throws \App\Exceptions\JsonResponse
     */
    public function list()
    {
        $blogs = $this->blogService->getBlogList();

        $this->pagination($blogs);
    }

    public function new(Request $request)
    {
        $data = $this->data($request);
    }

    public function update(Request $request, $id)
    {
        $data = $this->data($request);
    }
}
