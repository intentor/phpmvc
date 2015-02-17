<?php
namespace Intentor\PhpMVC;
/**
 * Generates menus based on JSON items.
 */
class MenuGenerator {
	/**
	 * Generates a menu.
	 * @param string $json JSON file.
	 * @param string $controller Current controller.
	 * @return return HTML string.
	 */
	public static function generate($json, $controller = '') {
		$descriptor = json_decode($json, TRUE);
		
		return self::create_menu($descriptor['menu']['items'], $descriptor['menu']['css'], $controller);
	}
	
	/**
	 * Creates a menu.
	 * @param array $items Menu items.
	 * @param array $css CSS classes.
	 * @param string $controller Current controller.
	 * @param boolean $children Indicates if the menu is a children from another.
	 */
	private static function create_menu($items, $css, $controller = '', $children = false) {
		$css_class = ($children ? $css['submenu'] : $css['menu']);
		
		$menu = '<ul';
		if (!empty($css_class)) $menu .= sprintf(' class="%s"', $css_class);
		$menu .= '>';
		$url = '';
		$class = '';
		
		foreach ($items as $item) {
			if(isset($item['controller']) && $item['controller'] == $controller){
				$class = (isset($item['class']))?$item['class']." ".$css['active']:$css['active'];
			}
			else if(isset($item['class'])){
				$class = $item['class'];
			}

			if (!isset($item['permissions']) || \Auth::has_permission($item['permissions'])) {
				$menu .= '<li';
				if (!empty($class)) $menu .= sprintf(' class="%s"', $class);
				$menu .= '>';
				$menu .= self::create_hyperlink($item);
					
				//Checks for children.
				if(isset($item['children'])) {
					$menu .= self::create_menu($item['children'], $css, $controller, true);
				}
			
				$menu .= '</li>';
			}
			
			$class = '';
		}
		
		$menu .= '</ul>';
		
		return $menu;
	}
	
	/**
	 * Creates a hyperlink.
	 * @param array $item Menu item.
	 * @return string
	 */
	private static function create_hyperlink($item) {
		$link = '';
		$url;
		
		if(isset($item['url'])) {
			$url = $item['url'];
		} else {
			if(isset($item['controller'])) {
				if(isset($item['action'])) {
					$url = $item['controller'].'/'.$item['action'].'/';
				} else {
					$url = $item['controller'].'/index/';
				}
			}
		}
		
		if (isset($url)) {			
			$href = get_url($url);
			$title = constant($item['title']);
			$attrs = isset($item['attributes']) ? ' '.$item['attributes'] : '';
			$name = constant($item['name']);
			$pre_html = isset($item['pre_html']) ? $item['pre_html'].' ' : '';
			$post_html = isset($item['post_html']) ? ' '.$item['post_html'] : '';
			
			$link = sprintf('<a href="%s" title="%s"%s>%s<span>%s</span>%s</a>', $href, $title, $attrs, $pre_html, $name, $post_html);
		} else {
			$link = sprintf('<span title="%s">%s</span>', constant($item['title']), constant($item['name']));
		}
		
		return $link;
	}
}