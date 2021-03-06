<!DOCTYPE html>
<?php
$CI = &get_instance();
?>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta charset="utf-8" />
	<title>Keyword Tool</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/global.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/selectbox.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/pagination.css" />
    <script src="https://code.jquery.com/jquery-1.12.4.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/html5shiv.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/respond.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/selectbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/pagination/pagination.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/custom.js"></script>
</head>
<body>
<section class="wrap">
	<header>
		<ul>
			<li><a href="keyword Tool pro" target="_blank"></a></li>
			<li><a href="API Access" target="_blank"></a></li>
			<li><a href="Login" target="_blank" class="login"></a></li>
		</ul>
	</header>
	<section class="form-section">
		<h1>Keyword Tool</h1>
		<h2>Get 750+ Google Keyword Suggestions for free</h2>
		<?php
			if(!empty($error)){
				echo '<div class="alert alert-danger">
				  '.$error.' 
				</div>';
			}
		?>
        <?php
        $provider = getProvider();
        $rel = array('google'=> 'Google', 'youtube'=>'Youtube', 'bing' => 'Bing');
        ?>
        <ul class="social-link">
            <?php foreach($rel as $k => $name){
                $class = '';
                if($k == $provider){
                    $class = 'active';
                }
                ?>
                <li><a href="<?php echo base_url();?>keywords/<?=$k;?>?keyword=<?=urlencode($_GET['keyword']);?><?php echo '&domain='.$_GET['domain']; ?><?php echo '&language='.$_GET['language']; ?><?php echo '#'.$k; ?>" class="<?=$k?> <?=$class?> "><?=$name;?></a></li>
            <?php } ?>
        </ul>

		<div class="form-action search-form" id="google">
			<form action="<?php echo base_url(); ?>keywords/google" method="get">
				<input type="text" class="search-box" value="<?php  if(!empty($CI->session->userdata('keyword'))){echo $CI->session->userdata('keyword');} ?>" name="keyword" placeholder="Type a keyword and press enter"/>
				<a class="btn btn-primary btn-select btn-select-light">
					<input type="hidden" class="btn-select-input" id="" name="" value="" />
                    <select id="edit-domain" class="form-select form-control required select2-hidden-accessible" name="domain"  placeholder="type country" tabindex="-1" aria-hidden="true">

                        <option selected="selected" value="us" >United States</option>
                        <?php
                        $countries = get_bing_country();
                        foreach($countries as $cval => $cname){?>
                            <option value="<?= $cval;?>"<?= $CI->session->userdata('domain') == $cval?' selected="selected"' : ''?> ><?= $cname; ?></option>
                        <?php }

                        ?>
                    </select>

					<!--<span class="btn-select-value">Select an Item</span>
					<span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
					<ul>
						<li>--select--</li>
						<li>google.com (United States)</li>
						<li class="selected">google.ad (Andorra)</li>
						<li>google.ae (United Arab Emirates)</li>
						<li>google.ae (United Arab Emirates)</li>
					</ul>-->
				</a>
				<a class="btn btn-primary btn-select btn-select-light">
					<input type="hidden" class="btn-select-input" id="" name="" value="" />
                    <select id="edit-language" class="form-select form-control required select2-hidden-accessible" name="language" placeholder="type language" tabindex="-1" aria-hidden="true">
                        <option selected="selected" value="en">English</option>
                        <?php
                        $languages = get_bing_language();
                        foreach($languages as $lval => $lname){?>
                            <option value="<?= $lval;?>"<?= $CI->session->userdata('language') == $lval?'selected="selected"' : ''?> ><?= $lname; ?></option>
                        <?php }
                        ?>
                    </select>
					<!--<span class="btn-select-value">Select an Item</span>
					<span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
					<ul>
						<li>--select--</li>
						<li>English</li>
						<li class="selected">Afrikaans</li>
						<li>Albanian</li>
						<li>google.ae (United Arab Emirates)</li>
					</ul>-->
				</a>
				<button type="submit" class="search-button btn btn-common form-submit"></button>
			</form>
		</div>
		<div class="form-action search-form" id="youtube">
			<form action="<?php echo base_url(); ?>keywords/youtube/#youtube" method="get">
				<input type="text" class="search-box" name="keyword" value="<?php  if(!empty($CI->session->userdata('keyword'))){echo $CI->session->userdata('keyword');} ?>" placeholder="Type a keyword and press enter"//>
				<a class="btn btn-primary btn-select btn-select-light">
					<input type="hidden" class="btn-select-input" id="" name="" value="" />
                    <select id="edit-domain" class="form-select form-control required select2-hidden-accessible" name="domain"  placeholder="type country" tabindex="-1" aria-hidden="true">

                        <option selected="selected" value="us" >United States</option>
                        <?php
                        $countries = get_youtube_country();
                        foreach($countries as $cval => $cname){?>
                            <option value="<?= $cval;?>"<?= $CI->session->userdata('domain') == $cval?' selected="selected"' : ''?> ><?= $cname; ?></option>
                        <?php }

                        ?>
                    </select>
					<!--<span class="btn-select-value">Select an Item</span>
					<span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
					<ul>
						<li>--select--</li>
						<li>Indian</li>
						<li class="selected">Indonesia</li>
						<li>Iraq</li>
						<li>Island</li>
					</ul>-->
				</a>
				<a class="btn btn-primary btn-select btn-select-light">
					<input type="hidden" class="btn-select-input" id="" name="" value="" />
                    <select id="edit-language" class="form-select form-control required select2-hidden-accessible" name="language" placeholder="type language" tabindex="-1" aria-hidden="true">
                        <option selected="selected" value="en">English</option>
                        <?php
                        $languages = get_youtube_languages();
                        foreach($languages as $lval => $lname){?>
                            <option value="<?= $lval;?>"<?= $CI->session->userdata('language') == $lval?'selected="selected"' : ''?> ><?= $lname; ?></option>
                        <?php }
                        ?>
                    </select>
					<!--<span class="btn-select-value">Select an Item</span>
					<span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
					<ul>
						<li>--select--</li>
						<li>English</li>
						<li class="selected">Afrikaans</li>
						<li>Albanian</li>
						<li>google.ae (United Arab Emirates)</li>
					</ul>-->
				</a>
				<button type="submit" class="search-button btn btn-common form-submit"></button>
			</form>
		</div>
        <div class="form-action search-form" id="bing">
            <form action="<?php echo base_url(); ?>keywords/bing/#bing" method="get">
				<input type="text" class="search-box" name="keyword" value="<?php  if(!empty($CI->session->userdata('keyword'))){echo $CI->session->userdata('keyword');} ?>" placeholder="Type a keyword and press enter"/>
				<a class="btn btn-primary btn-select btn-select-light">
					<input type="hidden" class="btn-select-input" id="" name="" value="" />
                    <select id="edit-domain" class="form-select form-control required select2-hidden-accessible" name="domain"  placeholder="type country" tabindex="-1" aria-hidden="true">

                        <option selected="selected" value="us" >United States</option>
                        <?php
                        $countries = get_bing_country();
                        foreach($countries as $cval => $cname){?>
                            <option value="<?= $cval;?>"<?= $CI->session->userdata('domain') == $cval?' selected="selected"' : ''?> ><?= $cname; ?></option>
                        <?php }

                        ?>
                    </select>
					<!--<span class="btn-select-value">Select an Item</span>
					<span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
					<ul>
						<li>--select--</li>
						<li>google.com (United States)</li>
						<li class="selected">google.ad (Andorra)</li>
						<li>google.ae (United Arab Emirates)</li>
						<li>google.ae (United Arab Emirates)</li>
					</ul>-->
				</a>
				<a class="btn btn-primary btn-select btn-select-light">
					<input type="hidden" class="btn-select-input" id="" name="" value="" />
                    <select id="edit-language" class="form-select form-control required select2-hidden-accessible" name="language" placeholder="type language" tabindex="-1" aria-hidden="true">
                        <option selected="selected" value="en">English</option>
                        <?php
                        $languages = get_bing_language();
                        foreach($languages as $lval => $lname){?>
                            <option value="<?= $lval;?>"<?= $CI->session->userdata('language') == $lval?'selected="selected"' : ''?> ><?= $lname; ?></option>
                        <?php }
                        ?>
                    </select>
					<!--<span class="btn-select-value">Select an Item</span>
					<span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
					<ul>
						<li>--select--</li>
						<li>English</li>
						<li class="selected">Afrikaans</li>
						<li>Albanian</li>
						<li>google.ae (United Arab Emirates)</li>
					</ul>-->
				</a>
				<button type="submit" name="submit" class="search-button btn btn-common form-submit"></button>
			</form>
		</div>
		<!-- <div class="result-box" id="google">

        </div>
        <div class="result-box" id="Youtube">

        </div>
        <div class="result-box" id="Bing">

        </div> -->
	</section>
	<section class="main-wrap">
		<div class="row">
			<div class="col-xs-3 col-md-3">
				<div class="panel-group tab-settings" id="tab-settings-filter" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="tab-settings-filter-header">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#tab-settings-filter-body" aria-expanded="true" "="" aria-controls="tab-settings-filter-body" class="">
								Filter Results
								<i class="fa fa-angle-down pull-right" aria-hidden="true"></i>
								</a>
							</h4>
						</div>
						<div id="tab-settings-filter-body" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="tab-settings-filter-header" aria-expanded="true">
							<div class="panel-body">
								<form>
									<div>
										<div id="edit-include-keywords-wrapper" class="form-wrapper">
											<label>Find Keywords Within Search Results</label>
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-9 col-xs-9 col-no-padding">
														<div class="form-item">
															<input placeholder="e.g. new" type="text" value="" class="form-text form-control">
														</div>
													</div>
													<div class="col-md-3 col-xs-3 col-no-padding pull-right text-right">
														<div class="form-item form-group">
															<button type="button" value="Go" class="form-submit btn btn-common">Go</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-group tab-settings" id="tab-settings-negative-keywords" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="tab-settings-negative-keywords-header">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" href="#tab-settings-negative-keywords-body" aria-expanded="true" aria-controls="tab-settings-negative-keywords-body" class="">
									Negative Keywords                <span class="icon-question" title="" data-content="" data-placement="right" data-original-title="Negative Keywords"></span>
									<i class="fa fa-angle-down pull-right" aria-hidden="true"></i>
								</a>
							</h4>
						</div>
						<div id="tab-settings-negative-keywords-body" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="tab-settings-negative-keywords-header" aria-expanded="true">
							<div class="panel-body">
								<form>
									<div>
										<div class="form-wrapper">
											<div class="form-item form-group">
												<label class="element-invisible" for="edit-negative-keywords">Put your negative keywords in this box, one keyword per line </label>
												<div class="form-textarea-wrapper">
													<textarea placeholder="Put your negative keywords in this box, one keyword per line" cols="60" rows="8" class="form-textarea form-control"></textarea>
												</div>
												<div class="description">
													<a href="javascript:;" class="btn btn-link btn-sm pull-right btn-clear hidden">Clear all</a>
												</div>
											</div>
											<div class="counter"><strong>5</strong> negative keywords remaining</div>
											<div class="form-actions form-wrapper">
												<button type="submit" value="Save" class="form-submit btn btn-common">Save</button>
												<button type="submit" class="btn-link hidden btn-reload form-submit btn btn-common" value="Reload">Reload</button>
												<button type="submit" class="btn-link form-submit btn btn-common" value="Reset">Reset</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

