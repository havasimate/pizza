<div class="container">
    <h2>Hír hozzáadása</h2>
    <form id="form" action="logicals/submit-hir.php" method="post">
      <div class="form-group">
        <label for="hir">Hír (max 300 karakter):</label>
        <textarea class="form-control" id="hir" name="hir"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Hozzáad</button>
    </form>
    <h2>Komment hozzáadása</h2>
    <form id="form" action="logicals/submit-komment.php" method="post">
      <div class="form-group">
        <label for="komment">Komment (max 300 karakter):</label>
        <textarea class="form-control" id="komment" name="komment"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Hozzáad</button>
    </form>
  </div>
  <script src="logicals/validator.js"></script>