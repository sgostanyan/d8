<?php


class graphql {

  public static function go() {
    // curl \
    //-X POST \
    //-H "Content-Type: application/json" \
    //--data '{ "query": "{ countries { code name } } " }' \
    //https://countries.trevorblades.com/


    // curl -X POST -H "Content-Type: application/json" --data '{ "query": "{ user(id: 1) { id name } }" }' https://graphqlzero.almansi.me/api

    $params = [5, 3];
    $query = '{ ';
    foreach ($params as $key => $param) {
      $query .= 'query' . $key . ': user(id: ' . $param . ') { id name }';
    }
    $query .= ' }';
    // $qq = '{ query1: user(id: 1) { id name } query2: user(id: 6) { id name } }'

    $result = self::graphql_query('https://graphqlzero.almansi.me/api',
      $query,
      [],
      NULL);

    return $result;
  }

  public static function graphql_query(string $endpoint, string $query, array $variables = [], string $token = NULL) {
    $headers = [
      'Content-Type: application/json',
    ];
    if (NULL !== $token) {
      $headers[] = "Authorization: bearer $token";
    }

    if (FALSE === $data = @file_get_contents($endpoint,
        FALSE,
        stream_context_create([
          'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode([
              'query' => $query,
              //   'variables' => $variables
            ]),
          ],
        ]))) {
      $error = error_get_last();
      var_dump($error);
      throw new \ErrorException($error['message'], $error['type']);
    }

    return json_decode($data, TRUE);
  }
}
