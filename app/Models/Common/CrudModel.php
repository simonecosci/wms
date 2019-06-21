<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * @abstract Base class for AR CRUD Models
 */
abstract class CrudModel extends Model {

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     *
     * @var string
     */
    protected $uploadFolder;

    /**
     * 
     * @param array $attributes
     * @param array $options
     * @return $this
     */
    public function create(array $attributes = [], array $options = []) {
        $this->fill($attributes)->save($options);
        return $this;
    }

    /**
     * 
     * @param array $attributes
     * @param array $options
     * @return $this
     */
    public function update(array $attributes = [], array $options = []) {
        if (!$this->exists) {
            return;
        }
        $this->fill($attributes)->save($options);
        return $this;
    }

    /**
     * 
     * @param object $options
     * @return array
     */
    public function read($options = null) {
        $query = static::query();
        if (!isset($options->pageSize)) {
            return $query->get();
        }
        if (isset($options->filter)) {
            $filters = json_decode($options->filter);
            if (!empty($filters)) {
                $this->_filter($query, $filters);
            }
        }
        $total = $query->count();
        if (isset($options->sort)) {
            $sort = json_decode($options->sort);
            if (is_object($sort) || (is_array($sort) && count($sort) > 0)) {
                $this->_orderBy($query, $sort);
            }
        }
        if (!empty($options->pageSize)) {
            $query->offset(($options->page - 1) * $options->pageSize);
            $query->limit($options->pageSize);
            return [
                'data' => $query->get(),
                'page' => intval($options->page),
                'total' => $total
            ];
        }
        return $query->get();
    }

    /**
     * 
     * @param Builder $query
     * @param object $filters
     */
    protected function _filter(Builder $query, $filters) {
        $logic = "and";
        if (is_object($filters)) {
            $logic = $filters->logic;
            $filters = $filters->filters;
        }
        if ($logic === "and") {
            $method = 'where';
        }
        if ($logic === "or") {
            $method = 'orWhere';
        }
        $query->$method(function ($query) use ($filters) {
            foreach ($filters as $filter) {
                if (is_array($filter) || isset($filter->logic)) {
                    $this->_filter($query, $filter);
                    continue;
                }
                $this->_applyFilter($query, $filter);
            }
        });
    }

    protected function _applyFilter(Builder $query, $filter) {
        $exp = explode('.', $filter->field);
        if (count($exp) > 1) {
            $f = array_pop($exp);
            $query->whereHas(implode('.', $exp), function($query) use ($exp, $f, $filter) {
                $_filter = clone $filter;
                $_filter->field = $f;
                $this->_applyFilter($query, $_filter);
            });
        } else {
            $this->_applyWhere($query, $filter);
        }
    }

    /**
     * 
     * @param QueryBuilder $query
     * @param object $filter
     */
    protected function _applyWhere(Builder $query, $filter) {
        switch ($filter->operator) {
            case 'eq':
                $query->where($filter->field, '=', $filter->value);
                break;
            case 'neq':
                $query->where($filter->field, '!=', $filter->value);
                break;
            case 'isnull':
                $query->whereNull($filter->field);
                break;
            case 'isnotnull':
                $query->whereNotNull($filter->field);
                break;
            case 'lt':
                $query->where($filter->field, '<', $filter->value);
                break;
            case 'lte':
                $query->where($filter->field, '<=', $filter->value);
                break;
            case 'gt':
                $query->where($filter->field, '>', $filter->value);
                break;
            case 'gte':
                $query->where($filter->field, '>=', $filter->value);
                break;
            case 'startswith':
                if (strpos($filter->field, '->') === false) {
                    $query->where($filter->field, 'LIKE', $filter->value . '%');
                } else {
                    $f = explode('->', $filter->field);
                    $f = array_map(function($el) {
                        return DB::connection()->getPdo()->quote($el);
                    }, $f);
                    $query->whereRaw('JSON_SEARCH(' . $f[0] . '->"$.' . $f[1] . '", "all", "' . DB::connection()
                        ->getPdo()
                        ->quote($filter->value) . '%") IS NOT NULL');
                }
                break;
            case 'endswith':
                if (strpos($filter->field, '->') === false) {
                    $query->where($filter->field, 'LIKE', '%' . $filter->value);
                } else {
                    $f = explode('->', $filter->field);
                    $f = array_map(function($el) {
                        return DB::connection()->getPdo()->quote($el);
                    }, $f);
                    $query->whereRaw('JSON_SEARCH(' . $f[0] . '->"$.' . $f[1] . '", "all", "%' . DB::connection()
                        ->getPdo()
                        ->quote($filter->value) . '") IS NOT NULL');
                }
                break;
            case 'contains':
                if (strpos($filter->field, '->') === false) {
                    $query->where($filter->field, 'LIKE', '%' . $filter->value . '%');
                } else {
                    $f = explode('->', $filter->field);
                    $f = array_map(function($el) {
                        return DB::connection()->getPdo()->quote($el);
                    }, $f);
                    $query->whereRaw('JSON_CONTAINS(' . $f[0] . '->"$.' . $f[1] . '", "all", "' . DB::connection()
                        ->getPdo()
                        ->quote($filter->value) . '") IS NOT NULL');
                }
                break;
            case 'doesnotcontain':
                if (strpos($filter->field, '->') === false) {
                    $query->where($filter->field, 'NOT LIKE', '%' . $filter->value . '%');
                } else {
                    $f = explode('->', $filter->field);
                    $f = array_map(function($el) {
                        return DB::connection()->getPdo()->quote($el);
                    }, $f);
                    $query->whereRaw('JSON_CONTAINS(' . $f[0] . '->"$.' . $f[1] . '", "all", "' . DB::connection()
                        ->getPdo()
                        ->quote($filter->value) . '") IS NULL');
                }
                break;
            case 'isempty':
                $query->where($filter->field, '=', '');
                break;
            case 'isnotempty':
                $query->where($filter->field, '!=', '');
                break;
        }
    }

    /**
     * 
     * @param Builder $query
     * @param string $sort
     */
    protected function _orderBy(Builder $query, $sort) {
        if (!is_array($sort)) {
            $sort = [$sort];
        }
        foreach ($sort as $orderBy) {
            $exp = explode('.', $orderBy->field);
            if (count($exp) > 1) {
                do {
                    $f = array_pop($exp);
                    $query->whereHas(implode('.', $exp), function($query) use ($f, $orderBy) {
                        $_orderBy = clone $orderBy;
                        $_orderBy->field = $f;
                        $query->orderBy($_orderBy->field, $orderBy->dir);
                    });
                } while (count($exp) > 1);
            } else {
                $query->orderBy($orderBy->field, $orderBy->dir);
            }
        }
    }

    /**
     * Gets the upload folder
     * @return string
     */
    public function getUploadFolder() {
        return $this->uploadFolder;
    }

    /**
     * 
     * @param string $uploadFolder
     * @return $this
     */
    public function setUploadFolder($uploadFolder) {
        $this->uploadFolder = $uploadFolder;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getPrimaryKey() {
        return $this->primaryKey;
    }

    public function __construct(array $attributes = []) {
        return parent::__construct($attributes);
    }

}
