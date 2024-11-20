<?php

namespace Rodrigoavlis\BuscadorCursos\tests;

use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Rodrigoavlis\BuscadorCursos\Buscador;
use Symfony\Component\DomCrawler\Crawler;

class TestBuscadorDeCursos extends TestCase
{
  private $httpClientMook;
  private $url = "url-teste";

  protected function setUp(): void{
    $html = <<<FIM
    <html>
      <body>
        <span class="card-curso__nome">Cursos Teste 1</span>
        <span class="card-curso__nome">Cursos Teste 2</span>
        <span class="card-curso__nome">Cursos Teste 3</span>
      </body>
    </html>
    FIM;

    $stream = $this->createMock(StreamInterface::class);
    $stream
      ->expects($this->once())
      ->method("__tostring")
      ->willReturn($html);

    $response = $this->createMock(ResponseInterface::class);
    $response
      ->expects($this->once())
      ->method("getBody")
      ->willReturn($stream);

    $httpClient = $this->createMock(ClientInterface::class);
    $httpClient
      ->expects($this->once())
      ->method("request")
      ->with("GET", $this->url)
      ->willReturn($response);

    $this->httpClientMook = $httpClient;
  }

  public function testBuscadorDeveRetornarCursos()
  {
    $crawler = new Crawler();
    $buscador = new Buscador($this->httpClientMook, $crawler);
    $cursos = $buscador->buscar($this->url);
    
    $this->assertCount(3, $cursos);
    $this->assertEquals("Cursos Teste 1", $cursos[0]);
    $this->assertEquals("Cursos Teste 2", $cursos[1]);
    $this->assertEquals("Cursos Teste 3", $cursos[2]);   
  }  
}