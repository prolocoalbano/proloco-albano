<?php
// 1. Riceve i dati inviati dal modulo HTML
$inputData = file_get_contents('php://input');
$nuovoMessaggio = json_decode($inputData, true);

if ($nuovoMessaggio) {
    // 2. Definisce il percorso del file JSON (che sta fuori dalla cartella pages)
    $filePercorso = '../dati/guestbook.json';

    // 3. Legge il contenuto attuale del file
    $contenutoAttuale = file_get_contents($filePercorso);
    $messaggi = json_decode($contenutoAttuale, true);

    // Se il file è vuoto o corrotto, crea un array vuoto
    if (!is_array($messaggi)) {
        $messaggi = [];
    }

    // 4. Aggiunge il nuovo messaggio in cima alla lista
    array_unshift($messaggi, [
        'nome' => htmlspecialchars($nuovoMessaggio['nome']),
        'testo' => htmlspecialchars($nuovoMessaggio['testo']),
        'data' => date('d/m/Y')
    ]);

    // 5. Salva tutto nel file JSON
    if (file_put_contents($filePercorso, json_encode($messaggi, JSON_PRETTY_PRINT))) {
        http_response_code(200);
        echo json_encode(["status" => "success"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error"]);
    }
}
?>
