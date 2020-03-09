<?php
namespace App\Services;


use App\Helpers\CacheHelper;

class BaseService
{
    public $size = 10;
    public $page = 1;
    public $key;
    public $cacheHelper;

    public function __construct()
    {
        $this->page = \request()->query('page') ? \request()->query('page') : 1;
        $this->size = \request()->query('size') ? \request()->query('size') : 10;
        $this->key = \request()->query('key') ? \request()->query('key') : null;

        $this->cacheHelper = new CacheHelper();
    }

    public function getPaginationData($key)
    {
        if ($this->page == 1) {
            return false;
        }

        return $this->cacheHelper->getCache($key, $this->page, $this->size);
    }

    /**
     * Base pagination with cache
     * @param $key
     * @param $data
     * @return array
     */
    public function paginationWithCache($key, $data)
    {
        // Set cache data
        $this->cacheHelper->setCache($key, $data);

        // Get cache data
        return $this->cacheHelper->getCache($key, $this->page, $this->size);
    }
}
