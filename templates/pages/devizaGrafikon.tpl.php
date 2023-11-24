<?php
function currencies()
{
    $client = new SoapClient("http://www.mnb.hu/arfolyamok.asmx?WSDL");
    $result = new SimpleXMLElement($client->GetCurrencies()->GetCurrenciesResult);
    $stack = [];
    foreach ($result->xpath("//Currencies/Curr") as $item) {
        $stack[] = $item[0]->__toString();
    }
    return $stack;
}

function exc_rates($start_date, $end_date, $currency)
{
    $soapClient = new SoapClient("http://www.mnb.hu/arfolyamok.asmx?singleWsdl");
    $res = $soapClient->GetExchangeRates(['startDate' => $start_date, 'endDate' => $end_date, 'currencyNames' => $currency]);
    return $res->GetExchangeRatesResult;
}

$today = date("m/d/Y");
$eredmeny = $rdate1 = $rdate2 = $currency1 = $currency2 = "";
$dev = $dev2 = $foo = 0.0;
$error = "A kiválasztott devizákra az adott napon nem található adat!";
$er = 0;

if (isset($_POST['datum1']) && isset($_POST['datum2']) && isset($_POST['penznem']) && isset($_POST['kuld']) && $_POST['datum1'] != "" && $_POST['datum2'] != "" && $_POST['penznem'] != "" && $_POST['penznem2'] != "") {
    $sdate1 = explode("/", $_POST['datum1']);
    $rdate1 = $sdate1[2] . "-" . $sdate1[0] . "-" . $sdate1[1];

    $sdate2 = explode("/", $_POST['datum2']);
    $rdate2 = $sdate2[2] . "-" . $sdate2[0] . "-" . $sdate2[1];

    $currency1 = $_POST['penznem'];
    $currency2 = $_POST['penznem2'];

    $eredmeny = simplexml_load_string(exc_rates($rdate1, $rdate2, $currency1 . ',' . $currency2));
}

$chartData = [];

foreach ($eredmeny->Day as $day) {
    $row = [
        'Dátum' => $day->attributes()->date->__toString()
    ];

    foreach ($day->Rate as $rate) {
        $row[$rate->attributes()->curr->__toString()] = (float)$rate->__toString();
    }

    $chartData[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deviza árfolyamok</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>

<div class="container">
    <h1 class="text-center mt-4 mb-0">Deviza árfolyam megtekintés egy adott időszakra</h1>

    <form class="mt-4" id="center" name="tableselect" text="Tábla választás" method="POST">
        <div class="form-group">
            <label for="datepicker1">Kezdő dátum:</label>
            <input type="text" class="form-control" name="datum1" id="datepicker1" required="required">
        </div>

        <div class="form-group">
            <label for="datepicker2">Vég dátum:</label>
            <input type="text" class="form-control" name="datum2" id="datepicker2" required="required">
        </div>

        <div class="form-group">
            <label for="penznem">Az első pénznem:</label>
            <select class="form-control" id="penznem" name="penznem" required="required">
                <option value="">Válasszon devizát!</option>
                <?php foreach (currencies() as $curr) : ?>
                    <option value="<?php echo $curr; ?>"><?php echo $curr; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="penznem2">A második pénznem:</label>
            <select class="form-control" id="penznem2" name="penznem2" required="required">
                <option value="">Válasszon devizát!</option>
                <?php foreach (currencies() as $curr) : ?>
                    <option value="<?php echo $curr; ?>"><?php echo $curr; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-outline-primary btn-lg btn-block" name="kuld">Küld</button>
        </div>
    </form>

    <?php if ($currency1 != "" && $currency2 != "" && $rdate1 != "" && $rdate2 != "" && $er == 0) : ?>
        <h3 class="mt-4 mb-0 text-center">A megadott devizák árfolyama a megadott időszakban:</h3>
        <h3 class="mt-3 text-center"><?php echo $currency1 . " => " . $currency2; ?></h3>

        <!-- Táblázat konténer -->
        <div class="table-container">
            <?php
            if ($eredmeny->count() > 0) {
                echo '<table border="1">';
                echo '<tr>';
                echo '<th>Dátum</th>';
                foreach ($eredmeny->Day[0]->Rate as $rate) {
                    echo '<th>' . $rate->attributes()->curr->__toString() . '</th>';
                }
                echo '</tr>';
                foreach ($eredmeny->Day as $day) {
                    echo '<tr>';
                    echo '<td>' . $day->attributes()->date->__toString() . '</td>';
                    foreach ($day->Rate as $rate) {
                        echo '<td>' . $rate->__toString() . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
            }
            ?>
        </div>

        <!-- Google Chart létrehozása -->
        <div id="googleChart" style="height: 300px;"></div>
    <?php endif; ?>
</div>

<script>
    $(function() {
        $("#datepicker1, #datepicker2").datepicker({
            maxDate: "<?php echo $today; ?>"
        });
    });

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var chartData = <?php echo json_encode($chartData); ?>;

        if (chartData.length > 0) {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Dátum');

            <?php foreach ($eredmeny->Day[0]->Rate as $rate) : ?>
                data.addColumn('number', '<?php echo $rate->attributes()->curr->__toString(); ?>');
            <?php endforeach; ?>

            chartData.forEach(function(rowData) {
                var chartRow = [];
                chartRow.push(rowData.Dátum);

                <?php foreach ($eredmeny->Day[0]->Rate as $rate) : ?>
                    chartRow.push(rowData.hasOwnProperty('<?php echo $rate->attributes()->curr->__toString(); ?>') ? rowData['<?php echo $rate->attributes()->curr->__toString(); ?>'] : null);
                <?php endforeach; ?>

                data.addRow(chartRow);
            });

            var options = {
                title: '<?php echo $currency1 . " => " . $currency2; ?>',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('googleChart'));
            chart.draw(data, options);
        }
    }
</script>
</body>
</html>
