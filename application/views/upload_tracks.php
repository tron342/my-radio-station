<link href="<?= base_url (); ?>assets/plugins/dropzone/css/dropzone.css" rel="stylesheet" type="text/css"/>
<div class="page-title">
    <h3>Upload Track</h3>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4><span class="semi-bold">Track</span> Drag & Drop <span class="semi-bold">Uploader</span></h4>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="grid-body no-border">
                <div class="row-fluid">
                    <form id="my-awesome-dropzone" action="<?= site_url ( '/track/do_upload' ); ?>"
                          class="dropzone no-margin"/>
                    <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                    <div class="fallback">
                        <input name="file" type="file" multiple=""/>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url (); ?>assets/plugins/dropzone/dropzone.js" type="text/javascript"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    $(document).ready(function () {
        console.log("Dropzone");
        new Dropzone("#my-awesome-dropzone", {
            paramName: "file",
            maxFilesize: 1024, // MB
            acceptedFiles: "audio/*",
            accept: function (file, done) {
                console.log(file);
                done();
            }
        });
    });
</script>
