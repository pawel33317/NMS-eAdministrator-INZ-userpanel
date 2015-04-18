<div class="panel panel-primary">
    <div class="panel-heading">Rejestracja: wprowadź dane</div>
    <div class="panel-body">
        <form role="form" action="<?php echo URL; ?>userpanel/register" method="POST">
            <div style="width:49%; float:left;">
                <div class="form-group">
                    <label>Login</label>
                    <input type="text" name="login" class="form-control" placeholder="nick"  value="<?php echo @$_POST["login"]; ?>">
                </div>
                <div class="form-group">
                    <label>Imię</label>
                    <input type="text" name="imie" class="form-control" placeholder="Jan"  value="<?php echo @$_POST["imie"]; ?>">
                </div>
                <div class="form-group">
                    <label>Pokój</label>
                    <input type="text" name="pokoj" class="form-control" placeholder="123" value="<?php echo @$_POST["pokoj"]; ?>">
                </div>
                <div class="form-group">
                    <label>Wydzial</label>
                    <input type="text" name="wydzial" class="form-control" placeholder="weeia"  value="<?php echo @$_POST["wydzial"]; ?>">
                </div>


            </div>
            <div style="width:49%; float:right;"> 
                <div class="form-group">
                    <label>Hasło</label>
                    <input type="password" name="haslo" class="form-control" placeholder="*****">
                </div>
                <div class="form-group">
                    <label>Powtórz hasło</label>
                    <input type="password" name="haslo2" class="form-control" placeholder="*****">
                </div>
                <div class="form-group">
                    <label>Nazwisko</label>
                    <input type="text" name="nazwisko" class="form-control" placeholder="Kowalski" value="<?php echo @$_POST["nazwisko"]; ?>">
                </div>
                <div class="form-group">
                    <label>Kierunek</label>
                    <input type="text" name="kierunek" class="form-control" placeholder="Ekonomia" value="<?php echo @$_POST["kierunek"]; ?>">
                </div>
            </div>
            <div style="clear:both;"></div>
            <button type="submit" class="btn btn-default">Zarejestruj</button>
        </form>
    </div>
</div>
