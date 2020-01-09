<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

class ApiMethods
{
  private function VK_API($access_token, $method, $params)
  {
    $params['access_token'] = $access_token;
    $params['v'] = '5.100';
    $query = http_build_query($params);
    $url = 'https://api.vk.com/method/' . $method . '?' . $query;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $error = curl_error($curl);
    if ($error)
    {
      error_log($error);
      throw new Exception("Неудачный запрос: {$method}.");
    }
    curl_close($curl);

    $response_api = json_decode($json, true);
    if (!$response_api || !isset($response_api['response']))
    {
      error_log($json);
      throw new Exception("Неверный ответ на запрос: {$method}.");
    }
    return $response_api['response'];
  }

  public function MessagesSend($group_token, $peer_id, $message)
  {
    $this->VK_API($group_token, 'messages.send', array(
    'peer_id' => $peer_id,
    'message' => $message,
    'random_id' => rand(999999,9999999),
     ));
  }

  public function UsersGet($group_token, $user_id)
  {
    return $this->VK_API($group_token, 'users.get', array(
      'user_ids' => $user_id,
      'fields' => 'bdate',
      ));
  }
}
