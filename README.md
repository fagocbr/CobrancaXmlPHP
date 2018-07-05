# CobrancaXml

## Certificado

Baixe o arquivo de certificado .PFX para o seu computador. Posicione o seu terminal na pasta em que está o documento e rode o comando abaixo. 

```bash
[~] $ openssl pkcs12 -in <certificado.pfx> -out certificado.pem
```
Informe a senha do .PFX e em seguida informa a senha que deseja usar para o .PEM


## Utilização 

```php
<?php
require 'vendor/autoload.php';

use CobrancaPHP\Recursos\Santander;;

$opcoes = [
    'tpAmbiente' => 'T',
    'estacao' => 'XXXX',
];

$parametros = [
    'CONVENIO.COD-CONVENIO' => 'XXXXXX',
    'PAGADOR.NUM-DOC' => 'XXXXXXXXXXX',
    'PAGADOR.NOME' => 'XXXXXXX XXXXXXX XXXXXXX XXXXX XXXXXX',
    'PAGADOR.ENDER' => 'XXX XXXXXX XXXXXXX XX XXXXX XXXXXXX',
    'PAGADOR.BAIRRO' => 'XXXXXXXXX',
    'PAGADOR.CIDADE' => 'XXX',
    'PAGADOR.UF' => 'XX',
    'PAGADOR.CEP' => 'XXXXXXXX',
    'TITULO.NOSSO-NUMERO' => 1,
    'TITULO.VL-NOMINAL' => 15,
];

Santander::criar('~/certificado.pem', 'senha do .PEM', $opcoes)->registrar($parametros);
```
