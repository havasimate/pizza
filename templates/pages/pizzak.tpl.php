<main>

<h1>Pizza History</h1>
<?php
$client =  new SoapClient(null, array(
    'location' => "http://gorogritabead1.nhely.hu/logicals/soapSzerver.php",
    'uri' => "http://gorogritabead1.nhely.hu/logicals/soapSzerver.php",
    'trace' => 1,
    'exceptions' => 1,
    'soap_version' => SOAP_1_2,
    'style' => SOAP_RPC,
    'use' => SOAP_ENCODED
));
try {
    $pizzak = $client->__soapCall("getPizza", array());
} catch (SoapFault $fault) {
    echo "SOAP hiba: " . $fault->getMessage();
}
$rendelesek = $client->getRendeles();
?>


<form style="background-color:transparent; width: fit-content; margin-left:auto; margin-right:auto;" name="tableselect" text="Tábla választás" method="POST">
    <select style="margin-left:auto; margin-right:auto;" name="pizza" required="required" onchange="javascript:tableselect.submit();">
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
        $pizzaNev = $_POST['pizza'];

        $response = $client->__soapCall("getPizzaAr", array($pizzaNev));

        if ($response->hibakod == 0) {
            echo "Az ár: " . $response["ar"] . " Ft";
        } else {
            echo "Hiba: " . $response->uzenet;
        }
    } catch (SoapFault $e) {
        echo "SOAP hiba: " . $e->getMessage();
    }



    ?>
        </select>
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

</form>
</main>