<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["data"])) {
    $Data = json_decode($_POST["data"], true); 

    $entryTime = $Data["entryTime"]; 
    $exitTime = $Data["exitTime"]; 
    $priceHour = 0.50; 

    // strtotime tarih ve saati sayısal veri olarak tutar
    $entryTimestamp = strtotime($entryTime);
    $exitTimestamp = strtotime($exitTime);


    if ($exitTimestamp > $entryTimestamp) {
        $totalTimeInSeconds = $exitTimestamp - $entryTimestamp;
    } else {
        //24 saatlik farkı alır
        $totalTimeInSeconds = $exitTimestamp + (24 * 60 * 60) - $entryTimestamp; 
    }

    // Toplam süre hesaplama
    $totalTimeInHours = $totalTimeInSeconds / 3600; 

    // Ücreti hesaplama
    $totalPrice = $totalTimeInHours * $priceHour;
    //yuvarlama işlemi yapar  2
    $roundedPrice = round($totalPrice, 2);
    //ondalıklı basamak yapar 2
    $formattedPrice = number_format($roundedPrice, 2);

    $dataFile = '../Data.txt';
    $currentData = file_get_contents($dataFile);
    $dataArray = json_decode($currentData, true);

    // Var olan id'yi güncelleme işlemi 
    foreach ($dataArray as &$item) {
        if ($item["Sequence_no"] === $Data["id"]) {
            $item["exit_time"] = $exitTime;
            $item["price"] = $totalPrice;
            break; 
        }
    }
    $newData = json_encode($dataArray, JSON_PRETTY_PRINT);
    //Data.txt dosyasında ki tek satır olma problemini çözer
    $newData = str_replace('"},{"', "\"},\n{\"", $newData);

    file_put_contents($dataFile, $newData);

    $response = "Ücret: " . $formattedPrice;
    echo $response;
}

?>