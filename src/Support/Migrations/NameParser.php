<?php

namespace Rawilk\LaravelModules\Support\Migrations;

class NameParser
{
	/**
	 * The migration name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The migration data.
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * The available schema actions.
	 *
	 * @var array
	 */
	protected $actions = [
		'create' => ['create', 'make'],
		'delete' => ['delete', 'remove'],
		'add'    => ['add', 'update', 'append', 'insert'],
		'drop'   => ['destroy', 'drop']
	];

	/**
	 * Create a new class instance.
	 *
	 * @param string $name
	 */
	public function __construct($name)
	{
	    $this->name = $name;
	    $this->data = $this->fetchData();
	}

	/**
	 * Get original migration name.
	 *
	 * @return string
	 */
	public function getOriginalName()
	{
		return $this->name;
	}

	/**
	 * Get the schema type or action.
	 *
	 * @return string
	 */
	public function getAction()
	{
		return head($this->data);
	}

	/**
	 * Get the table to use for the migration.
	 *
	 * @return string
	 */
	public function getTableName()
	{
		$matches = array_reverse($this->getMatches());

		return array_shift($matches);
	}

	/**
	 * Get matches from regex.
	 *
	 * @return array
	 */
	public function getMatches()
	{
		preg_match($this->getPattern(), $this->name, $matches);

		return $matches;
	}

	/**
	 * Get the name regex pattern.
	 *
	 * @return string
	 */
	public function getPattern()
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

	/**
	 * Convert the migration name to an array of data.
	 *
	 * @return array
	 */
	protected function fetchData()
	{
		return explode('_', $this->name);
	}

	/**
	 * Get the migration data.
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Determine if the given type is the same as the current schema action or type.
	 *
	 * @param $type
	 * @return bool
	 */
	public function is($type)
	{
		return $type === $this->getAction();
	}

	/**
	 * Determine if the current schema action is an adding action.
	 *
	 * @return bool
	 */
	public function isAdd()
	{
		return in_array($this->getAction(), $this->actions['add']);
	}

	/**
	 * Determine if the current schema action is a deleting action.
	 *
	 * @return bool
	 */
	public function isDelete()
	{
		return in_array($this->getAction(), $this->actions['delete']);
	}

	/**
	 * Determine if the current schema action is a creating action.
	 *
	 * @return bool
	 */
	public function isCreate()
	{
		return in_array($this->getAction(), $this->actions['create']);
	}

	/**
	 * Determine if the current schema action is a dropping action.
	 *
	 * @return bool
	 */
	public function isDrop()
	{
		return in_array($this->getAction(), $this->actions['drop']);
	}
}
