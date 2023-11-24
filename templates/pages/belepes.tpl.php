<div class="container">
  <div class="row flex-column">
    <div class="col-sm-6">
      <form action="?oldal=belep" method="post">
        <fieldset>
          <legend>Bejelentkezés</legend>
          <div class="form-group">
            <label for="felhasznalo">Felhasználó:</label>
            <input type="text" class="form-control" id="felhasznalo" name="felhasznalo" required>
          </div>
          <div class="form-group">
            <label for="jelszo">Jelszó:</label>
            <input type="password" class="form-control" id="jelszo" name="jelszo" required>
          </div>
          <button type="submit" class="btn btn-primary" name="belepes">Belépés</button>
        </fieldset>
      </form>
    </div>

    <div class="col-sm-6">
      <form action="?oldal=regisztral" method="post">
        <fieldset>
          <legend>Regisztráció</legend>
          <div class="form-group">
            <label for="vezeteknev">Vezetéknév:</label>
            <input type="text" class="form-control" id="vezeteknev" name="vezeteknev" required>
          </div>
          <div class="form-group">
            <label for="utonev">Utónév:</label>
            <input type="text" class="form-control" id="utonev" name="utonev" required>
          </div>
          <div class="form-group">
            <label for="felhasznalo2">Felhasználói név:</label>
            <input type="text" class="form-control" id="felhasznalo2" name="felhasznalo" required>
          </div>
          <div class="form-group">
            <label for="jelszo2">Jelszó:</label>
            <input type="password" class="form-control" id="jelszo2" name="jelszo" required>
          </div>
          <button type="submit" class="btn btn-primary" name="regisztracio">Regisztráció</button>
        </fieldset>
      </form>
    </div>
  </div>
</div>
