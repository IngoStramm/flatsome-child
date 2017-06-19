<?php
class Gateway
{
	private $id;
	private $nome;
	function __construct( $id, $nome )
	{
		$this->id;
		$this->nome;
	}

	public function getId() {
		return $this->id;
	}

	public function getNome() {
		return $this->nome;
	}
}