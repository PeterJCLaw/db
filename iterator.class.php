<?php

abstract class BaseIterator implements Iterator, ArrayAccess
{
	private $position = 0;
	private $cache = array();

	public function __construct()
	{
	}

	protected abstract function getNextValue();

	private function exists($position)
	{
		return isset($this->cache[$position]);
	}

	private function updateCache($position)
	{
		while (count($this->cache) <= $position)
		{
			$next = $this->getNextValue();
			if ($next === null)
			{
				return;
			}
			$this->cache[] =  $next;
		}
	}

	private function ensureCacheTo($position)
	{
		if (!$this->exists($position))
		{
			$this->updateCache($position);
		}
	}

	public function rewind()
	{
		$this->position = 0;
	}

	public function current()
	{
		$this->ensureCacheTo($this->position);
		return $this->cache[$this->position];
	}

	public function key()
	{
		return $this->position;
	}

	public function next()
	{
		++$this->position;
	}

	public function valid()
	{
		$this->ensureCacheTo($this->position);
		$exists = $this->exists($this->position);
		return $exists;
	}

	private function validateOffset($offset)
	{
		return is_int($offset);
	}

	public function offsetSet($offset, $value)
	{
		// TODO: handle setting?
		trigger_error('Cannot modify.', E_USER_WARNING);
	}

	public function offsetExists($offset)
	{
		if (!$this->validateOffset($offset))
		{
			return false;
		}
		$this->ensureCacheTo($offset);
		return isset($this->cache[$offset]);
	}

	public function offsetUnset($offset)
	{
		$this->offsetSet($offset, null);
	}

	public function offsetGet($offset)
	{
		if ($this->offsetExists($offset))
		{
			return $this->cache[$offset];
		}
		return null;
	}
}
