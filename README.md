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
    'opcoes' => [
        'tpAmbiente' => 'T',
        'estacao' => '1LKZ',
        'marcacoes' => [
            'CONVENIO.COD-CONVENIO' => [
                'apelido' => 'Convenio'
            ],
            'PAGADOR.NUM-DOC' => [
                'apelido' => 'CPF'
            ],
            'PAGADOR.NOME' => [
                'apelido' => 'Nome',
                'formatador' => function($value, $marcacao) {
                    if (strlen($value) <= $marcacao['tamanho']) {
                        return $value;
                    }
                    return substr($value, 0, 40);
                }
            ],
            'PAGADOR.ENDER' => [
                'apelido' => 'Endereco'
            ],
            'PAGADOR.BAIRRO' => [
                'apelido' => 'Bairro'
            ],
            'PAGADOR.CIDADE' => [
                'apelido' => 'Cidade'
            ],
            'PAGADOR.UF' => [
                'apelido' => 'UF'
            ],
            'PAGADOR.CEP' => [
                'apelido' => 'CEP'
            ],
            'TITULO.NOSSO-NUMERO' => [
                'apelido' => 'NossoNumero'
            ],
            'TITULO.VL-NOMINAL' => [
                'apelido' => 'Valor'
            ],
        ],
    ],
];

$parametros = [
    'Convenio' => 'XXXXXX',
    'CPF' => 'XXXXXXXXXXX',
    'Nome' => 'XXXXXXX XXXXXXX XXXXXXX XXXXX XXXXXX XXXXXXXX',
    'Endereco' => 'XXX XXXXXX XXXXXXX XX XXXXX XXXXXXX',
    'Bairro' => 'XXXXXXXXX',
    'Cidade' => 'XXX',
    'UF' => 'XX',
    'CEP' => 'XXXXXXXX',
    'NossoNumero' => 1,
    'Valor' => 15,
];

Santander::criar('~/certificado.pem', 'senha do .PEM', $opcoes)->registrar($parametros);
```
