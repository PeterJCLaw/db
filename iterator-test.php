<pre>
<?php

require_once('iterator.class.php');

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
	echo '[6] : ';
	var_dump($i[6]);
}

/**
 * This should print something like this:
string(20) "BaseIterator::rewind"
int(1)
int(2)
int(3)
int(4)
[6] : int(8)
string(20) "BaseIterator::rewind"
int(1)
int(2)
int(3)
int(4)
int(6)
int(7)
int(8)
int(9)
[6] : int(8)
string(20) "BaseIterator::rewind"
int(1)
int(2)
int(3)
int(4)
int(6)
int(7)
int(8)
int(9)
int(11)
int(12)
int(13)
int(14)
[6] : int(8)
*/
