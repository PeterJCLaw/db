<pre>
<?php

abstract class BaseIterator implements Iterator, ArrayAccess
{
	private $position = 0;
	private $cache = array();

	public function __construct()
	{
	}

	protected abstract function getNextValue();

	private function exists()
	{
		//var_dump(__METHOD__);
		return isset($this->cache[$this->position]);
	}

	private function moveForward()
	{
//		var_dump(__METHOD__);
//		var_dump($this->cache, $this->position);
		while (count($this->cache) <= $this->position)
		{
			$next = $this->getNextValue();
			if ($next === null)
			{
				return;
			}
			$this->cache[] =  $next;
		}
	}

	private function ensureUpToDate()
	{
//		var_dump(__METHOD__);
		if (!$this->exists())
		{
			$this->moveForward();
		}
	}

	public function rewind()
	{
		var_dump(__METHOD__);
		$this->position = 0;
	}

	public function current()
	{
//		var_dump(__METHOD__);
		$this->ensureUpToDate();
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
		$this->ensureUpToDate();
		$exists = $this->exists();
		return $exists;
	}

	public function offsetSet($offset, $value)
	{
		// TODO: handle setting?
		trigger_error('Cannot modify.', E_USER_WARNING);
	}

	public function offsetExists($offset)
	{
		return isset($this->cache[$offset]);
	}

	public function offsetUnset($offset)
	{
		$this->offsetSet($offset, null);
	}

	public function offsetGet($offset)
	{
		return isset($this->cache[$offset]) ? $this->cache[$offset] : null;
	}
}

class MyIterator extends BaseIterator
{
	protected function getNextValue()
	{
//		var_dump(__METHOD__);
		static $count = 0;
		if (++$count % 5 == 0)
		{
			return null;
		}
		return $count;
	}
}

$i = new MyIterator();
var_dump($i);

for ($j = 0; $j < 3; $j++)
{
	foreach ($i as $v)
	{
		var_dump($v);
	}
}