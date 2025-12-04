<div class="container" style="max-width:400px; margin-top:60px;">
    <h2>Login</h2>
    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>
    <form method="post">
        <div class="mb-3">
            <label for="email" class="form-label">E-Mail</label>
            <input type="email" class="form-control" name="email" id="email" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Passwort</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
