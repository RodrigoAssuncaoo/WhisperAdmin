<?php

$result = array(
  'success' => true,
  'status' => 'success',
  'message' => 'success',
  'data' => [
    'user' => [
      'id' => 1,
      'name' => 'jarg',
      'email' => 'jarg@gmial.com',
      'token' => 'token_value',
      'signup_tokens' => [
        ['id' => 1, 'name' => 'token1', 'value' => 'token1_value'],
        ['id' => 2, 'name' => 'token2', 'value' => 'token2_value'],
        ['id' => 3, 'name' => 'token3', 'value' => 'token3_value'],
      ]
    ]
  ]
);

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_NUMERIC_CHECK | JSON_UNESCAPED_LINE_TERMINATORS | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);


?>