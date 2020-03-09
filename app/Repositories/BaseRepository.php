<?php
namespace App\Repositories;


abstract class BaseRepository
{
    public $model;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * get model
     * @return string
     */
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    /**
     * Get model
     * @return mixed
     */
    public function model()
    {
        return $this->model;
    }

    public function getModelObject()
    {
        $fillable = $this->model->getFillable();
        $modelObject = [];

        foreach ($fillable as $field) {
            $modelObject[$field] = null;
        }

        return $modelObject;
    }

    /**
     * Create new model
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * Update model
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $update = $this->getModelById($id);

        if ($update) {
            $update->update($data);
            return $this->getModelById($id);
        }

        return null;
    }

    public function queryByFields($conditions)
    {
        $queryBuilder = $this->model->query();

        if (count($conditions) > 0) {
            $this->queryBuilder($queryBuilder, $conditions);
        }

        return $queryBuilder;
    }

    /**
     * Update model by fields
     * @param $data
     * @param $conditions
     * @return mixed
     */
    public function updateByFields($data, $conditions)
    {
        $queryBuilder = $this->model->query();

        if (count($conditions) > 0) {
            $this->queryBuilder($queryBuilder, $conditions);

            return $queryBuilder->update($data);
        }

        return $this->model->update($data);
    }

    /**
     * Destroy model
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Find all models
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get model by id
     * @param $id
     * @return mixed
     */
    public function getModelById($id)
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * Get model by field
     * @param $field
     * @param $value
     * @param $first
     * @return mixed
     */
    public function getModelByField($field, $value, $first = false)
    {
        //Add condition
        $queryBuilder = $this->model->where($field, '=', $value);

        if ($first) {
            return $queryBuilder->first();
        }

        return $queryBuilder->get();
    }

    /**
     * Get model by multi fields
     * 1: field name; 2: operator; 3: value
     * @param $conditions
     * @param $first
     * @return mixed
     */
    public function getModelByFields(array $conditions = [], $first = false)
    {
        $queryBuilder = $this->model->query();

        //Add conditions
        if (count($conditions) > 0) {
            $this->queryBuilder($queryBuilder, $conditions);

            if ($first) {
                return $queryBuilder->first();
            }

            return $queryBuilder->get();
        }

        return null;
    }

    /**
     * Delete model by field
     * @param $field
     * @param $value
     * @return mixed
     */
    public function deleteModelByField($field, $value)
    {
        return $this->model->where($field, $value)->delete();
    }

    /**
     * Delete model by multi fields
     * @param array $conditions
     * @return bool
     */
    public function deleteModelByFields(array $conditions = [])
    {
        $queryBuilder = $this->model->query();

        //Add conditions
        if (count($conditions) > 0) {
            foreach ($conditions as $condition) {
                if (count($condition) == 2) {
                    $queryBuilder->where($condition[0], $condition[1]);
                } elseif (count($condition) === 3) {
                    $operator = strtoupper($condition[1]);

                    switch ($operator) {
                        case 'NULL':
                            $queryBuilder->whereNull($condition[0]);
                            break;
                        case 'NOT_NULL':
                            $queryBuilder->whereNotNull($condition[0]);
                            break;
                        case 'IN':
                            $queryBuilder->whereIn($condition[0], $condition[2]);
                            break;
                        case 'NOT_IN':
                            $queryBuilder->whereNotIn($condition[0], $condition[2]);
                            break;
                        case 'OR':
                            $queryBuilder->orWhere($condition[0], $condition[2]);
                            break;
                        case 'OR_IN':
                            $queryBuilder->orWhereIn($condition[0], $condition[2]);
                            break;
                        case 'OR_NULL':
                            $queryBuilder->orWhereNull($condition[0]);
                            break;
                        default:
                            $queryBuilder->where($condition[0], $operator, $condition[2]);
                            break;
                    }
                }
            }

            return $queryBuilder->delete();
        }

        return false;
    }

    /**
     * Build simple query statement with array of conditions
     * @param $queryBuilder
     * @param $conditions
     */
    public function queryBuilder(&$queryBuilder, $conditions)
    {
        foreach ($conditions as $condition) {
            if (count($condition) == 2) {
                $operator = strtoupper($condition[1]);
                switch ($operator) {
                    case 'NOT_NULL':
                        $queryBuilder->whereNotNull($condition[0]);
                        break;
                    case 'NULL':
                        $queryBuilder->whereNull($condition[0]);
                        break;
                    default:
                        $queryBuilder->where($condition[0], $condition[1]);
                        break;
                }

            } elseif (count($condition) === 3) {
                $operator = strtoupper($condition[1]);
                switch ($operator) {
                    case 'IN':
                        $queryBuilder->whereIn($condition[0], $condition[2]);
                        break;
                    case 'NOT_IN':
                        $queryBuilder->whereNotIn($condition[0], $condition[2]);
                        break;
                    case 'OR':
                        $queryBuilder->orWhere($condition[0], $condition[2]);
                        break;
                    case 'OR_IN':
                        $queryBuilder->orWhereIn($condition[0], $condition[2]);
                        break;
                    default:
                        $queryBuilder->where($condition[0], $operator, $condition[2]);
                        break;
                }
            }
        }
    }
}
