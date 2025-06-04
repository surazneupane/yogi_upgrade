<?php



?>


<div class="text_view-layout-wrapper">

	<div class="article-description-div">
	
		<?php // echo $article['description']; ?>
		
		<?php 
		
			$insertion = $outstream;
			$paragraph_id = 2;
			$content = $article['description'];
			$closing_p = '</p>';
			$paragraphs = explode( $closing_p, $content );
			foreach ($paragraphs as $index => $paragraph) {
				// Only add closing tag to non-empty paragraphs
				if ( trim( $paragraph ) ) {
					// Adding closing markup now, rather than at implode, means insertion
					// is outside of the paragraph markup, and not just inside of it.
					$paragraphs[$index] .= $closing_p;
				}
				// + 1 allows for considering the first paragraph as #1, not #0.
				if ( $paragraph_id == $index + 1 ) {
					$paragraphs[$index] .= $insertion;
				}
			}
			$html = implode( '', $paragraphs );
			
			echo $html;
		
		?>
		
	</div>
	
	<div class="article-tags-div">
		<?php if(!empty($article_tags)) { ?>
			<label class="tag-label">سمات : </label>
			<?php foreach ($article_tags AS $article_tag) { ?>
			<span><a href="<?php echo site_url('مقالات-الوسم/'.$article_tag['alias']); ?>"><?php echo $article_tag['title']; ?></a></span>
			<?php } ?>
		<?php } ?>
	</div>

</div>