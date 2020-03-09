<?php

namespace App\Http\Controllers\CMS;


use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCategoryRequest;
use App\Http\Traits\CustomRequest;
use App\Services\BlogService;
use Illuminate\Http\Response;

class BlogCategoryController extends Controller
{
    use CustomRequest;
    private $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function list()
    {

    }

    /**
     * Add new model
     * @param BlogCategoryRequest $request
     * @throws \App\Exceptions\JsonResponse
     */
    public function new(BlogCategoryRequest $request)
    {
        $data = $this->data($request);

        $category = $this->blogService->blogCategoryRepo->create($data);

        if (!$category) {
            $this->error(messages('error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->success($category);
    }

    /**
     * Update model
     * @param BlogCategoryRequest $request
     * @param $id
     * @throws \App\Exceptions\JsonResponse
     */
    public function update(BlogCategoryRequest $request, $id)
    {
        $data = $this->data($request);

        $category = $this->blogService->blogCategoryRepo->update($id, $data);

        if (!$category) {
            $this->error(messages('error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->success($category);
    }
}
