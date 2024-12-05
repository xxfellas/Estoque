<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Estoque;


class EstoqueTest extends TestCase
{
  private $estoque;

  protected function setUp(): void
  {
    $this->estoque = new Estoque();
  }

  public function testAdicionarProduto()
  {
    $produto = [
      'nome' => 'Teclado',
      'quantidade' => 10,
      'preco' => 100.00
    ];

    $this->estoque->adicionarProduto($produto);
    $this->assertEquals(1, count($this->estoque->listarProdutos()));
  }

  public function testAdicionarProdutoDuplicado()
  {
    $produto = [
      'nome' => 'Notebook',
      'quantidade' => 5,
      'preco' => 2000.00
    ];

    $this->estoque->adicionarProduto($produto);

    $produtoDuplicado = [
      'nome' => 'Notebook',
      'quantidade' => 10,
      'preco' => 2200.00
    ];

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Produto já existente no estoque.');

    $this->estoque->adicionarProduto($produtoDuplicado);
  }

  public function testAtualizarQuantidade()
  {
    $produto = [
      'nome' => 'Mouse',
      'quantidade' => 5,
      'preco' => 50.00
    ];

    $this->estoque->adicionarProduto($produto);
    $this->estoque->atualizarQuantidade('Mouse', 10);
    $produtos = $this->estoque->listarProdutos();
    $this->assertEquals(15, $produtos['Mouse']['quantidade']);
  }

  public function testAtualizarQuantidadeProdutoInexistente()
  {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Produto não encontrado no estoque.');

    $this->estoque->atualizarQuantidade('Celular', 10);
  }

  public function testAtualizarQuantidadeComValorNegativo()
  {
    $produto = [
      'nome' => 'Fone de Ouvido',
      'quantidade' => 3,
      'preco' => 150.00
    ];

    $this->estoque->adicionarProduto($produto);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Quantidade inválida para atualização.');

    $this->estoque->atualizarQuantidade('Fone de Ouvido', -5);
  }

  public function testRemoverProduto()
  {
    $produto = [
      'nome' => 'Impressora',
      'quantidade' => 1,
      'preco' => 500.00
    ];

    $this->estoque->adicionarProduto($produto);
    $this->estoque->removerProduto('Impressora');

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Produto não encontrado no estoque.');

    $this->estoque->consultarProduto('Impressora');
  }

  public function testRemoverProdutoInexistente()
  {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Produto não encontrado no estoque.');

    $this->estoque->removerProduto('Tablet');
  }

  public function testRemoverTodaQuantidadeDoProduto()
  {
    $produto = [
      'nome' => 'Smartphone',
      'quantidade' => 10,
      'preco' => 1200.00
    ];

    $this->estoque->adicionarProduto($produto);

    $this->estoque->atualizarQuantidade('Smartphone', -10);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Produto não encontrado no estoque.');

    $this->estoque->consultarProduto('Smartphone');
  }

  public function testConsultarProduto()
  {
    $produto = [
      'nome' => 'Monitor',
      'quantidade' => 2,
      'preco' => 800.00
    ];

    $this->estoque->adicionarProduto($produto);
    $resultado = $this->estoque->consultarProduto('Monitor');
    $this->assertEquals(800.00, $resultado['preco']);
  }

  public function testConsultarProdutoInexistente()
  {
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Produto não encontrado no estoque.');

    $this->estoque->consultarProduto('Teclado Mecânico');
  }

  public function testListarProdutosEstoqueVazio()
  {
    $produtos = $this->estoque->listarProdutos();
    $this->assertEmpty($produtos);
  }

  public function testCalculoValorTotalDoProduto()
  {
    $produto = [
      'nome' => 'Cadeira Gamer',
      'quantidade' => 4,
      'preco' => 500.00
    ];

    $this->estoque->adicionarProduto($produto);

    $resultado = $this->estoque->consultarProduto('Cadeira Gamer');
    $this->assertEquals(2000.00, $resultado['valor_total']);
  }

  public function testCalculoValorTotalDoProdutoDepoisDaAtualizacao()
  {
    $produto = [
      'nome' => 'Cadeira Gamer',
      'quantidade' => 4,
      'preco' => 500.00
    ];

    $this->estoque->adicionarProduto($produto);

    $this->estoque->atualizarQuantidade('Cadeira Gamer', -2);

    $resultado = $this->estoque->consultarProduto('Cadeira Gamer');
    $this->assertEquals(1000.00, $resultado['valor_total']);
  }

  public function testAdicionarProdutoComPrecoInvalido()
  {
    $produto = [
      'nome' => 'Caixa de Som',
      'quantidade' => 2,
      'preco' => -50.00
    ];

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Preço inválido para o produto.');

    $this->estoque->adicionarProduto($produto);
  }

  public function testAdicionarProdutoComQuantidadeInvalida()
  {
    $produto = [
      'nome' => 'Tablet',
      'quantidade' => 0,
      'preco' => 900.00
    ];

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Quantidade inválida para o produto.');

    $this->estoque->adicionarProduto($produto);
  }
}