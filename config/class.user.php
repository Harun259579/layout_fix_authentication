<?php
if(session_status() === PHP_SESSION_NONE)
{
	session_start();
}

require_once __DIR__ . '/dbconfig.php';

class USER
{
	protected $db;

	protected $baseUrl;

	public function __construct($pdo = null)
	{
		global $conn;

		$this->db = $pdo instanceof PDO ? $pdo : (isset($conn) ? $conn : null);

		$this->baseUrl = defined('BASE_URL') ? BASE_URL : $this->guessBaseUrl();
	}

	function guessBaseUrl()
	{
		$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
		$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

		return $scheme . '://' .$host .'/ecommerce';

	}

	/* Helper Functions */

	public  function redirect($url)
	{
		header("Location: .$url");
		exit;
	}

	public function is_logged_in()
	{
		return !empty($_SESSION['user_id']);		
	}

	public function logout()
	{
		session_unset();
		session_destroy();
		return true;
	}

	public function getUserById($id)
	{
		$st = $this->db->prepare("SELECT id, username, email, verified FROM users WHERE id = ? LIMIT 1");
		$st->execute([$id]);
		return $st->fetch(PDO::FETCH_ASSOC);
	}

	/* Auth Core */

	public function register($username, $email, $password)
	{
		$st = $this->db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
		$st->execute([$email]);
		if($st->fetch())
		{
			throw new Exception("This email already registered!");
		}

		$token = bin2hex(random_bytes(16));
		$hash = password_hash($password, PASSWORD_DEFAULT);

		$in = $this->db->prepare("INSERT INTO users (username, email, password, token, verified) VALUES (?,?,?,?,0)");

		$in->execute([$username, $email, $hash, $token]);

		//send verification mail
		$verifyLink = $this->baseUrl . "/auth/verify.php?token=".urlencode($token)."&email=" . urlencode($email);

		$this->sendMail($email,"Verify Your Email","Click the link to verify:{$verifyLink}");

		return true;
	}

	public function login($email, $password)
	{
		$st = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");

		$st->execute([$email]);

		$u = $st->fetch(PDO::FETCH_ASSOC);

		if(!$u)
		{
			throw new Exception("Invalid Credentials");
		}

		if(!password_verify($password, $u['password']))
		{
			throw new Exception("Invalid Credentials");
		}

		if((int)$u['verified'] !== 1)
		{
			throw new Exception("Your Email is Not Verified. Please verify your email");
		}

		$_SESSION['user_id'] = $u['id'];
		$_SESSION['user_email'] = $u['email'];
		$_SESSION['user_name'] = $u['username'];

		return true;
	
	}

	public function verify($email, $token)
	{
		$st = $this->db->prepare("SELECT id, token, verified FROM users WHERE email = ? LIMIT 1");
		$st->execute([$email]);
		$u = $st->fetch(PDO:FETCH_ASSOC);

		if(!$u)
		{
			throw new Exception("Account Not Found!");
		}

		if((int)$u['verified === 1'])
		{
			return true; //already verified
		}

		if(!hash_equals($u['token'] ?? '', $token ?? ''))
		{
			throw new Exception("Invalid verification token");
		}

		$up = $this->db->prepare("UPDATE users SET verified = 1, token = NULL WHERE id = ?");
		$up->execute([$u['id']]);

		return true;
	}


}
?>