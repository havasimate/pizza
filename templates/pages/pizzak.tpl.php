<main>

<h1>Pizza History</h1>
<?php
//Globális változók megadása és az adatbázisból kiolvasó függvények meghívása.
$client =  new SoapClient(null, array(
    'location' => "http://localhost/beadando1/logicals/soapSzerver.php",
    'uri' => "http://localhost/beadando1/logicals/soapSzerver.php",
    'trace' => 1,
    'exceptions' => 1,
    'soap_version' => SOAP_1_2,
    'style' => SOAP_RPC,
    'use' => SOAP_ENCODED
));
$pizzak = $client->__soapCall("getPizza", array());;
$rendelesek = $client->getRendeles();
?>


<form style="background-color:transparent; width: fit-content; margin-left:auto; margin-right:auto;" name="tableselect" text="Tábla választás" method="POST">
    <select style="margin-left:auto; margin-right:auto;" name="pizza" required="required" onchange="javascript:tableselect.submit();">
        <!--Legördülő menü, amiben kiválaszthatjuk a lekérdezni kívánt megyét--->
        <option value="">Válasszon pizzat!</option>
        <?php
        foreach ($pizzak['nev'] as $pizza) { ?>

            <option value="<?php echo $pizza['nev']; ?>" <?php if (isset($_POST['pizza']) && $_POST['pizza'] == $pizza['nev']) {
                                                                echo "selected=selected";
                                                            } ?>><?php echo $pizza['nev']; ?></option>

        <?php echo $pizza['nev']; } ?>
    </select>
    <?php

    try {
        $pizzaNev = $_POST['pizza']; // Ezt cseréld le a kiválasztott pizza névére

        $response = $client->__soapCall("getPizzaAr", array($pizzaNev));

        if ($response->hibakod == 0) {
            echo "Az ár: " . $response["ar"] . " Ft";
            // var_dump($response);
        } else {
            echo "Hiba: " . $response->uzenet;
        }
    } catch (SoapFault $e) {
        echo "SOAP hiba: " . $e->getMessage();
    }



    ?>
        </select>
        <!--A küldés gomb, amivel liírjuk egy táblázatba a kiválasztásokhoz kapcsolható szílerőműveket és tulajdonságaikat.-->
        <!-- <input style="margin-top:20px;" class="btn btn-outline-primary btn-sm btn-block" type="submit" name="kiir" value="Kiírás"> -->
        <?php
        //Ellenőrizzük, hogy választottunk-e értékeket a listákból és hogy a kiir gombot megnyomtuk-e.
        ?>

        <!-- <div class="table-responsive text-nowrap table-hover">
                <table class="table table-striped"> -->
        <!--Az erőművek kiírása táblázatba.-->
        <br>
        <br>
        <br>
        <table class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Név</th>
            <th scope="col">Darab</th>
            <th scope="col">Felvétel</th>
            <th scope="col">Kiszállítás</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rendelesek["rendeles"] as $rendeles) {
            if ($rendeles['pizzanev'] == $_POST['pizza']) { ?>
                <tr>
                    <td><?php echo $rendeles['pizzanev'] ?></td>
                    <td><?php echo $rendeles['darab'] ?></td>
                    <td><?php echo $rendeles['felvetel'] ?></td>
                    <td><?php echo $rendeles['kiszallitas'] ?></td>
                </tr>
        <?php }
        } ?>
    </tbody>
</table>

        <!-- </div> -->

</form>
</main>