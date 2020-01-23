<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE HTML>
<html>
    <head>
        <title><?php
            if (isset($_this->pageContents->meta_title->content) && !empty($_this->pageContents->meta_title->content)) {
                echo $_this->pageContents->meta_title->content . ' | ';
            } else {
                $title = (isset($_this->pageContents->title) && !empty($_this->pageContents->title)) ? $_this->pagecontent->title->content : $_this->page->label;
                echo $title . ' | ';
            }
            ?> ORGANIZATION NAME HERE</title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
<?php
// set "expires" meta tag if page has an expiration date (set in controller)
if (isset($page_expiration) && strtotime($page_expiration) > time()) {
    ?>
            <meta name="expires" content="<?php echo date("D, d M, Y", strtotime($page_expiration)); ?>">
            <?php
        }

        $meta_name_tags = array("description" => "meta_description",
            "keywords" => "meta_keywords"
        );
        foreach ($meta_name_tags as $name => $block_name) {
            if (!isset($_this->pageContents->$block_name->content) || empty($_this->pageContents->$block_name->content)) {
                continue;
            }
            ?>
            <meta name="<?php echo $name ?>" content="<?php echo $_this->pageContents->$block_name->content ?>">
            <?php
        }
        $meta_properties_tags = array("og:title" => "meta_title",
            "og:image" => "og_image"
        );
        foreach ($meta_properties_tags as $name => $block_name) {
            if (!isset($_this->pageContents->$block_name->content) || empty($_this->pageContents->$block_name->content)) {
                continue;
            }
            ?>
            <meta property="<?php echo $name ?>" content="<?php echo $_this->pageContents->$block_name->content ?>">
            <?php
        }
        ?>

        <?php
        //loop and load each style
        foreach ($_this->template->styles as $style => $type) {
        echo html::style($style,array('media' => $type)), "\n";
        }
      

        //extra page-specific header code
        echo (isset($_this->pageContents->extra_head_code->content) && !empty($_this->pageContents->extra_head_code->content)) ? $_this->pageContents->extra_head_code->content : '';
        ?>
            <script src="/assets/js/modernizr.custom.2.8.3.min.js" type="text/javascript"></script>
            <script>
            var sessiontoken = "<?= Session::instance()->get('ybr_token'); ?>";
            var BASE_PATH = "/";// root path to this site
            var XHR_PUBLIC_PATH = BASE_PATH + "request/";// path to the front-facing "ajax" controller
        </script>
            
    </head>

    <body>
        <main>
            <aside id="leftColumn">
                
            </aside>
            <header>
                <h1>This is Shell "Theme 2"</h1>
                <h3>This is the outer-most template, aka "Shell"</h3>
                <p>For most sites, there is only this one shell template which includes the opening and closing HTML tags and probably the header and footer. It may also include the main navigation or other elements that are common accross the enitre site.</p>
            </header>
            <article>
                <?= $innerView; // aka "Layout" template ?>
            </article>
            <footer>
                    This is the skeleton structure of the site being developed.
            </footer>
                
        </main>
        
        <?php
          //loop and load each script
        foreach ($_this->template->scripts as $file) {
        echo HTML::script($file), "\n";
        }
        ?>
    </body>
</html>