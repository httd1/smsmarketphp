<?php

/**
*
** J.M <t.me/httd1>
*
** @link https://smsmarket.docs.apiary.io/reference/servicos-da-api/
*
** Classe simples para o envio de SMS com a API do smsmarket.com.br
*
**/

class SMSMarket
{
	
	const URL='https://api.smsmarket.com.br/webservice-rest/'; // url de requisição
	
	protected $hash; //hash de autenticação
	protected $timezone;
	
function __construct ($usuario, $senha, $timezone=null){
	
	if ($timezone == null)
	{
		$this->setTimezone (date ('P'));
	}

	$this->hash=base64_encode ($usuario.':'.$senha);
	
	}

/**
*
** Envia um SMS individual para um número.
** Referências https://smsmarket.docs.apiary.io/reference/servicos-da-api/envio-individual
*
** @param $numero - Número para envio do  SMS.
** @param $mensagem - Mensagem a ser enviada.
** @param $type - Tipo de mensagem que será enviada Referencia https://smsmarket.docs.apiary.io/introduction/parametro-type
** @param $campaing_id - Um identificador para esse envio.
** @param $country_code - Codigo DDI do país.
** @param $schedule - Horário programado para a mensagem ser enviada no formato ISO 8691.
*
** @return Um objeto json com informações do envio.
*
*/

public function sendSMS ($numero, $mensagem, $type=0, $campaing_id=null, $country_code=null, $schedule=null){
	
	$query ['number']=$numero;
	$query ['content']=$mensagem;
	$query ['type']=$type;
	
	if ($campaing_id != null){
		$query ['campaing_id']=$campaing_id;
		}
		
	if ($country_code != null){
	$query ['country_code']=$country_code;
		}
		
	if ($schedule != null){
		$query ['schedule']=$schedule;
		}
		
	$query ['timezone']=$this->timezone;
		
		$queryRequest=http_build_query ($query);

		$request=$this->request ('send-single', $queryRequest);
		
			return $request;
	
	}

/**
*
** Envia um lote de SMS.
** Referências https://smsmarket.docs.apiary.io/reference/servicos-da-api/envio-em-lote
*
** @param $mensagem - Um array contendo os destinatarios do SMS.
** @param $type - Tipo de mensagem que será enviada.
** @param $schedule - Horário programado para a mensagem ser enviada no formato ISO 8691.
*
** @return Um objeto json com informações do envio.
*
*/

public function sendSMSMultiple (array $messages, $type=0, $schedule=null){

	$query ['defaultValues']['type']=$type;

	if ($schedule != null){
		$query ['defaultValues']['schedule']=$schedule;
	}

	$query ['defaultValues']['timezone']=$this->timezone;
	
	$query ['messages']=$messages;
		
		$queryRequest=json_encode ($query);

		$request=$this->request ('send-multiple', $queryRequest, 'POST');
		
			return $request;
	
	}

/**
*
** Saldo disponível na sua conta para envio de SMS.
** Referências https://smsmarket.docs.apiary.io/reference/servicos-da-api/consulta-de-saldo
*
** @return Um objeto json com informações do saldo.
*
*/

public function getBalance (){
	
	$request=$this->request ('balance');
	
		return $request;
	
	}

/**
*
** Consulta status de um SMS enviado.
** Referências https://smsmarket.docs.apiary.io/reference/servicos-da-api/consulta-de-status-por-identificador
*
** @param $id - ID do SMS retornado pela api após o envio, também pode ser um array com os IDs.
*
** @return um objeto json com o status do(s) envio(s).
*
*/

public function getStatusID ($id){
	
	if (is_array ($id)){
		$id=implode (',', $id);
		}
		
	$query ['id']=$id;
	
	$queryRequest=http_build_query ($query);
	
	$request=$this->request ('mt_id', $queryRequest);
	
		return $request;
	
	}

/**
*
** Consulta status de um SMS enviado por campaing_id.
** Referências https://smsmarket.docs.apiary.io/reference/servicos-da-api/consulta-de-status-por-identificador
*
** @param $id - campaing_id do envio.
*
** @return um objeto json com o status do(s) envio(s).
*
*/

public function getStatusCampaingID ($id){
	
	if (is_array ($id)){
		$id=implode (',', $id);
		}
	
	$query ['campaing_id']=$id;
	$query ['timezone']=$this->timezone;
	
	$queryRequest=http_build_query ($query);
	
	$request=$this->request ('mt_id', $queryRequest);
	
		return $request;
	
	}

/**
*
** Busca mensagens enviadas em um periodo de tempo.
*
** Referências https://smsmarket.docs.apiary.io/#reference/servicos-da-api/consulta-de-status-por-periodo
*
** @param $startDate - Data inicial da busca no formato ISO-8601.
** @param $endDate - Data final da busca no formato ISO-8601.
** @param $type - Filtra por tipo.
** @param $status - Filtra por status 1 (enviado),0 (não enviado).
*
** @return um objeto json com o status do(s) envio(s).
*
*/
public function getStatusDate ($startDate, $endDate, $type=0, $status=1){

	$query ['type']=$type;
	$query ['status']=$status;
	$query ['start_date']=$startDate;
	$query ['end_date']=$endDate;
	$query ['timezone']=$this->timezone;
	
	$queryRequest=http_build_query ($query);
	
	$request=$this->request ('mt_date', $queryRequest);
	
		return $request;
	
	}

/**
*
** Busca por novas mensagens recebidas.
*
** Referências https://smsmarket.docs.apiary.io/reference/servicos-da-api/consulta-por-novas-mensagens-recebidas
*
** @param $type - Tipo de mensagem.
*
** @return um objeto json com novas mensagens
*
*/
public function getNewMessages ($type=0){

	$query ['type']=$type;
	$query ['timezone']=$this->timezone;
	
	$queryRequest=http_build_query ($query);
	
	$request=$this->request ('mo_new', $queryRequest);
	
		return $request;
	
	}

/**
*
** Busca novas mensagens recebidas em um periodo de tempo
*
** Referências https://smsmarket.docs.apiary.io/reference/servicos-da-api/consulta-de-mensagens-recebidas-por-periodo
*
** @param $startDate - Data inicial da busca no formato ISO-8601.
** @param $endDate - Data final da busca no formato ISO-8601.
** @param $type - Filtra por tipo.
** @param $campaing_id - Identificador da mensagems.
*
** @return um objeto json com o status do(s) envio(s).
*
*/
public function getNewMessagesDate ($startDate, $endDate, $type=0, $campaing_id=null){

	if ($campaing_id != null)
	{
		$query ['campaing_id']=$campaing_id;
	}

	$query ['type']=$type;
	$query ['start_date']=$startDate;
	$query ['end_date']=$endDate;
	$query ['timezone']=$this->timezone;
	
	$queryRequest=http_build_query ($query);
	
	$request=$this->request ('mo', $queryRequest);
	
		return $request;
	
	}

public function setTimezone ($timezone){

	$this->timezone=$timezone;

}
	
private function request ($path, $query=null, $requestType='GET'){

	if ($requestType == 'GET')
	{

		$url=self::URL.$path.'?'.$query;

		$header=[
			'Authorization: Basic '.$this->hash,
			'Content-Type: apllication/x-www-form-urlencoded'
		];

	} else {

		$url=self::URL.$path;

		$header[]='Authorization: Basic '.$this->hash;
	}

	$connect=curl_init ();
	
	curl_setopt ($connect, CURLOPT_URL, $url);
	curl_setopt ($connect, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($connect, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt ($connect, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt ($connect, CURLOPT_HTTPHEADER, $header);

	if ($requestType == 'POST')
	{

		curl_setopt ($connect, CURLOPT_POST, true);
		curl_setopt ($connect, CURLOPT_POSTFIELDS, $query);

	}

	$request=curl_exec ($connect);

		return json_decode ($request, true);

	}
	
	}