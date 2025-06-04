<?php



?>
<section class="content-header">
	<h1>
		Dashboard
		<small>Control panel</small>
	</h1>
	<!--<ol class="breadcrumb">
		<li class="active">Dashboard</li>
	</ol>-->
</section>
<section class="content">
	<div class="row">
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3><?php echo $users[0]['total_user_count']; ?></h3>
					<p>Users</p>
				</div>
				<div class="icon">
					<i class="ion ion-person-add"></i>
				</div>
				<a href="<?php echo site_url('administrator/users'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3><?php echo $categories[0]['total_category_count']; ?></h3>
					<p>Categories</p>
				</div>
				<div class="icon">
					<i class="ion ion-folder"></i>
				</div>
				<a href="<?php echo site_url('administrator/categories'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h3><?php echo $articles[0]['total_article_count']; ?></h3>
					<p>Articles</p>
				</div>
				<div class="icon">
					<i class="ion ion-document"></i>
				</div>
				<a href="<?php echo site_url('administrator/articles'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h3><?php echo $tags[0]['total_tag_count']; ?></h3>
					<p>Tags</p>
				</div>
				<div class="icon">
					<i class="ion ion-pricetags"></i>
				</div>
				<a href="<?php echo site_url('administrator/tags'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
	</div>
		
	
	<div class="row">
		<div class="col-md-6">
			<!-- PRODUCT LIST -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Recently Added Articles</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th width="10%">ID</th>
									<th width="20%">Created Date</th>
									<th>Title</th>
									<th>#</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($latest_articles AS $article) { ?>
								<?php 
								// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
								$article_preview_link = generateArticleLink($article['category_ids'], $article['alias']);
								?>
								<tr>
									<td><?php echo $article['id']; ?></td>
									<td><?php echo date('Y-m-d', strtotime($article['created_date'])); ?></td>
									<td>
										<?php echo anchor('administrator/articles/edit/'.$article['id'],$article['title'], array('class' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); ?> <span class="small">(Alias : <?php echo $article['alias']; ?>)</span>
										<?php $categorylist = explode(',', $article['category_ids']); ?>
										<div class="small">Category : <?php echo getCategoryName($categorylist); ?></div>
									</td>
									<td><?php echo anchor($article_preview_link,'<i class="fa fa-eye"></i>', array('target' => '_blank', 'class' => 'btn btn-info', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Preview')); ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<div class="col-md-6">
			<!-- PRODUCT LIST -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Most Viewed Articles</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
						<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th width="10%">ID</th>
									<th>Title</th>
									<th width="10%">Views</th>
									<th width="10%">#</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($mostread_articles AS $article) { ?>
								<?php 
								// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
								$article_preview_link = generateArticleLink($article['category_ids'], $article['alias']);
								?>
								<tr>
									<td><?php echo $article['id']; ?></td>
									<td>
										<?php echo anchor('administrator/articles/edit/'.$article['id'],$article['title'], array('class' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')); ?> <span class="small">(Alias : <?php echo $article['alias']; ?>)</span>
										<?php $categorylist = explode(',', $article['category_ids']); ?>
										<div class="small">Category : <?php echo getCategoryName($categorylist); ?></div>
									</td>
									<td>
										<?php
											if($article['hits'] <= 20) {
												$hit_class = 'bg-primary';
											} elseif($article['hits'] <= 40) {
												$hit_class = 'bg-primary';
											} elseif($article['hits'] <= 60) {
												$hit_class = 'bg-red';
											} elseif($article['hits'] <= 80) {
												$hit_class = 'bg-red';
											} elseif($article['hits'] <= 100) {
												$hit_class = 'bg-green';
											} else {
												$hit_class = 'bg-green';
											}
										?>
										<a href="javascript:void(0)" class="label <?php echo $hit_class; ?>" title="<?php echo $article['hits'].' Views'; ?>"><?php echo $article['hits']; ?></a>
									</td>
									<td><?php echo anchor($article_preview_link,'<i class="fa fa-eye"></i>', array('target' => '_blank', 'class' => 'btn btn-info', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Preview')); ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
	</div>
	
	
</section>
