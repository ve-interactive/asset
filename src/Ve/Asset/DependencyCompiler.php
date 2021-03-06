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

namespace Ve\Asset;

use Fuel\Common\Arr;
use Ve\Asset\Exception\UnsatisfiableDependencyException;

/**
 * Defines a basic array based dependency compiler
 *
 * @package Ve\Asset
 * @author  Ve Interactive PHP Team
 */
class DependencyCompiler implements DependencyCompilerInterface
{

	/**
	 * Contains a list of groups to compile
	 * @var array
	 */
	protected $groups = [];

	/**
	 * Resets all the assigned groups
	 *
	 * @return DependencyCompilerInterface
	 */
	public function reset()
	{
		$this->groups = [];
		return $this;
	}

	/**
	 * Add a group of files to the compiler. Sample config:
	 *
	 * ['group1', 'group2']
	 *
	 * This will ensure that all files and dependencies from group1 and group2 are included before the files in "files"
	 *
	 * @param string $name   Name of the group for later identification
	 * @param array  $deps
	 *
	 * @return DependencyCompilerInterface
	 */
	public function addGroup($name, $deps = [])
	{
		$this->groups[$name] = $deps;
		return $this;
	}

	/**
	 * Removes a group of files from the compiler. A removed group will not be included with the end result.
	 *
	 * @param string $name
	 *
	 * @return DependencyCompilerInterface
	 */
	public function removeGroup($name)
	{
		Arr::delete($this->groups, $name);
		return $this;
	}

	/**
	 * Should return a list of files in the order they are to be loaded in.
	 *
	 * @return array
	 */
	public function compile()
	{
		// Get a list of all the group names to use as a basis for which order to load from later
		$groups = array_keys($this->groups);
		$groupKeys = array_flip($groups);

		// For each group
		foreach ($this->groups as $name => $deps)
		{
			// Check each dependency is above the current group
			foreach ($deps as $dep)
			{
				// Check that the dep exists
				if ( ! array_key_exists($dep, $groupKeys))
				{
					throw new UnsatisfiableDependencyException('Unable to find dependency: '.$dep.' for: '.$name);
				}

				// If the current group is above the dependency
				$groupIndex = $groupKeys[$name];
				$depIndex = $groupKeys[$dep];

				if ($depIndex >= $groupIndex)
				{
					// Move the dep to the start
					unset($groups[$depIndex]);

					// Add the dep to the start of the groups array
					array_unshift($groups, $dep);

					// Make sure the key index is updated
					$groupKeys = array_flip($groups);
				}
			}
		}

		return $groups;
	}

	/**
	 * Returns the given group
	 *
	 * @param string $name
	 *
	 * @return mixed|null Null if no group is found
	 */
	public function getGroup($name)
	{
		return Arr::get($this->groups, $name, null);
	}
}
