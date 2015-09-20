<?php
global $page_title_class, $page_small_title, $page_title_block, $page_title_prefix_block, $page_content_class;
?>
<header id="navbar" role="banner" class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <?php if (!empty($logo)): ?>
        <a class="logo pull-left" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>">
          <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
        </a>
      <?php endif; ?>

      <?php if (!empty($site_name)): ?>
        <h1 id="site-name">
          <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" class="brand"><?php print $site_name; ?></a>
        </h1>
      <?php endif; ?>
        <?php if (!empty($page['navigation'])): ?>
            <?php print render($page['navigation']); ?>
        <?php endif; ?>

      <?php if (!empty($primary_nav) || !empty($secondary_nav) || !empty($page['navigation'])): ?>
        <div class="nav-collapse collapse">
          <nav role="navigation">
            <?php if (!empty($primary_nav)): ?>
              <?php print render($primary_nav); ?>
            <?php endif; ?>
            <?php if (!empty($secondary_nav)): ?>
              <?php print render($secondary_nav); ?>
            <?php endif; ?>
          </nav>
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>

<div class="main-container container">

  <header role="banner" id="page-header">
    <?php if (!empty($site_slogan)): ?>
      <p class="lead"><?php print $site_slogan; ?></p>
    <?php endif; ?>

    <?php print render($page['header']); ?>
  </header> <!-- /#header -->

  <div class="row-fluid">

    <?php //if (!empty($page['sidebar_first'])): ?>
        <?php //print render($page['sidebar_first']); ?>
    <?php //endif; ?>  

    <section class="<?php print _bootstrap_content_span(); ?>">  
      <?php if (!empty($page['highlighted'])): ?>
        <div class="highlighted hero-unit"><?php print render($page['highlighted']); ?></div>
      <?php endif; ?>
        <div class="<?php echo (empty($page_content_class)?'':$page_content_class); ?>">
            <?php if (!empty($breadcrumb)): print '<div class="breadcrumb-container">'.$breadcrumb.'</div>'; endif;?>
            <a id="main-content"></a>
            <?php print render($title_prefix); ?>
            <?php if (!empty($title)): ?>
            <div class="page-title-bar">
                <div class="inner">
                    <?php if (!empty($page_title_prefix_block)) : ?>
                        <?php echo $page_title_prefix_block; ?>
                    <?php endif; ?>
                    <div class="page-title <?php echo (!empty($page_title_class)?$page_title_class:''); ?>">
                        <h1><?php echo $title; ?></h1>
                        <?php if (!empty($page_small_title)) : ?>
                            <?php echo $page_small_title; ?>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($page_title_block)) : ?>
                    <?php echo $page_title_block; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php print render($title_suffix); ?>

            <div class="page-container">
                <?php print $messages; ?>
                <?php if (!empty($page['sidebar_first'])): ?>
                	<div class="search_sidebar" style="float: left; width: 30%;">
                		<span class="search-block-title"> Filter by tags: </span><br/>
                		<?php print render($page['sidebar_first']); ?>
                	</div>
                	<div class="search_content" style="float: right; width: 65%;">
                <?php endif; ?>
                <?php if (!empty($tabs)): ?>
                    <?php print render($tabs); ?>
                <?php endif; ?>
                <?php if (!empty($page['help'])): ?>
                    <div class="well"><?php print render($page['help']); ?></div>
                <?php endif; ?>
                <?php if (!empty($action_links)): ?>
                    <ul class="action-links"><?php print render($action_links); ?></ul>
                <?php endif; ?>
                <?php print render($page['content']); ?>
                <?php if (!empty($page['sidebar_first'])): ?>
                	</div>
				<?php endif; ?>
            </div>
      </div>
    </section>

    <?php if (!empty($page['sidebar_second'])): ?>
      <aside class="span3" role="complementary">
        <?php print render($page['sidebar_second']); ?>
      </aside>  <!-- /#sidebar-second -->
    <?php endif; ?>

  </div>
</div>
<footer class="footer container">
  <?php print render($page['footer']); ?>
</footer>
