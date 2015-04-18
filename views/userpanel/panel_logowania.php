
<div class="panel panel-primary">
				<div class="panel-heading">Logowanie: wprowadź dane</div>
				<div class="panel-body">
					<form role="form" action="<?php echo URL; ?>userpanel/login" method="POST">
					<div style="width:49%; float:left;">
					  <div class="form-group">
						<label>Login</label>
						<input type="text" name="user_name" class="form-control" placeholder="nick">
					  </div>  
					  
					 </div>
					 <div style="width:49%; float:right;"> 
					  <div class="form-group">
						<label>Hasło</label>
						<input type="password" name="user_pass" class="form-control" placeholder="*****">
					  </div>
					 </div>
					 <div style="clear:both;"></div>
					 <button type="submit" class="btn btn-default">Zaloguj</button>
					</form>
				</div>
			</div>