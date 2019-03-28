<?php
namespace sanabuk\larafast;

use Illuminate\Http\Request;

/**
 * Larafast
 *
 * Test to generate Eloquent Request with Url Parameters
 *
 * example Route : 
 * ?model=driver&conditions=max=id:100,...,&output=...
 * */

class Larafast
{
    protected $asked_model;

    public function __construct()
    {
        $this->asked_model = '';
    }

    /**
     * @param array $url_parameters
     * @param Builder $query
     * @return Builder
     */ 
    public function getDatas($url_parameters)
    {
        $this->asked_model = $url_parameters['model'];
        $class = config('larafast.models.'.$this->asked_model);
        $query = new $class;
        $parser           = new ParentheseParser();
        $conditions       = $parser->generate($url_parameters['conditions']);
        $output           = $parser->generate($url_parameters['output']);

        $query = $this->handlingConditions($query, $conditions);
        $query = $this->handlingFormat($query, $output);
        return $query;
    }

    /**
     * Gestion des conditions de la requête
     * Eloquent whereHas
     * @param Builder $query
     * @param array $conditions
     * @return Builder $query
     */
    protected function handlingConditions($query, $conditions)
    {
        foreach ($conditions as $key => $value) {
            if (is_integer($key)) {
                // Conditions sur le modèle de base de la requête
                list($type, $condition)  = $this->getConditionType($value);
                list($needle, $haystack) = explode(':', $condition);
                $query                   = $this->checkTypeAndApplyCondition($query, $type, $needle, $haystack);
            } else {
                // Condition sur une relation
                $relation = $key;
                $negation = $relation[0] == "!" ? true : false;
                $query    = $this->constrainsWhereHas($query, trim($relation, '!'), $value, $negation);
            }
        }
        return $query;
    }

    /**
     * Gestion du format de sortie de la requête
     * Eloquent EagerLoad
     * @param Builder $query
     * @param array $format
     * @return Builder $query
     */
    protected function handlingFormat($query, $format)
    {
        $selectArray = config('larafast.'.$this->asked_model);
        foreach ($format as $key => $value) {
            if (is_integer($key)) {
                // Conditions sur le modèle de base de la requête
                if (preg_match('/(?<type>.*)=(?<column>.*)/', $value, $matches)==1) {
                    if($this->isSort($matches['type'])){
                        $column  = trim($matches['column'], '-');
                        $operator = $matches['column'][0] == '-' ? 'DESC' : 'ASC';
                        $query    = $this->addSort($query, $column, $operator);
                    }
                } else {
                    $selectArray[] = $value;
                }
            } else {
                // Condition sur une relation
                $relation = $key;
                $query    = $this->addEagerLoadRelation($query, $relation, $value);
            }
        }
        $query = $this->addSelect($query, $selectArray);
        return $query;
    }

    /**
     * Add Eager Load
     * @param Builder $query
     * @param string $relation
     * @param array $param
     * @param int $counter
     * @return Builder
     */
    protected function addEagerLoadRelation($query, $relation, $param, $counter = 0)
    {
        $query = $query->with([$relation => $this->getCallback($relation, $counter, $param)]);
        return $query;
    }

    /**
     * Get callback function for eager load
     * @param string $relation
     * @param int $counter
     * @param array $param
     * @return function
     */
    protected function getCallback($relation, $counter, $param)
    {
        return function ($q) use ($relation, $counter, $param) {
            $q = $this->constrainsSelectAndSortAndWhere($q, $relation, $param);
        };
    }

    /**
     * @param Builder $q
     * @param string $model
     * @param array $param
     */
    protected function constrainsSelectAndSortAndWhere($q, $model, $param)
    {
        $selectArray = config('larafast.'.$model);
        foreach ($param as $key => $v) {
            if (is_integer($key)) {
                if ($this->isSort($key)) {
                    if ($v[0] == '-') {
                        $q = $this->addSort($q, trim($v, '-'), 'DESC');
                    } else {
                        $q = $this->addSort($q, $v, 'ASC');
                    }
                }
                if ($this->isSelect($key)) {
                    $selectArray[] = $v;
                }
                if ($this->isWhere($key)) {
                    $operator = [
                        'like'   => 'like',
                        'equals' => '=',
                        'min'    => '>=',
                        'max'    => '<=',
                    ];
                    $q = $this->addWhere($q, explode(':', $v)[0], explode(':', $v)[1], $operator[$key]);
                }
            } else {
                $q = $this->addEagerLoadRelation($q, $key, $v);
            }
        }
        $q = $this->addSelect($q, $selectArray);
        return $q;
    }

    protected function getConditionType($value)
    {
        return explode('=', $value);
    }

    protected function isSelect($key)
    {
        return is_integer($key);
    }

    protected function addSelect($query, $column)
    {
        return $query->select($column);
    }

    protected function isSort($key)
    {
        return $key == 'sort';
    }

    protected function addSort($query, $column, $operator = 'ASC')
    {
        return $query->orderBy($column, $operator);
    }

    protected function isWhere($key)
    {
        return is_integer($key) ? false : in_array($key, ['equals', 'like', 'min', 'max']);
    }

    protected function addWhere($query, $column1, $column2, $operator = '=')
    {
        if ($operator == 'like') {
            $column2 = '%' . $column2 . '%';
        }
        return $query->where($column1, $operator, $column2);
    }

    protected function constrainsWhereHas($q, $model, $param, $negation = false)
    {
        $function = $negation ? 'whereDoesntHave' : 'whereHas';
        $q = $q->$function($model, function ($query) use ($param) {
                foreach ($param as $key => $value) {
                    if (is_integer($key)) {
                        list($type, $condition)  = $this->getConditionType($value);
                        list($needle, $haystack) = explode(':', $condition);
                        $this->checkTypeAndApplyCondition($query, $type, $needle, $haystack);
                    } else {
                        $this->constrainsWhereHas($query, $key, $value);
                    }
                }
                $query;
            });
        return $q;
    }

    /**
     * @param Builder $query
     * @param string $type
     * @param string $needle
     * @param string $haystack
     * @return Builder
     */
    protected function checkTypeAndApplyCondition($query, $type, $needle, $haystack)
    {
        switch ($type) {
            case 'equals':
                $query = $this->addWhere($query, $needle, $haystack, '=');
                break;
            case 'like':
                $query = $this->addWhere($query, $needle, $haystack, 'like');
                break;
            case 'min':
                $query = $this->addWhere($query, $needle, $haystack, '>=');
                break;
            case 'max':
                $query = $this->addWhere($query, $needle, $haystack, '<=');
                break;

            default:
                # code...
                break;
        }
        return $query;
    }
}
