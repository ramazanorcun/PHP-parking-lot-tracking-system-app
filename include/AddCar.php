<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        #addCarButton{
            background-color: green;
            color: white;
            border: none;
            width:200px;
            border-radius:50px;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>

</head>

<body>
    <div class="container">
    <button id="addCarButton">Ekle</button>

    </div>

    <script>
        $(document).ready(function() {
            $("#addCarButton").click(function(event) {
                event.preventDefault();

                //Enter tuşuna basıldığında aynı veri varsa ekleme işlemi yapıyordu. 
                //Çözümü
                $(document).on("keydown", function(event) {
                if (event.key === "Enter") {
                    addCar();
                }
            });
                var Plate = prompt("Araç Plakası:");
                var Brand = prompt("Araç Markası:");
                var currentTime = new Date();
                var hours = currentTime.getHours();
                var minutes = currentTime.getMinutes();
                if (hours < 10) {
                   hours = "0" + hours;
                }
                if (minutes < 10) {
                    minutes = "0" + minutes;
                }
                var entry_time = hours + ":" + minutes;

                if(Plate.trim() === "" || Brand.trim() === "") {
                    confirm("Boş alan Bırakmayınız");
                    return;
                }
                if (Plate !== null && Brand !== null) {
                    var confirmData = "Plaka: " + Plate + "\nMarka: " + Brand + "\nGiriş Saati: " + entry_time + "\nBu aracı eklemek istediğinizden emin misiniz?";
                    var price = 0;
                    
                    if (confirm(confirmData)) {
                        var carData = {
                            "Plate": Plate,
                            "Brand": Brand,
                            "entry_time": entry_time,
                            "price": price,
                            "exitTime":null

                        };

                        $.ajax({
                            type: "POST",
                            url: "veri_isleme.php",
                            data: { data: JSON.stringify(carData) },
                            success: function(response) {
                                alert(response);
                            }
                        });
                    } else {
                        alert("Araç eklenmedi.");
                    }
                } else {
                    alert("Araç eklemesi iptal edildi.");
                }
            });
        });
    </script>
</body>
</html>
