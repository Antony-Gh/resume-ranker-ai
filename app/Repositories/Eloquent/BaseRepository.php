<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * Cache TTL in seconds (default: 60 minutes)
     *
     * @var int
     */
    protected int $cacheTtl = 3600;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all resources with optional caching
     *
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        $cacheKey = $this->getCacheKey('all', $columns);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($columns) {
            return $this->model->all($columns);
        });
    }

    /**
     * Get paginated resources
     *
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Create a resource
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        $model = $this->model->create($data);
        $this->clearCache();
        return $model;
    }

    /**
     * Update a resource
     *
     * @param array $data
     * @param int $id
     * @return Model
     */
    public function update(array $data, int $id): Model
    {
        $model = $this->find($id);
        $model->update($data);
        $this->clearCache();
        return $model;
    }

    /**
     * Delete a resource
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $result = $this->model->destroy($id);
        $this->clearCache();
        return $result > 0;
    }

    /**
     * Find a resource by id with optional caching
     *
     * @param int $id
     * @param array $columns
     * @return Model|null
     */
    public function find(int $id, array $columns = ['*']): ?Model
    {
        $cacheKey = $this->getCacheKey('find', ['id' => $id, 'columns' => $columns]);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($id, $columns) {
            return $this->model->find($id, $columns);
        });
    }

    /**
     * Find a resource by specific criteria with optional caching
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return Model|null
     */
    public function findBy(string $field, mixed $value, array $columns = ['*']): ?Model
    {
        $cacheKey = $this->getCacheKey('findBy', ['field' => $field, 'value' => $value, 'columns' => $columns]);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($field, $value, $columns) {
            return $this->model->where($field, $value)->first($columns);
        });
    }

    /**
     * Generate a cache key for the repository
     *
     * @param string $method
     * @param array $params
     * @return string
     */
    protected function getCacheKey(string $method, array $params = []): string
    {
        $modelClass = get_class($this->model);
        $paramsKey = md5(json_encode($params));

        return "repository.{$modelClass}.{$method}.{$paramsKey}";
    }

    /**
     * Clear all cache keys related to this repository
     *
     * @return void
     */
    protected function clearCache(): void
    {
        $modelClass = get_class($this->model);
        $pattern = "repository.{$modelClass}.*";

        // For simplicity, we're clearing all cache
        // In a production app, you might want to use a more targeted approach
        // such as using a cache tag system
        Cache::flush();
    }
}
