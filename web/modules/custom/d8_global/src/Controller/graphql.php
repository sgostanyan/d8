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

    $query = '{ query1: getCCNs(params: "6312Z") { id } }';

    $ep1 = 'https://graphqlzero.almansi.me/api';
    $ep2 = 'https://ccn.harmonie-mutuelle.fr/api/gql';

    $result = self::graphql_query($ep2,
      $query,
      [],
      'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6Ijk1ZDc3MDJlLWFmODEtNDUyYi1hYTY3LTEwMTViMDJkYzZjNSIsImlhdCI6MTYxMTIxODI3MSwiZXhwIjoxNjExMjI1NDcxLCJhdWQiOiIiLCJpc3MiOiJIYXJtb25pZSBNdXR1ZWxsZSIsInN1YiI6IkNvQ29OdXQifQ.YC90FRXdyeA5gM08K72quCGVhBdntYbkMKS3E1zrw1l0BtpBlIPmg8rJqY5CTkJtr0Dlw3C8GLNyxPgL2v4K_hvu5IMMpX3Avfh4m_lT_juIIWdduA48w5HBlvX3AR5mCTm7L0xzG_gSsWnedxX_tqLG6B1i51zFlvc3B8RQML8qa2vw9t9uD4LgDHAAXiESpdFRE1HjtkTolFKAtxU2h1YpZMdQpF7WzBKUCpODxDYpm4YN6XXOKT36Frf84qZ4XBWwqOsrkv3iW3lpb9fl_jl-dB4HHKh0uHNrLyBfsy1asBXhNP1u9gYbMBRHJ0exY4zE4POVBTAIOxaw6yshAA');

    return $result;
  }

  public static function graphql_query(string $endpoint, string $query, array $variables = [], string $token = NULL) {
    $headers = [
      'Content-Type: application/json',
    ];
    if (NULL !== $token) {
      $headers[] = "Authorization: bearer $token";
    }
$stop='';
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
      var_dump($error['message']);
      $stop='';
      //throw new \ErrorException($error['message'], $error['type']);
    }

    return json_decode($data, TRUE);
  }
}
