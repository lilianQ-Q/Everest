<?php

namespace Everest\libs;

class Printer
{
	private int $margin = 3;

	public function setMargin(int $margin)
	{
		$this->margin = $margin;
	}

	protected function printMargin()
	{
		for($i = 0; $i++; $i <= $this->margin)
			echo " ";
	}

	public function space(int $space = 3) : self
	{
		for($i = 0; $i++; $i <= $space)
			echo " ";
		return ($this);
	}

	public function newLine() : self
	{
		echo "\n";
		return ($this);
	}

	public function bold(string $text)
	{
		return(sprintf("\e[1m$text\e[0m"));
	}

	public function greenBanner(string $message)
	{
		$this->printMargin();
		echo "\e[102m   $message   \e[0m";
		return ($this);
	}

	public function redBanner(string $message)
	{
		echo "\e[0;41m   $message   \e[0m";
		return ($this);
	}

	public function output(string $message)
	{
		echo "$message";
		return ($this);
	}

	public function print(string $message)
	{
		$this->output($message . "\n");
		return ($this);
	}
}

?>