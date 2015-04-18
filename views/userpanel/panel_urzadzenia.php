<div class="panel panel-primary">
    <div class="panel-heading">Rejestracja tego urządzenia: wprowadź dane</div>
    <div class="panel-body">
        <form role="form" action="<?php echo URL; ?>deviceregister/register" method="POST">
            <div style="width:49%; float:left;">
                <div class="form-group">
                    <label>Nazwa urządzenia</label>
                    <input type="text" name="devname" class="form-control" placeholder="Samsung Galaxy"  value="<?php echo @$_POST["devname"];?>">
                </div>  
            </div>
            <div style="width:49%; float:right;"> 
                <div class="form-group">
                    <label>Typ urządzenia</label>
                    <input type="text" name="devtype" class="form-control" placeholder="Telefon" value="<?php echo @$_POST["devtype"];?>">
                </div>
            </div>
            <div style="clear:both;"></div>
            <button type="submit" class="btn btn-default">Zarejestruj</button>
        </form>
    </div>
</div>