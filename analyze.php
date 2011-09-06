<p><span style="font-size:1.2em; font-weight:bold;">Text statistics</span><br/>
Basic statistics on text</p>
<p>word_count: <?=$textStat->word_count($content) ?></p>
<p>syllable_count: <?=$textStat->average_syllables_per_word($content) ?></p>
<p>average_syllables_per_word: <?=$textStat->average_syllables_per_word($content) ?></p>
<p>flesch_kincaid_reading_ease: <?=$textStat->flesch_kincaid_reading_ease($content) ?></p>
<p>flesch_kincaid_grade_level: <?=$textStat->flesch_kincaid_grade_level($content) ?></p>

<p><span style="font-size:1.2em; font-weight:bold;">Calias analysis</span><br/>
Terms and concepts referred to in text</p>
<table style="font-size:0.75em;">
<?foreach ($calaisData as $caliasItem) {
	if ($caliasItem['_typeGroup'] == 'topics') {
		echo '<tr><td><b>Topic</b></td> <td>' . $caliasItem['categoryName'] . '</td> <td>' . $caliasItem['score'] . '</td></tr>';
	} else {
		echo '<tr><td>' . $caliasItem['name'] . '</td> <td>' . $caliasItem['_type'] . '</td> <td>' . $caliasItem['relevance'] . '</td></tr>';
	}
}?>
</table>