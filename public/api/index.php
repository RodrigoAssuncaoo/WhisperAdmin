<?php 

try {

    // Validar se tipo de pedido é o correto (GET)
    if ('GET' != $_SERVER['REQUEST_METHOD']) {
        throw new Exception('Método não permitido');
    }

    $result = array(
        'success' => true,
        'status' => 'success',
        'message' => 'API is working',
        'data' => [
            'name' =>'My mydev.Whisper.com',
            'version' => '1.0.0',
            'descripcion' => 'this is a simple API app example'
        ],
    );

    echo(json_encode($result, JSON_PRETTY_PRINT| JSON_UNESCAPED_SLASHES| JSON_UNESCAPED_UNICODE| JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_LINE_TERMINATORS | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION | JSON_INVALID_UTF8_SUBSTITUTE));


} catch (Exception $e) {
    $result = [
        'status' => 'FALSE',
        'message' => $e->getMessage()
    ];

    echo json_encode($result);

}

?>