<?php

namespace App\Repository;

class WebQueryBuilder
{
    protected string $select = '*';
    protected array $orderBy;
    protected int $page;
    protected int $limit = 15;

    protected array $wheres = [];

    public function __construct(protected string $tableName)
    {
    }

    public function select(string $select)
    {
        $this->select = $select;
    }

    public function orderBy(string $orderBy, string $orderType = 'ASC')
    {
        $this->orderBy = ['field' => $orderBy, 'type' => $orderType];
    }

    public function page(int $page)
    {
        $this->page = $page;
    }

    public function limit(int $limit = 15)
    {
        $this->limit = $limit;
    }

    public function getSelect()
    {
        return $this->select;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function where(string $field, string $operator, string $value)
    {
        $this->wheres[] = [$field, $operator, $value];
    }

    public function getWheres()
    {
        return $this->wheres;
    }
}
