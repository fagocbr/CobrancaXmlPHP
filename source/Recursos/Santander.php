<?php

namespace CobrancaPHP\Recursos;

use CobrancaPHP\Erros\ErroValidacaoMarcacao;
use CobrancaPHP\Recursos\Santander\ConfiguracaoMarcacao;
use CobrancaPHP\Recursos\Santander\ConfiguracaoXML;
use CobrancaPHP\Recursos\Santander\Resposta;
use CobrancaPHP\Transacao;
use SoapClient;
use stdClass;

/**
 * Class Santander
 * @package CobrancaPHP\Recursos
 */
class Santander extends Transacao
{
    /**
     * @see ConfiguracaoXML
     */
    use ConfiguracaoXML;

    /**
     * @see ConfiguracaoMarcacao
     */
    use ConfiguracaoMarcacao;

    /**
     * @var string
     */
    protected $ticketWsdl = "https://"
    . "ymbdlb.santander.com.br/"
    . "dl-ticket-services/TicketEndpointService/TicketEndpointService.wsdl";

    /**
     * @var string
     */
    protected $cobrancaWsdl = "https://"
    . "ymbcash.santander.com.br/"
    . "ymbsrv/CobrancaEndpointService/CobrancaEndpointService.wsdl";

    /**
     * @var array
     */
    protected $parametros = [];

    /**
     * @var array
     */
    protected $marcacoes = [];

    /**
     * @var string
     */
    const CODIGO_BANCO = '0033';

    /**
     * Santander constructor.
     * @param string $certificado
     * @param string $senha
     * @param array $opcoes
     */
    public function __construct($certificado, $senha, array $opcoes = [])
    {
        parent::__construct($certificado, $senha, $opcoes);

        $marcacoes = [];
        if (isset($opcoes['marcacoes'])) {
            $marcacoes = $opcoes['marcacoes'];
        }
        $this->configurarMarcacoes($marcacoes);
    }

    /**
     * @param array $parametros
     * @return Resposta
     * @throws ErroValidacaoMarcacao
     */
    public function registrar(array $parametros)
    {
        $configuracoes = [
            'keep_alive' => false,
            'trace' => true,
            'local_cert' => $this->certificado,
            'passphrase' => $this->senha,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'stream_context' => stream_context_create([
                'http' => [
                    'user_agent' => 'PHPSoapClient'
                ]
            ])
        ];
        $this->parametros = $parametros;

        $ticket = $this->registrarTicket($configuracoes);

        $cobranca = $this->registrarEntrada($configuracoes, $ticket);

        return Resposta::construir($cobranca);
    }

    /**
     * @param array $configuracoes
     * @return stdClass
     * @throws ErroValidacaoMarcacao
     */
    protected function registrarTicket(array $configuracoes)
    {
        $soapClient = new SoapClient($this->ticketWsdl, $configuracoes);

        $xml = $this->comporTicket();

        /** @noinspection PhpUndefinedMethodInspection */
        return $soapClient->create($xml);
    }

    /**
     * @param array $options
     * @param stdClass $ticket
     * @return stdClass
     */
    protected function registrarEntrada(array $options, $ticket)
    {
        $soapClient = new SoapClient($this->cobrancaWsdl, $options);

        $xml = $this->comporEntrada($ticket->TicketResponse->ticket);

        /** @noinspection PhpUndefinedMethodInspection */
        return $soapClient->registraTitulo($xml);
    }

    /**
     * @param string $propriedade
     * @param mixed [$padrao] (null)
     * @return mixed
     */
    protected function parametro($propriedade, $padrao = null)
    {
        if (isset($this->parametros[$propriedade])) {
            return $this->parametros[$propriedade];
        }
        return $padrao;
    }

    /**
     * @param string $propriedade
     * @param mixed [$padrao] (null)
     * @return mixed
     */
    protected function opcao($propriedade, $padrao = null)
    {
        if (isset($this->opcoes[$propriedade])) {
            return $this->opcoes[$propriedade];
        }
        return $padrao;
    }
}
