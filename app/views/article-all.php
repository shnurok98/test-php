

<div class="articles">
	<?php

		foreach($data as $row)
		{
			echo "<div data-id=\"{$row['id']}\" class=\"article-small\" onclick=\"onGetArticle(this)\">";
			echo '<h2>'.$row['title'].'</h2><p>'.$row['description'].'</p><p>Просмотров: '.$row['views'] . '</p>';
			echo '</div>';
		}

	?>
</div>