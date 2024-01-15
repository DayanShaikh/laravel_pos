<?php

namespace App;

use App\Models\Menu;

class Utility
{
	public static function getMenu()
	{
		$menus = Menu::all();
		$html = '<div class="side-bar">
					<div class="menu">
						<div class="item">';
		foreach ($menus as $menu) {
			if ($menu->parent_id == null) {
				$html .= '<a ' . (empty($menu->parent_id) ? 'href="' . $menu->url . '"' : '') . ' class="sub-btn">' . $menu->small_icon . $menu->title . (empty($menu->parent_id) ? '<i class="fas fa-angle-right dropdown"></i>' : '') . '</a>';
			}
			if ($menu->parent_id) {
			$sub_menu = Menu::where('parent_id', $menu->parent_id)->get();
			$html .= '<div class="sub-menu">';
				foreach ($sub_menu as $parent) {
					$html .= '<a href="' . $parent->url . '" class="sub-item"> ' . $parent->small_icon . ' ' . $parent->title . ' </a>';
				}
				$html .= '</div>';
			}
		}
		$html .= '</div>
				</div>
			</div>';
		return $html;
	}
}
