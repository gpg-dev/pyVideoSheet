

<style>
	.cover-all {
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 10000000;
		background: #fff;
		margin-top: -5px;
	}
	body {  background: #fff !important;  }
	#wrap { padding-top: 5px; background: #fff; }
	.box { border-radius: 0 !important; }
	.search-group .glyphicon-search { padding: 3px }
	.search-group button, .search-group button, .filter-sort button, .filter-sort ul, .right-box, .panel { border-radius: 0; }
	.version {
		position: relative;
		margin-top: 50px;
		margin-right: 60px;
	}
	.nav.navbar-nav .sub-level:hover .dropdown-menu {
		display: block;
	}
	@media screen and (max-width: 768px){
		.nav.navbar-nav .dropdown-menu {
			margin-left: 50px;
		}
		.nav.navbar-nav .sub-level .dropdown-menu {
			display: block;
			margin-left: 50px;
		}
		#wrap .navbar-default .navbar-nav  li a {
			color: #fff;
		}
	}
	.alert.alert-success {
		background-color: #10516f;
		border-color: #072b64;
		color: #fff;
	}
	.alert.alert-danger {
		background-color: #e08e8e;
		border-color: #faebcc;
		color: #fff;
	}
	.panel-default > .panel-heading {
		color: #fff;
		background-color: #10516f;
		border-color: #0c1f3f;
	}
	.panel-default > .panel-heading > h1 {
		color: #fff;
	}
</style>
<br>
<div class="filter-sort" style="margin-top: 80px; display: block;">
	<div class="form-group" style="float: right;">
		<div class="col-sm-12">
			<?php
			$sortBy = ['month' => 'Popular This Month', 'week' => 'Popular This Week', 'new' => 'Newest'];
			?>
			<div class="dropdown">
				<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
					Sort: <?php echo @$sortBy[$_GET['sort']] ? @$sortBy[$_GET['sort']] : 'Newest' ?>
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<?php foreach ($sortBy as $key => $item) { ?>
						<li>
							<a href="/index.html?filter=<?php echo @$_GET['filter'] ?>&sort=<?php echo $key ?>">
								<?php echo $item ?>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="form-group" style="float: right;">
		<div class="col-sm-12">
			<?php
			$filterBy = ['all' => 'All', 'video' => 'Videos', 'audio' => 'Audios', 'image' => 'Pics'];
			?>
			<div class="dropdown">
				<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
					Filter: <?php echo @$postType[$_GET['filter']] ? @$filterBy[$_GET['filter']] : 'All' ?>
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<?php foreach ($filterBy as $key => $item) { ?>
						<li><a href="/index.html?filter=<?php echo $key ?>"><?php echo $item ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>