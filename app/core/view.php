<?php

namespace Views;

class View {
	
	/**
	 * Подключает вьюху
	 * content_view - вью для контента
	 */
	function render($content_view, $data = null)
	{
		// Подключаем нужную вьюху
		include 'app/views/'.$content_view;
	}
}