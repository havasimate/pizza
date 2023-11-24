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
$eredmeny = $eredmeny2 = $rdate = $currency1 = $currency2 = "";
$dev = $dev2 = $foo = 0.0;
$error = "A kiválasztott devizákra az adott napon nem található adat!";
$er = 0;

if (isset($_POST['datum']) && isset($_POST['penznem']) && isset($_POST['kuld']) && $_POST['datum'] != "" && $_POST['penznem'] != "" && $_POST['penznem2'] != "") {
    $sdate = explode("/", $_POST['datum']);
    $rdate = $sdate[2] . "-" . $sdate[0] . "-" . $sdate[1];
    $currency1 = $_POST['penznem'];
    $currency2 = $_POST['penznem2'];

    if ($currency1 != "HUF" && $currency2 != "HUF") {
        $eredmeny = simplexml_load_string(exc_rates($rdate, $rdate, $currency1));
        $eredmeny2 = simplexml_load_string(exc_rates($rdate, $rdate, $currency2));

        if ($eredmeny->count() != 0 && $eredmeny2->count() != 0) {
            $dev = floatval(str_replace(',', '.', trim($eredmeny->Day->Rate)));
            $dev2 = floatval(str_replace(',', '.', trim($eredmeny2->Day->Rate)));
            $foo = $dev / $dev2;
        } else {
            $er = 1;
        }
    } elseif ($currency1 == "HUF" && $currency2 != "HUF") {
        $eredmeny2 = simplexml_load_string(exc_rates($rdate, $rdate, $currency2));
        if ($eredmeny2->count() != 0) {
            $dev2 = floatval(str_replace(',', '.', trim($eredmeny2->Day->Rate)));
            $foo = 1 / $dev2;
        } else {
            $er = 1;
        }
    } elseif ($currency1 != "HUF" && $currency2 == "HUF") {
        $eredmeny = simplexml_load_string(exc_rates($rdate, $rdate, $currency1));
        if ($eredmeny->count() != 0) {
            $dev = floatval(str_replace(',', '.', trim($eredmeny->Day->Rate)));
            $foo = $dev;
        } else {
            $er = 1;
        }
    } elseif ($currency1 == "HUF" && $currency2 == "HUF") {
        $foo = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deviza árfolyam</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <div class="container">
        <h1 class="text-center mt-4 mb-0">Deviza árfolyam megtekintés egy adott napra</h1>

        <form class="mt-4" id="center" name="tableselect" text="Tábla választás" method="POST">
            <div class="form-group">
                <label for="datepicker">Dátum:</label>
                <input type="text" class="form-control" name="datum" id="datepicker" required="required">
            </div>

            <div class="form-group">
                <label for="penznem">Az első pénznem:</label>
                <select class="form-control" id="penznem" name="penznem" required="required">
                    <option value="">Válasszon Devizát!</option>
                    <?php foreach (currencies() as $curr) : ?>
                        <option value="<?php echo $curr; ?>"><?php echo $curr; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="penznem2">A második pénznem:</label>
                <select class="form-control" id="penznem2" name="penznem2" required="required">
                    <option value="">Válasszon Devizát!</option>
                    <?php foreach (currencies() as $curr) : ?>
                        <option value="<?php echo $curr; ?>"><?php echo $curr; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-outline-primary btn-lg btn-block" name="kuld">Küld</button>
            </div>
        </form>

        <?php if ($currency1 != "" && $currency2 != "" && $rdate != "" && $er == 0) : ?>
    <h3 class="mt-4 mb-0 text-center">A megadott devizák atváltási aránya a megadott napon (<?php echo $rdate; ?>):</h3>
    <h3 class="mt-3 text-center"><?php echo $currency1 . " => " . $currency2; ?></h3>
    <h4 class="mb-0 pb-4 text-center"><?php echo ($foo != 0) ? $foo : $error; ?></h4>

    <!-- Chart konténer -->
    <div class="chart-container">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        // Adatok előkészítése Chart.js számára
        var data = {
            labels: ["<?php echo $currency1; ?>", "<?php echo $currency2; ?>"],
            datasets: [{
                label: 'Árfolyamok',
                data: [1, <?php echo $foo; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                ],
                borderWidth: 1
            }]
        };

        // Chart konfiguráció
        var config = {
            type: 'bar', // vagy 'line' a vonaldiagramhoz
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Chart létrehozása
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, config);
    </script>
<?php endif; ?>

    </div>

    <script>
        $(function() {
            $("#datepicker").datepicker({
                maxDate: "<?php echo $today; ?>"
            });
        });
    </script>
</body>

</html>