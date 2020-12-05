<?php

namespace Navindex\Auth\Authentication\Resetters;

use Navindex\Auth\Config\Auth;

class Resetter
{
	/**
	 * Configuration settings.
	 *
	 * @var \Navindex\Auth\Config\Auth
	 */
	protected $config;

	/**
	 * Error message.
	 *
	 * @var string
	 */
	protected $error;

	//--------------------------------------------------------------------

	/**
	 * Constructor.
	 *
	 * @param \Navindex\Auth\Config\Auth $config Configuration settings
	 */
	public function __construct(Auth $config)
	{
		$this->config = $config;
	}

	//--------------------------------------------------------------------

	/**
	 * Sends reset message to the user via specified class
	 * in `$activeResetter` setting in Navindex\Auth\Config\Auth.php.
	 *
	 * @param object $user User record
	 *
	 * @return bool True if the operation was successful, false otherwise
	 */
	public function send(object $user = null): bool
	{
		if (false === $this->config->passwordReset) {
			return true;
		}

		$className = $this->config->activeResetter;

		$class = new $className($this->config);

		if (false === $class->send($user)) {
			$this->error = $class->error();
			log_message('error', $this->error);

			return false;
		}

		return true;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the current error.
	 *
	 * @return null/string Error message or null
	 */
	public function error(): ?string
	{
		return $this->error;
	}
}