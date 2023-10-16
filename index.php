<?php 
require_once 'include/Navbar.php';
require_once 'include/AddCar.php';
require_once 'include/CarExit.php';


//dosyanın içeriğini okur
$data = file_get_contents('Data.txt');

?>
<!DOCTYPE html>
<html>
<head>
 
    <!-- Tabulator Kütüphanesini Dahil Etme -->
    <link href="https://unpkg.com/tabulator-tables@4.8.1/dist/css/tabulator.min.css" rel="stylesheet">
    
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.8.1/dist/js/tabulator.min.js"></script>
   
   <style>
        .delete-button{
            background-color: #ff3333;
            color: white;
            border: none;
            width:50px;
            border-radius:50px;
            padding: 5px 10px;
            cursor: pointer;
            }
            .exit-button{
            background-color: blue;
            margin-left:5px;
            color: white;
            border: none;
            width:100px;
            border-radius:50px;
            padding: 5px 10px;
            cursor: pointer;
            }
  </style>
</head>
<body>
<div id="customers" class=" m-5">

    <script type="text/javascript">

        var tabledata = <?php echo $data; ?>;   
        tabledata.reverse();
        
        var table = new Tabulator("#customers", {
           
        rowFormatter: function(row) {  
        var data = row.getData();

        if (data.exit_time !== null) {
            row.getElement().style.backgroundColor = "red";
        } else if (data.Sequence_no === tabledata[0].Sequence_no) {
            // en son eklenen veri yeşil renkte görünür.
            row.getElement().style.backgroundColor = "green";
            setTimeout(function() {
                row.getElement().style.backgroundColor = "";
            }, 1000);
        }
        },
            data: tabledata,
            layout: "fitColumns",
            pagination: "local",
            paginationSize: 10,
            tooltips: true,
            columns: [
                {
                    title: "Sıra No",
                    field: "Sequence_no",
                    sorter: "number",
                    width: 200,
                    
                },
                {
                    title: "Araç plaka",
                    field: "Plate",
                    sorter: "string",
                    headerFilter: true,
                    
                    width: 200,    
                },
                {
                    title: "Araç Markası",
                    field: "Brand",
                    sorter: "string ",
                    hozAlign: "left", 
                },
                {
                    title: "Giriş Saati",
                    field: "entry_time",
                    sorter: "date",
                    hozAlign: "center",
                    editor: "select",
                }, 
                {
                    title: "Çıkış Saati",
                    field: "exit_time",
                    sorter: "date",
                    hozAlign: "center",
                    editor: "select",
                }, 
                {
                    title: "Ücret",
                    field: "price",
                    sorter: "number",
                    hozAlign: "center",
                    formatter: function(cell, formatterParams, onRendered) {
                    var value = cell.getValue();
                    // 2 ondalık basamağı yuvarlar
                    var formattedValue = parseFloat(value).toFixed(2); 
                    if (parseFloat(formattedValue) < 0.50) {
                        formattedValue = "0.00";
                    }
                    return formattedValue + " ₺"; 
                    },
                    editor: "select",
                }, 
                {
                    title: "İşlemler",
                    field: "operations",
                    formatter: function(cell, formatterParams, onRendered) {
                    var data = cell.getRow().getData();
                
                    var deleteButton = document.createElement("button");
                    deleteButton.className = "delete-button";
                    deleteButton.innerText = "Sil";
                    
                    var exitButton = document.createElement("button");
                    exitButton.className = "exit-button";
                    exitButton.innerText = "Çıkış Yap";

                    deleteButton.addEventListener("click", function() {
                    var idToDelete = data.Sequence_no;
                    
                    var confirmation = confirm("Bu veriyi silmek istediğinizden emin misiniz?");
                    if (confirmation) {
                        $.ajax({
                            type: "POST",
                            url: "inc/Delete.php",
                            data: { id: idToDelete },
                            success: function(response) {
                                alert(response);
                            }
                        });
                    }
                });

                exitButton.addEventListener("click", function(id) {
                    exitData =  data.Sequence_no
                    
                    var idToUpdate = data.Sequence_no;
                    
                    var currentTime = new Date();
                    var hours = currentTime.getHours();
                    var minutes = currentTime.getMinutes();
                    if (hours < 10) {
                    hours = "0" + hours;
                    }
                    if (minutes < 10) {
                        minutes = "0" + minutes;
                    }
                    var exitTime = hours + ":" + minutes;
                    var confirmation = "Sıra NO: " + data.Sequence_no + "\nMarka: " + data.Brand + "\nGiriş Saati: " + data.entry_time + "\nÇıkış saati: " + exitTime + "\nAraç çıkış yapmak istiyor onaylıyor musunuz?";
                    
                    if (confirm(confirmation)) {
                        var exitData = {
                            id: idToUpdate,
                            entryTime:data.entry_time,
                            exitTime: exitTime
                        };

                        $.ajax({
                            type: "POST",
                            url: "inc/CarExit.php",
                            data: { data: JSON.stringify(exitData) },
                            success: function(response) {
                                alert(response);
                            }
                        });
                    } else {
                        alert("Araç Çıkışı gerçekleşmedi.");
                    }
                });
                // İki butonu tablonun içine koymak için 
                    var buttonsContainer = document.createElement("div");
                        buttonsContainer.appendChild(deleteButton);
                        buttonsContainer.appendChild(exitButton);

                        return buttonsContainer;
                    },
                 hozAlign: "center",
                },
            ],
    });
    </script>
</div>
</body>
</html>