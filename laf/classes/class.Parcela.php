<?php

/**
* Parcela
*/
class Parcela
{
	private $numeroVezes;
	private $preco;
	private $juros;
	private $displayJuros;
	private $valorParcela;
	private $valorTotalParcelas;

	function __construct( $numeroVezes, $preco, $juros = null )
	{
		$juros = $juros ? str_replace( ',', '.', $juros) : $juros;
		$this->numeroVezes = $numeroVezes;
		$this->preco = (float)$preco;
		$this->juros = $juros ? (float)$juros / 100 : $juros;
		$this->displayJuros = $juros ? str_replace( '.', ',', $juros ) : $juros;
		$this->setValorParcela();
		$this->setValorTotalParcelas();
	}

	public function getNumeroVezes() {
		return $this->numeroVezes;
	}

	public function getPreco() {
		return $this->preco;
	}

	public function getJuros() {
		return $this->juros;
	}

	public function getDisplayJuros() {

		return $this->displayJuros;
	}

	private function setValorParcela() {
		if( $this->preco > 0 && $this->numeroVezes > 0 ) :
			$valorParcela = $this->preco / $this->numeroVezes;
			$valorParcela = $this->juros ? $valorParcela + ( $valorParcela * $this->juros ) : $valorParcela;
		else :
			$valorParcela = 0;
		endif;
		$this->valorParcela = $valorParcela;
	}

	public function getValorParcela() {
		return $this->valorParcela;
	}

	private function setValorTotalParcelas() {
		$valorTotalParcelas = $this->juros ? $this->preco + ( ( $this->preco) * ( $this->juros ) ) : $this->preco;
		$this->valorTotalParcelas = $valorTotalParcelas;
	}

	public function getValorTotalParcelas() {
		return $this->valorTotalParcelas;
	}
}