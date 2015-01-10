<?php namespace Klucherev\Smsaero;
 
class Smsaero {

	private $gate;
    private $username;
    private $password;
    private $from;
    private $typeanswer;
    private $useragent;
    
    function __construct($username,
                         $password,
                         $from,
                         $typeanswer = 'json',
                         $useragent = 'Mozilla/5.0 (Windows NT 6.1; rv:15.0) Gecko/20100101 Firefox/15.0.1')
    {
        $this->username     = $username;
        $this->password     = md5($password);
        $this->typeanswer   = "&answer={$typeanswer}";
        $this->from         = $from;
        $this->useragent    = $useragent;
        $this->gate         = 'http://gate.smsaero.ru';
    }
 
    private function send_post($url, $data)
    {
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $this->gate . $url . '?' . str_replace('+', '%20', http_build_query($data)) . $this->typeanswer);
    	curl_setopt($ch, CURLOPT_HEADER, false);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    	curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);
    	$response = curl_exec($ch);
    	curl_close($ch);
    	return $response;
    }

    /**
     * Получение баланса
     */
    public function getBalance()
    {
    	$smsaero_response = json_decode($this->send_post("/balance/", array('user' => $this->username, 'password' => $this->password)));
        return $smsaero_response->balance;
    }

    /**
     * Передача сообщения
     * @param $to Номер телефона получателя, в формате 71234567890
     * @param $text Текст сообщения, в UTF-8 кодировке
     * @param $from Подпись отправителя (например TEST)
     * @param $date Дата для отложенной отправки сообщения (количество секунд с 1 января 1970 года)
     */
    public function send( $to, $text, $from=null, $date=null )
    {
    	if(is_null($from))
    		$from=$this->from;

    	$response = $this->send_post(
    		"/send/",
    		array(
    			'user'     => $this->username,
    			'password' => $this->password,
    			'to'       => $to,
    			'text'     => $text,
    			'from'     => $from,
    			'date'     => $date
    			)
    		);

    	return json_decode($response);
    }
    
    /**
     * Проверка состояния отправленного сообщения
     * @param $id Идентификатор сообщения, который вернул сервис при отправке сообщения
     */
    public function getStatus($id)
    {
    	return json_decode($this->send_post(
    		"/status/",
    		array(
    			'user' => $this->username,
    			'password' => $this->password,
    			'id' => $id
    			)
    		));
    }

    /**
     * Список доступных подписей отправителя
     */
    public function getSenders()
    {
        return json_decode($this->send_post(
            "/senders/",
            array(
                'user' => $this->username,
                'password' => $this->password
            )
        ));
    }
 
}