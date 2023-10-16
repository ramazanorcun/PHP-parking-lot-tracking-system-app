<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $idToDelete = $_POST["id"];

    $filename = '../Data.txt';
    // file_exists dosyanın varlığını kontrol eder 
    if (file_exists($filename)) {
        $currentData = file_get_contents($filename);
        if (!empty($currentData)) {
            $dataArray = json_decode($currentData, true);
            
            // Silinecek veriyi bulur
            $itemToDelete = null;
            foreach ($dataArray as $index => $item) {
                if ($item["Sequence_no"] == $idToDelete) {
                    $itemToDelete = $item;
                    break;
                }
            }

            if ($itemToDelete !== null) {
                //strtotime tarihleri rondom sayılar olarak alır eşitler 
                $entryTimes = strtotime($itemToDelete["entry_time"]); 
                
                $currentTime = time();
                $timeMinutes = ( $entryTimes - $currentTime ) / 60;
                
                if ($timeMinutes <= 30) {
                    echo "Veri 30 dakikadan önce girildiği için silinemez.";
                } else {
                    // silme
                    array_splice($dataArray, $index, 1);

                    //JSON_PRETTY_PRINT daha okunaklı şekilde olmasını sağlar
                    // Güncellenmiş veriyi JSON formatına dönüştürür
                    $jsonData = json_encode($dataArray, JSON_PRETTY_PRINT) . "\n";

                    //Güncellenmiş JSON verisini belirtilen dosyaya yazar.
                    if (file_put_contents($filename, $jsonData)) {
                        echo "Veri başarıyla silindi.";
                    } else {
                        echo "Veri silinirken bir hata oluştu.";
                    }
                }
            } else {
                echo "Veri bulunamadı."; 
            }
        }
    } else {
        echo "Veri dosyası bulunamadı."; 
    }
} else {
    echo "Geçersiz istek."; 
}
?>
