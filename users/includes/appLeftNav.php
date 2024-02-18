<style>
    .ligneMenu{padding-top: 20px; padding-bottom: 20px; border-bottom: 1px dotted black; color: black;}
    .iconMenu{margin-right: 20px;}
</style>
<?php
if($user->isLoggedIn())
{
?>
        <ul class="nav nav-pills flex-column" >
         <li class="nav-item ligneMenu font-weight-bold" ><a href="appDashboard.php"><i class="fa fa-fw fa-dashboard iconMenu"></i>Tableau de bord</a></li>
         <li class="nav-item ligneMenu font-weight-bold" ><a href="appHumour.php"><i class="fa fa-smile-o iconMenu"></i>Humour</a></li>
         <li class="nav-item ligneMenu font-weight-bold" ><a href="appSpirit.php"><i class="fa fa-universal-access iconMenu"></i>Developpement Perso</a></li>
<?php

}

//liks for users not logged in
else 
{
?>
		    <li><a href="forgot_password.php"><i class="fa fa-fw fa-wrench"></i> Forgot Password</a></li>
<?php
if ($email_act)
{
?>
		    <li><a href="verify_resend.php"> Resend Activation Email</a></li>
		</ul>
<?php
}
}
?>