<?php

require_once "./vendor/autoload.php";

// Teste::metodo();
// Teste2::metodo();
// exit();

use GuzzleHttp\Client;
use Rodrigoavlis\BuscadorCursos\Buscador;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client(["base_uri" => "https://www.alura.com.br/"]);
$crawler = new Crawler();

$buscador = new Buscador($client, $crawler);
$cursos = $buscador->buscar("cursos-online-programacao/php");

$i = 0;
foreach ($cursos as $curso) {
  $i++;
  echo exibeMensagem("#$i: $curso");
}