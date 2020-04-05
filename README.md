# smsmarket
SMSMarket em PHP

### Documentação da API: https://smsmarket.docs.apiary.io

```php
<?php

include 'SMSMarket.php';

$sms=new SMSMarket ('usuario', 'senha');

// programa um sms para ser enviado em 5 minutos
$envio=$sms->sendSMS ('67999999999', 'Texto da mensagem.', 0, null, 55, date ('c', strtotime ('+5 minutes')));

print_r ($envio);

/*
// envia sms para varios números
$loteSMS=$sms->sendSMSMultiple ([
[
'number' => '67999999999',
'content' => 'Texto da mensagem.'
],
[
'number' => '67999999999',
'content' => 'Texto da mensagem.'
]
]);

print_r ($loteSMS);
*/

?>
```
