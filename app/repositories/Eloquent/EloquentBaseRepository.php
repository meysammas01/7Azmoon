<?php

namespace App\repositories\Eloquent;

use App\repositories\Contracts\RepositoryInterface;

class EloquentBaseRepository implements RepositoryInterface
{
    protected $model;

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update(int $id , array $data)
    {
        return $this->model::where('id', $id)->updata($data);
    }

    public function all(array $where)
    {
        $query = $this->model::query();
        foreach ($where as $key => $value)
        {
            $query->where($key, $value);
        }
        return $query->get();
    }

    public function delete(int $id)
    {
        return $this->model::where('id', $id)->delete();
    }

    public function find(int $id)
    {
        return $this->model::find($id);
    }

    public function deleteBy(array $where)
    {
        $query = $this->model::query();
        foreach ($where as $key => $value)
        {
            $query->where($key, $value);
        }
        return $query->delete();
    }
}
