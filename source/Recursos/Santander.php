<?php

namespace CobrancaPHP\Recursos;

use CobrancaPHP\ErroValidacaoMarcacao;
use CobrancaPHP\Recursos\Santander\ConfiguracaoMarcacao;
use CobrancaPHP\Recursos\Santander\ConfiguracaoXML;
use CobrancaPHP\Resposta;
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
     * @param $certificado
     * @param $senha
     * @param array $opcoes
     */
    public function __construct($certificado, $senha, array $opcoes = [])
    {
        parent::__construct($certificado, $senha, $opcoes);

        $this->configurarMarcacoes();
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
            'cache_ws' => WSDL_CACHE_NONE
        ];
        $this->parametros = $parametros;

        $ticket = $this->registrarTicket($configuracoes);

        $cobranca = $this->registrarCobranca($configuracoes, $ticket);

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
    protected function registrarCobranca(array $options, $ticket)
    {
        $soapClient = new SoapClient($this->cobrancaWsdl, $options);

        $xml = $this->comporCobranca($ticket->TicketResponse->ticket);

        /** @noinspection PhpUndefinedMethodInspection */
        return $soapClient->registraTitulo($xml);
    }
}
