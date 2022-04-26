<?php

class Printer
{
	public function bold(string $text)
	{
		return(sprintf("\e[1m$text\e[0m"));
	}
}

?>