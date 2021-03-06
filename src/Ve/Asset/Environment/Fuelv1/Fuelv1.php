<?php

/**
 * This file is part of the VeAsset package.
 *
 * Released under the MIT licence.
 * This file is free to use and reuse as long as the original credits are preserved.
 *
 * @license MIT License
 * @copyright 2013 Ve Interactive
 */

namespace Ve\Asset\Environment\Fuelv1;

use Ve\Asset\Environment\AbstractDriver;

/**
 * Defines an interface with the FuelPHP v1.x framework
 *
 * @package Ve\Asset\Environment\Fuelv1
 * @author  Ve Interactive PHP Team
 */
class Fuelv1 extends AbstractDriver
{

	/**
	 * Ensure the right folder is added to finder to allow for config loading
	 */
	public function bootstrap(array $config = [])
	{
		\Finder::instance()->add_path(realpath(__DIR__.'../../../../../resources/'));
	}

	/**
	 * Returns a valid interface to interact with fuelphp v1 config class
	 * @return Config
	 */
	public function getConfigInstance()
	{
		return new Config;
	}

}
