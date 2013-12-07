<?PHP
require 'generales/classes.php';
require 'generales/db.inc.php';

include('config.php');

$idu=$_SESSION['cf_login_id'];
$cu=$_SESSION['cf_login_username']; 
$nu=$_SESSION['cf_login_privilege'];
$tu=$_SESSION['cf_login_lastactive'];
$gu=$_SESSION['cf_login_grupousuario'];

//echo $gu;

  if (!$login->logged()) : 
  else:
    header('location: tramites.php');
  endif;
  
  	if ($_POST['submit'])
  	{
	if($login->login($_POST['usn'], $_POST['pwd']))
    {
		header('location: tramites.php');
	}
	else
    {
      ?>
	<script>
alert("Usuario y/o Password Invalidos");
</script>

	<?PHP
    }
	
$clogeo=new clogeo;
if($_POST['tipo']=='a')
{
$usn=$_POST['usn'];
$pwd=$_POST['pwd'];
$consultaw= $clogeo->acceso("$usn","$pwd");
}
	
	
  }

?>
    <link href="templates/css/bootstrap.min.css" rel="stylesheet" media="screen">   
<script src="http://code.jquery.com/jquery.js"></script>
    <script src="templates/js/bootstrap.min.js"></script>
<style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>


<title></title>
<body >
  <br />  <br />  <br />

  <table  width="100%" align="center" bgcolor="#FFFFFF"  cellpadding="0" cellspacing="0" >
    <tr>
      <td  width="100%" valign="top" align="center"></td>
    </tr>
    <tbody>
    
    
    
      <tr>
        <td width="100%" valign="top" align="center">
        
         <form method="post" class="form-signin">
        <table width="300" >

     <br /> <h4 class="form-signin-heading">   Sistema de Tramite Documentario</h4>
      
      <br /> <br /> 
    <tr>
    <td>&nbsp;</td>
    <td><span class="input-block-level">Usuario</span></td>
    <td><input type="text" name="usn" value="" class="input-block-level" /></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
  <td>&nbsp;</td>
    <td><span class="input-block-level">Password&nbsp;&nbsp;</span></td>
    <td><input type="password" name="pwd" value="" class="input-block-level"/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input name="submit" type="submit" id="submit" value="Entrar" class="btn btn-large btn-primary"  />
     <input name="tipo" value="a" type="hidden" size="50" />
    </td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
  <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

       </form> 


        </td>
      </tr>
    </tbody>
    <tbody>
      <tr>
        <td class="footer" width="83%" valign="top" align="center"><br />
            <br />
</td>
      </tr>
    </tbody>
  </table>
  <br />

</body>