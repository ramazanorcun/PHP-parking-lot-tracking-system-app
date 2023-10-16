<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["data"])) {
    $receivedData = json_decode($_POST["data"], true);

    
    $Plate = $receivedData["Plate"];
    $Brand = $receivedData["Brand"];
    $entry_time = $receivedData["entry_time"];
    $price = $receivedData["price"];

    $filename = 'Data.txt';
    $dataArray = array();

    if (file_exists($filename)) {
        $currentData = file_get_contents($filename);
        if (!empty($currentData)) {
            $dataArray = json_decode($currentData, true);
        }
    }

    // Aynı plakaya sahip aracı kontrol et
    $duplicateFound = false;
    foreach ($dataArray as $item) {
        if ($item["Plate"] == $Plate) {
            $duplicateFound = true;
            break;
        }
    }

    if ($duplicateFound) {
        echo "Bu plakaya sahip araç zaten eklenmiş.";
    } else {
        $lastIndex = 0;

        if (!empty($dataArray)) {
            //end En son girilen veriyi alır 
            $lastIndex = end($dataArray)["Sequence_no"];
        }
            // en son eklenen verinin numarasına +1 ekleyerek ekleme yapar
        $newIndex = $lastIndex + 1;

        $data = array(
            "Sequence_no" => $newIndex,
            "Plate" => $Plate,
            "Brand" => $Brand,
            "entry_time" => $entry_time,
            "price" => $price,
            "exit_time" => null

        );

        $dataArray[] = $data;

        $jsonData = json_encode($dataArray, JSON_PRETTY_PRINT) . "\n";
        
        if (file_put_contents($filename, $jsonData)) {
            echo "Araç  Girişi Yapıldı.";
        } else {
            echo "Araç Girişi yapılamadı.";
        }
    }

} else {
    echo "Geçersiz istek.";
}
?>