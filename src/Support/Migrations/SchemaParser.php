<?php

namespace Rawilk\LaravelModules\Support\Migrations;

use Illuminate\Contracts\Support\Arrayable;

class SchemaParser implements Arrayable
{
    /**
     * The custom attributes
     *
     * @var array
     */
    protected $customAttributes = [
        'remember_token' => 'rememberToken()',
        'soft_delete'    => 'softDeletes()',
    ];

    /**
     * The migration schema
     *
     * @var string
     */
    protected $schema;

    /**
     * The relationship keys
     *
     * @var array
     */
    protected $relationshipKeys = [
        'belongsTo',
    ];

    /**
     * Create new class instance.
     *
     * @param string|null $schema
     */
    public function __construct($schema = null)
    {
        $this->schema = $schema;
    }

    /**
     * Convert the given string to an array of formatted data.
     *
     * @param string $schema
     * @return array
     */
    public function parse($schema)
    {
        $this->schema = $schema;

        $parsed = [];

        foreach ($this->getSchemas() as $schemaData) {
            $column = $this->getColumn($schemaData);

            $attributes = $this->getAttributes($column, $schemaData);

            $parsed[$column] = $attributes;
        }

        return $parsed;
    }

    /**
     * Get the schemas.
     *
     * @return array
     */
    public function getSchemas()
    {
        if (is_null($this->schema)) {
            return [];
        }

        return explode(',', str_replace(' ', '', $this->schema));
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->parse($this->schema);
    }

    /**
     * Render the migration to a formatted script.
     *
     * @return string
     */
    public function render()
    {
        $results = '';

        foreach ($this->toArray() as $column => $attributes) {
            $results .= $this->createField($column, $attributes);
        }

        return $results;
    }

    /**
     * Render up migration fields.
     *
     * @return string
     */
    public function up()
    {
        return $this->render();
    }

    /**
     * Render down migration fields.
     *
     * @return string
     */
    public function down()
    {
        $results = '';

        foreach ($this->toArray() as $column => $attributes) {
            $attributes = [head($attributes)];

            $results .= $this->createField($column, $attributes, 'remove');
        }

        return $results;
    }

    /**
     * Create the given field.
     *
     * @param string $column
     * @param array $attributes
     * @param string $type
     * @return string
     */
    public function createField($column, $attributes, $type = 'add')
    {
        $results = "\t\t\t" . '$table';

        foreach ($attributes as $key => $field) {
            if (in_array($column, $this->relationshipKeys)) {
                $results .= $this->addRelationColumn($key, $field, $column);
            } else {
                $results .= $this->{"{$type}Column"}($key, $field, $column);
            }
        }

        return $results . ';' . PHP_EOL;
    }

    /**
     * Generate the given relation column.
     *
     * @param int $key
     * @param string $field
     * @param string $column
     * @return string
     */
    protected function addRelationColumn($key, $field, $column)
    {
        $relatedColumn = snake_case(class_basename($field)) . '_id';

        $method = 'integer';

        return "->{$method}('{$relatedColumn}')";
    }

    /**
     * Format the given field to a migration command.
     *
     * @param int $key
     * @param string $field
     * @param string $column
     * @return string
     */
    protected function addColumn($key, $field, $column)
    {
        if ($this->hasCustomAttribute($column)) {
            return "->{$field}";
        }

        if ($key == 0) {
            return "->{$field}('{$column}')";
        }

        if (str_contains($field, '(')) {
            return "->{$field}";
        }

        return "->{$field}()";
    }

    /**
     * Format the given field to a drop migration command.
     *
     * @param int $key
     * @param string $field
     * @param string $column
     * @return string
     */
    protected function removeColumn($key, $field, $column)
    {
        if ($this->hasCustomAttribute($column)) {
            return "->{$field}";
        }

        return "->dropColumn('{$column}')";
    }

    /**
     * Get the column name from the given schema.
     *
     * @param string $schema
     * @return string
     */
    public function getColumn($schema)
    {
        return array_get(explode(':', $schema), 0);
    }

    /**
     * Get the given column's attributes.
     *
     * @param string $column
     * @param string $schema
     * @return array
     */
    public function getAttributes($column, $schema)
    {
        $fields = str_replace($column . ':', '', $schema);

        return $this->hasCustomAttribute($column)
            ? $this->getCustomAttribute($column)
            : explode(':', $fields);
    }

    /**
     * Determine if the given column is a custom attribute.
     *
     * @param string $column
     * @return bool
     */
    public function hasCustomAttribute($column)
    {
        return array_key_exists($column, $this->customAttributes);
    }

    /**
     * Get the given custom attribute's value.
     *
     * @param string $column
     * @return array
     */
    public function getCustomAttribute($column)
    {
        return (array) $this->customAttributes[$column];
    }
}
