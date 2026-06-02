<div class="row-fluid">
    <div class="span6">
        <?php
        $data[ 'is_widget' ] = false;
        $this->load->view ( 'stream' , $data );
        ?>
    </div>
    <div class="span6">
        <div class="grid simple">
            <div class="grid-title no-border text-center">
                <h4><?php echo lang ( "widget_code" ) ?></h4>
            </div>
            <div class="grid-body no-border minheight300">
                <p><?php echo lang ( "widget_description" ) ?></p>
                <div class="code-copy">
                    <button id="copy-button" class="btn btn-mini btn-danger"><i
                                class="icon-copy"></i> <?php echo lang ( "copy" ) ?></button>
                    <pre><code class="html minheight200"><?php
                            $url = site_url ( "/stream/player/widget/" );
                            echo htmlentities ( '<iframe frameborder="0" width="100%" src="' . $url . '"></iframe>' );
                            ?></code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
