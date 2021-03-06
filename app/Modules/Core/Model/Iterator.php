<?php declare(strict_types=1);

namespace App\Modules\Core\Model;

use Nette\Database\Table\IRow;


class Iterator implements \Iterator
{
	/** @var int */
	protected $index;

	/** @var IRow[] */
	protected $data;


	public function __construct(array $data)
	{
		$this->data = array_values($data);
	}


	public function current()
	{
		return $this->valid() ? $this->data[$this->index] : NULL;
	}


	public function next()
	{
		++$this->index;
		return $this->current();
	}


	public function key()
	{
		return $this->index;
	}


	public function valid()
	{
		return array_key_exists($this->index, $this->data);
	}


	public function rewind()
	{
		$this->index = 0;
	}
}