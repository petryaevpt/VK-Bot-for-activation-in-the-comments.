<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

/*
Ох, как много работы впереди.
*/

require_once 'config.php';
require_once 'apimethods.php';

$data = json_decode(file_get_contents('php://input'));
$config = new ConfigTokens;
$api_methods = new ApiMethods;

switch ($data->type)
{
  case 'confirmation':
    echo $config->confirmation_token;
  break;

  case 'message_new':
    $message = $data->object->text;
    $peer_id = $data->object->peer_id ?: $data->object->user_id;

    $user_info = $api_methods->UsersGet($config->group_token, $peer_id)[0];
    $user_name = $user_info['first_name'];

    if ($message == 'Привет')
    {
      $api_methods->MessagesSend($config->group_token, $peer_id,
      "{$user_name}, ого, всё получилось!");
    }

    echo 'ok';
  break;
}
