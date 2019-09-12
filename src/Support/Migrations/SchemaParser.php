<?php

namespace Rawilk\LaravelModules\Support\Migrations;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SchemaParser implements Arrayable
{
    /** @var array */
    protected static $customAttributes = [
        'remember_token' => 'rememberToken()',
        'soft_delete'    => 'softDeletes()'
    ];

    /** @var array */
    protected static $relationshipKeys = ['belongsTo'];

    /** @var string|null */
    protected $schema;

    /**
     * @param string|null $schema
     */
    public function __construct(?string $schema = null)
    {
        $this->schema = $schema;
    }

    public function createField(string $column, array $attributes, string $type = 'add'): string
    {
        $results = "\t\t\t" . '$table';

        foreach ($attributes as $key => $field) {
            if (in_array($column, static::$relationshipKeys, true)) {
                $results .= $this->addRelationColumn($key, $field, $column);
            } else {
                $results .= $this->{"{$type}Column"}($key, $field, $column);
            }
        }

        return $results . ';' . PHP_EOL;
    }

    /**
     * Render the down function of the migration.
     *
     * @return string
     */
    public function down(): string
    {
        $results = '';

        foreach ($this->toArray() as $column => $attributes) {
            $attributes = [head($attributes)];

            $results .= $this->createField($column, $attributes, 'remove');
        }

        return $results;
    }

    public function getAttributes(string $column, string $schema): array
    {
        $fields = str_replace("{$column}:", '', $schema);

        return $this->hasCustomAttribute($column)
            ? $this->getCustomAttribute($column)
            : explode(':', $fields);
    }

    public function getColumn(string $schema): string
    {
        return Arr::get(explode(':', $schema), 0);
    }

    public function getCustomAttribute(string $column): array
    {
        return (array) static::$customAttributes[$column];
    }

    public function getSchemas(): array
    {
        if ($this->schema === null) {
            return [];
        }

        return explode(',', str_replace(' ', '', $this->schema));
    }

    public function hasCustomAttribute(string $column): bool
    {
        return array_key_exists($column, static::$customAttributes);
    }

    public function parse(?string $schema): array
    {
        $this->schema = $schema;

        $parsed = [];

        foreach ($this->getSchemas() as $schemas) {
            $column = $this->getColumn($schemas);

            $attributes = $this->getAttributes($column, $schemas);

            $parsed[$column] = $attributes;
        }

        return $parsed;
    }

    public function render(): string
    {
        $results = '';

        foreach ($this->toArray() as $column => $attributes) {
            $results .= $this->createField($column, $attributes);
        }

        return $results;
    }

    public function toArray(): array
    {
        return $this->parse($this->schema);
    }

    /**
     * Render the up function of the migration.
     *
     * @return string
     */
    public function up(): string
    {
        return $this->render();
    }

    protected function addColumn(int $key, string $field, string $column): string
    {
        if ($this->hasCustomAttribute($column)) {
            return "->{$field}";
        }

        if ($key === 0) {
            return "->{$field}('{$column}')";
        }

        if (Str::contains($field, '(')) {
            return "->{$field}";
        }

        return "->{$field}()";
    }

    protected function addRelationColumn(int $key, string $field, string $column): string
    {
        if ($key === 0) {
            $relatedColumn = Str::snake(class_basename($field)) . '_id';

            return "->unsignedBigInteger('{$relatedColumn}');"
                . PHP_EOL . "\t\t\t\$table->foreign('{$relatedColumn}')";
        }

        if ($key === 1) {
            return "->references('{$field}')";
        }

        if ($key === 2) {
            return "->on('{$field}')";
        }

        if (Str::contains($field, '(')) {
            return "->{$field}";
        }

        return "->{$field}()";
    }

    protected function removeColumn(int $key, string $field, string $column): string
    {
        if ($this->hasCustomAttribute($column)) {
            return "->{$field}";
        }

        return "->dropColumn('{$column}')";
    }
}
