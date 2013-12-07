<?PHP

if (version_compare(PHP_VERSION, '5.0.0') < 0)
{
	die('The CodeFlyer Framework requires PHP 5.x.x or greater.');
}


class login
{
	private $username;
	private $password;
	private $privilege;
	private $idgrupousuario;

	private $link;
	private $id;
	private $table;
	
	public $error;

  /**
   * Get userdata
   */

  public function get($var)
  {
    $var = trim(lcase($var));

    if ($var=='privilege')
    {
      $ret = $this->privilege;
    }
    else if ($var=='username')
    {
      $ret = $this->username;
    }
    else if ($var=='password')
    {
      $ret = $this->password;
    }
    else
    {
      $ret = false;
    }
    return $ret;
  }


  public function isadmin()
	{
    return $this->privilege == 1;
	}

	public function getdata($data)
	{
    $data = $this->clean(trim($data));
    $query = "SELECT $data FROM {$this->table} WHERE id='{$this->id}' LIMIT 1;";
    if ($result = mysql_query($query, $this->link))
    {
      if ($row = mysql_fetch_assoc($result))
      {
        return $row[$data];
      }
    }
	}

  /**
   * Set userdata
   */
	public function modlastlogin()
	{
		mysql_query("UPDATE {$this->table} SET lastactive = NOW() WHERE id = '{$this->id}';", $this->link);
		return mysql_affected_rows($this->link)==1 ? true : false;
		//mysql_query("INSERT {$this->table} SET lastactive = NOW() WHERE id = '{$this->id}';", $this->link);
	}

	public function lastlogin()
	{
		if ($result = mysql_query("SELECT lastactive FROM {$this->table} WHERE id = '{$this->id}' LIMIT 1", $this->link))
		{
			if ($row = mysql_fetch_assoc($result))
			{
				return $row['lastactive'];
			}
		}
	}

	/**
	 * Login core
	 */
	public function inherit($session)
	{
    session_name(urldecode($session));
	}

	public function getSID()
	{
    return "PHPSESSID=".session_id();
	}

  public function login($username, $password, $remember = false)
  {
    $username = $this->clean($username);
    $password = md5($password);
    $query    = "SELECT * FROM {$this->table} WHERE username = '$username' LIMIT 1;";
	

    if ($result = mysql_query($query, $this->link))
    {
      if ($row = mysql_fetch_assoc($result))
      {
        if ($row['password']==$password)
        {
          return $this->setSession($row, $remember);
        }
        else
        {
          $this->logout();
          $this->error = 'pi'; // Password Incorrect
          return false;
        }
      }
      $this->logout();
      $this->error = 'ui'; // Username Incorrect
      return false;
    }
    else
    {
      $this->logout();
      return false;
    }
  }
  
  // Construir la session y la cookie, y guardarlas en la base de datos.
  private function setSession(&$values, $remember = false, $init = true)
  {
    $this->id         = $values['id'];
    $this->username   = $values['username'];
    $this->password   = $values['password'];
    $this->privilege  = $values['privilege'];
	$this->lastactive  = $values['lastactive'];
	$this->idgrupousuario = $values['idgrupousuario'];

	$_SESSION['cf_login_id'] = htmlspecialchars($this->id);
    $_SESSION['cf_login_username'] = htmlspecialchars($this->username);
	$_SESSION['cf_login_privilege'] = htmlspecialchars($this->privilege);
	$_SESSION['cf_login_lastactive'] = htmlspecialchars($this->lastactive);
	$_SESSION['cf_login_grupousuario'] = htmlspecialchars($this->idgrupousuario);
	
    
    $cookie = md5($values['username'].date("Y-m-d"));
    if ($remember)
    {
      $this->update_cookie($cookie, true);
    }

    if ($init)
    {
      $session = session_id();
      mysql_query("UPDATE {$this->table} SET session='{$session}', cookie='{$cookie}' WHERE id='{$this->id}'", $this->link);
      $this->modlastlogin();
    }
    return true;
  }

  private function update_cookie($cookie)
  {
    //$this->create_cookie('cf_login_cookie', serialize(array($this->username, $this->password, $cookie)), time() + 31104000);
	$this->create_cookie('cf_login_cookie', serialize(array($this->username, $this->password, $cookie)), time() + 1800);
  }
  
  public function create_cookie($name, $value='', $maxage=0, $domain='', $path='', $secure=false, $HTTPOnly=false)
  {
    $ob = ini_get('output_buffering');
    
    if ($_SERVER['HTTPS'])
    {
      $secure = true;
    }

    // Abort the method if headers have already been sent, except when output buffering has been enabled
    if ( headers_sent() && (bool) $ob === false || strtolower($ob) == 'off' )
    {
      return false;
    }

    if (!(bool)$maxage)
    {
      $maxage = time()+3600;
    }

    if ( !empty($domain) )
    {
      // Fix the domain to accept domains with and without 'www.'.
      if ( strtolower( substr($domain, 0, 4) ) == 'www.' )
      {
        $domain = substr($domain, 4);
      }

      // Add the dot prefix to ensure compatibility with subdomains
      if ( substr($domain, 0, 1) != '.' )
      {
        $domain = '.'.$domain;
      }


      // Remove port information.
      $port = strpos($domain, ':');

      if ( $port !== false )
      {
        $domain = substr($domain, 0, $port);
      }
    }
    else
    {
      // Localhost compatibility
      $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
    }

    header('Set-Cookie: ' .rawurlencode($name).'='.rawurlencode($value)
                          .(empty($domain) ? '' : '; Domain='.$domain )
                          .(empty($maxage) ? '' : '; Max-Age='.$maxage)
                          .(empty($path)   ? '' : '; Path='.$path     )
                          .(!$secure       ? '' : '; Secure'          )
                          .(!$HTTPOnly     ? '' : '; HttpOnly'        )
          , false);
    return true;
  }

  // Devuelve true si el usuario está logueado. Caso contrario devuelve false.
  // @return bool
	public function logged()
	{
    // Verificar si el usuario contiene una cookie y cargar sus datos.
    $cookie = array();
    if ($_COOKIE['cf_login_cookie'])
    {
      list($cookie['username'], $cookie['password'], $cookie['serial']) = @unserialize(stripslashes($_COOKIE['cf_login_cookie']));
    }
  
    // Verificar si los datos de la cookie son válidos.
    if ($cookie['serial'] && $cookie['username'] && $cookie['password1'])
    {
      $query    = "SELECT * FROM {$this->table} WHERE (username = '{$cookie['username']}')  AND (cookie = '{$cookie['serial']}') LIMIT 1;";
    }
    else
    {
      // Verificar si los datos de session son válidos.
      $username = $_SESSION['cf_login_username'];
      $session  = session_id();
      $query    = "SELECT * FROM {$this->table} WHERE (username = '$username') AND (session = '$session') LIMIT 1;";
    }


    if ($result = mysql_query($query, $this->link))
    {
      if ($row = mysql_fetch_assoc($result))
      {
        return $this->setSession($row, false, false); // Log in
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
	}

  // Destruir sessión.
	public function logout()
	{
    $_SESSION['cf_login_username'] = '';
    $_SESSION['cf_login_cookie']   = TRUE;
    $this->create_cookie('cf_login_cookie', '', time() - 3600);
    mysql_query("UPDATE {$this->table} SET session='".strtoupper(md5(time()))."', cookie='".strtoupper(md5(time()))."' WHERE id='{$this->id}'", $this->link);

    $this->username = '';
    $this->password = '';
    $this->privilege = 0;
    $this->id = 0;
  }

  // Limpia la variable de carácteres impuros.
  private function clean($value)
  {
    if (get_magic_quotes_gpc())
    {
      $value = stripslashes($value);
    }
    $value = mysql_real_escape_string( htmlspecialchars( $value ) );
    return $value;
  }

  // Crea la clase y conecta con la base de datos.
  // @param array : ['host']     = 'localhost';
  //                ['table']    = Tabla en donde se almacenan los usuarios
  //                ['username'] = Nombre de usuario de la base de datos
  //                ['password'] = Password de la base de datos 
	public function __construct($array)
	{
    $this->table = $array['table'] ? $array['table'] : 'tm_usuarios';
    $this->link  = mysql_connect( $array['host'] ? $array['host'] : 'localhost', $array['username'], $array['password'], true );
    if (!$this->link)
    {
      die(mysql_error());
    }
    else
    {
      if (!mysql_select_db($array['database']))
      {
        die(mysql_error());
      }
    }

    if (isset($_GET['PHPSESSID']))
    {
      session_id($_GET['PHPSESSID']);
    }

    session_start();
	}

}
/*
if($_POST['tipo']=='n')
{
$usn=$_POST['usn'];
$clogeo=new clogeo;
$consultaw= $clogeo->acceso("$usn");
}
*/

?>