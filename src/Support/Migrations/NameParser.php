<?php

namespace Rawilk\LaravelModules\Support\Migrations;

class NameParser
{
    /** @var array */
    protected static $actions = [
        'add'    => ['add', 'update', 'append', 'insert'],
        'create' => ['create', 'make'],
        'delete' => ['delete', 'remove'],
        'drop'   => ['destroy', 'drop']
    ];

    /** @var array */
    protected $data = [];

    /** @var string */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->data = $this->fetchData();
    }

    public function getAction(): string
    {
        return head($this->data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getMatches(): array
    {
        preg_match($this->getPattern(), $this->name, $matches);

        return $matches;
    }

    public function getOriginalName(): string
    {
        return $this->name;
    }

    public function getPattern(): string
    {
        switch ($action = $this->getAction()) {
            case 'add':
            case 'append':
            case 'update':
            case 'insert':
                return "/{$action}_(.*)_to_(.*)_table/";
            case 'delete':
            case 'remove':
            case 'alter':
                return "/{$action}_(.*)_from_(.*)_table/";
            default:
                return "/{$action}_(.*)_table/";
        }
    }

    public function getTableName(): string
    {
        $matches = array_reverse($this->getMatches());

        return array_shift($matches);
    }

    public function is(string $type): bool
    {
        return $type === $this->getAction();
    }

    public function isAdd(): bool
    {
        return in_array($this->getAction(), static::$actions['add'], true);
    }

    public function isCreate(): bool
    {
        return in_array($this->getAction(), static::$actions['create'], true);
    }

    public function isDelete(): bool
    {
        return in_array($this->getAction(), static::$actions['delete'], true);
    }

    public function isDrop(): bool
    {
        return in_array($this->getAction(), static::$actions['drop'], true);
    }

    protected function fetchData(): array
    {
        return explode('_', $this->name);
    }
}
