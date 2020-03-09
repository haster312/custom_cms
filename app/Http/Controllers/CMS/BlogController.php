<?php

namespace App\Http\Controllers\CMS;


use App\Http\Controllers\Controller;
use App\Http\Traits\CustomRequest;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * Get cms blog pagination
     * @throws \App\Exceptions\JsonResponse
     */
    public function paginate()
    {
        $blogs = $this->blogService->getBlogPaginate();

        $this->pagination($blogs);
    }

    /**
     * Get cms blog detail
     * @param $id
     * @throws \App\Exceptions\JsonResponse
     */
    public function detail($id)
    {
        $blog = $this->blogService->getBlog($id);

        if (!$blog) {
            $this->error(messages('not_exist'));
        }

        $this->success($blog);
    }

    /**
     * Add cms blog
     * @param Request $request
     * @throws \App\Exceptions\JsonResponse
     */
    public function new(Request $request)
    {
        $data = $this->data($request);

        $blog = $this->blogService->addBlog($data);

        if (!$blog) {
            $this->error(messages('error'));
        }

        $this->success($blog, Response::HTTP_CREATED);
    }

    /**
     * Update cms blog
     * @param Request $request
     * @param $id
     * @throws \App\Exceptions\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $data = $this->data($request);

        $blog = $this->blogService->updateBlog($id, $data);

        if (!$blog) {
            $this->error(messages('error'));
        }

        $this->success($blog);
    }

    /**
     * delete cms blog
     * @param $id
     * @throws \App\Exceptions\JsonResponse
     */
    public function delete($id)
    {
        $deleted = $this->blogService->deleteBlog($id);

        if (!$deleted) {
            $this->error(messages('error'));
        }

        $this->success($deleted);
    }
}
