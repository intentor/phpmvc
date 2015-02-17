<?php
namespace Intentor\PhpMVC;

/**
 * Menu item class.
 */
class MenuItem {
	/** Menu item's name. */
	public $name;
	/** Menu item's title. */
	public $title;
	/** Menu item's controller (optional). */
	public $controller;
	/** Menu item's action (optional). */
	public $action;
	/** Menu item's URL (optional). */
	public $url;
	/** Menu items's permissions. It's a string with comma separated IDs. */
	public $permissions;
	/** Menu item's children. It's an Array. */
	public $children;
	/** Menu item's css class (optional). */
	public $class;
}