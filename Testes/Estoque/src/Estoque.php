<?php

namespace App;

class Estoque
{
    private $produtos = [];

    public function adicionarProduto($produto)
    {
        $nomeProduto = $produto['nome'];
        $quantidade = $produto['quantidade'];
        $preco = $produto['preco'];

        if (isset($this->produtos[$nomeProduto])) {
            throw new \Exception('Produto já existente no estoque.');
        }

        if ($quantidade <= 0) {
            throw new \Exception('Quantidade inválida para o produto.');
        }

        if ($preco <= 0) {
            throw new \Exception('Preço inválido para o produto.');
        }

        $produto['valor_total'] = $quantidade * $preco;

        $this->produtos[$nomeProduto] = $produto;
    }

    public function listarProdutos()
    {
        return $this->produtos;
    }

    public function consultarProduto($nomeProduto)
    {
        $this->verificaSeExiste($nomeProduto);
        
        return $this->produtos[$nomeProduto];
    }

    public function removerProduto($nomeProduto)
    {
        $this->verificaSeExiste($nomeProduto);

        unset($this->produtos[$nomeProduto]);
    }

    public function atualizarQuantidade($nomeProduto, $quantidade)
    {
        $this->verificaSeExiste($nomeProduto);

        $novaQuantidade = $this->produtos[$nomeProduto]['quantidade'] + $quantidade;

        if ($novaQuantidade < 0) {
            throw new \Exception('Quantidade inválida para atualização.');
        }

        $this->produtos[$nomeProduto]['quantidade'] = $novaQuantidade;
        $this->produtos[$nomeProduto]['valor_total'] = $novaQuantidade * $this->produtos[$nomeProduto]['preco'];

        if ($novaQuantidade == 0) {
            $this->removerProduto($nomeProduto);
        }
    }

    private function verificaSeExiste($nomeProduto){
        if (!isset($this->produtos[$nomeProduto])) {
            throw new \Exception('Produto não encontrado no estoque.');
        }
    }
}