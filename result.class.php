<?php

/**
 * A wrapper object for a sql query result.
 * Capable of generating php-typed values, rather than just strings.
 */
// TODO: implement ArrayAccess
// TODO: implement Iterator
class Result
{
	private $res;

	/**
	 * Associate array mapping field names to their types.
	 */
	private $fieldTypes;

	/**
	 * Cache of the rows in this query result.
	 */
	private $rows;

	/**
	 * @param result The result handle for the results this object represents.
	 */
	public function __construct($result)
	{
		$this->res = $result;
	}

	public function getRow()
	{
		$this->fetchTypes();
		$row = $this->res->fetch_assoc();
		foreach ($this->fieldTypes as $name => $type)
		{
			settype($row[$name], $type);
		}
		return $row;
	}

	/**
	 * Gets the types of the fields, ready for use.
	 */
	private function fetchTypes()
	{
		if (!empty($this->fieldTypes))
		{
			return;
		}
		foreach ($this->res->fetch_fields() as $key => $fieldInfo)
		{
			$this->fieldTypes[$fieldInfo->name] = self::typeNameFromInfo($fieldInfo);
		}
		var_dump($this->fieldTypes);
	}

	/**
	 * Convert the type value that mysqli gives into a php compatible name.
	 * Defaults to 'string' if the type is now known.
	 * @param fieldInfo A field info object from mysqli.
	 * @returns A string suitable for php's settype.
	 */
	private static function typeNameFromInfo($fieldInfo)
	{
		$type = $fieldInfo->type;
		$length = $fieldInfo->length;
		if ($type == MYSQLI_TYPE_TINY && $length == 1)
		{
			return 'boolean';
		}

		static $floats = array(MYSQLI_TYPE_FLOAT
							,MYSQLI_TYPE_DOUBLE
							,MYSQLI_TYPE_DECIMAL
							,MYSQLI_TYPE_NEWDECIMAL
			);
		if (in_array($type, $floats))
		{
			return 'float';
		}

		static $integers = array(MYSQLI_TYPE_TINY
								,MYSQLI_TYPE_SHORT
								,MYSQLI_TYPE_LONG
			);
		if (in_array($type, $integers))
		{
			return 'integer';
		}

		return 'string';
	}
}