<?php

namespace App;

use App\Models\Menu;
use App\Models\ConfigVariable;

class Utility
{
	public static $permissions = ['view', 'Add', 'Edit', 'Delete'];
	public static function getMenu()
	{
		$menus = Menu::all();
		$html = '<div class="side-bar">
					<div class="menu">
						<div class="item">';
		foreach ($menus as $menu) {
			if ($menu->parent_id == null) {
				$html .= '<a ' . (empty($menu->parent_id) ? 'href="' . url($menu->url) . '"' : '') . ' class="sub-btn">' . $menu->small_icon . $menu->title . (empty($menu->parent_id) ? '<i class="material-icons dropdown">keyboard_arrow_down</i>' : '') . '</a>';
			}
			if ($menu->parent_id) {
				$sub_menu = Menu::where('parent_id', $menu->parent_id)->get();
				$html .= '<div class="sub-menu">';
				foreach ($sub_menu as $parent) {
					$html .= '<a href="' . url($parent->url) . '" class="sub-item"> ' . $parent->small_icon . ' ' . $parent->title . ' </a>';
				}
				$html .= '</div>';
			}
		}
		$html .= '</div>
				</div>
			</div>';
		return $html;
	}

	public static function setting($var)
	{
		$config = ConfigVariable::where('key', $var)->get();
		foreach ($config as $configs) {
			return $configs->value;
		}
	}
}
