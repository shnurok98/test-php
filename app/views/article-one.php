

<div data-id="<= $row['id'] >" class="article-small article-full">
	<?php
	
		$row = $data[0];
		echo '<h2>'.$row['title'].'</h2>'.$row['body'].'Просмотров: '.$row['views'].'<p>Время публикации: '.$row['time_create'] . '</p>';

	?>
</div>