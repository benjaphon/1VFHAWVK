<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();
$option_pc = array(
    "table" => "product_categories"
);
$query_pc = $db->select($option_pc);


$title = 'เพิ่มสินค้าใหม่';
/*
 * php code///////////**********************************************************
 */

/*
 * header***********************************************************************
 */
require 'assets/template/back/header.php';
/*
 * header***********************************************************************
 */
?>
<link href="<?php echo $baseUrl; ?>/assets/css/jquery-ui.css" rel="stylesheet">
<style>
@import "http://fonts.googleapis.com/css?family=Droid+Sans";
.upload{
background-color:red;
border:1px solid red;
color:#fff;
border-radius:5px;
padding:10px;
text-shadow:1px 1px 0 green;
box-shadow:2px 2px 15px rgba(0,0,0,.75)
}
.upload:hover{
cursor:pointer;
background:#c20b0b;
border:1px solid #c20b0b;
box-shadow:0 0 5px rgba(0,0,0,.75)
}
#x-img{
width:50px;
border:none;
height:50px;
margin-left:-20px;
vertical-align:top;
}
.abcd img{
width:200px;
padding:5px;
border:1px solid #e8debd
}
.ck-editor__editable {
    min-height: 200px;
}
</style>

<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h1 class="page-header">เพิ่มข้อมูลใหม่</h1>
        </div>
    </div>
    <div class="row mt">
        <div class="col-lg-12">
            <div class="subhead">
                <a role="button" id="save" class="btn btn-success new-data" href="#">
                    <i class="glyphicon glyphicon-floppy-save"></i>
                    บันทึก
                </a>
                <a role="button" class="search-button btn btn-default" href="<?php echo $baseUrl; ?>/back/product">
                    <i class="glyphicon glyphicon-remove-circle"></i>
                    ยกเลิก
                </a>
            </div>
        </div>
    </div>
    <div class="row mt">
        <div class="form-horizontal" style="margin-top: 10px;">
            <form id="product-form" action="<?php echo $baseUrl; ?>/back/product/form_create" method="post" enctype="multipart/form-data">
                <div class="col-lg-6">
                    
                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">รูปภาพ</label>
                        <div class="col-sm-8">
                            <input type="button" id="add_more" class="upload" value="Add More Files"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="video" class="col-sm-2 control-label">วิดีโอ</label>
                        <div class="col-sm-8">
                            <!--<video width="400" controls>
                                <source id="video_here">
                                    Your browser does not support HTML5 video.
                            </video>-->
                            <input type="file" id="file_video" name="file_video" accept="video/*"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="Product_name" class="col-sm-2 control-label required">ชื่อสินค้า</label>
                        <div class="col-sm-8">
                            <input type="text" id="name" name="name" class="form-control input-sm" data-validation="required" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label required">ราคา</label>
                        <div class="col-sm-8">
                            <input type="text" id="price" name="price" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="wholesale_price" class="col-sm-2 control-label required">ราคาขายส่ง</label>
                        <div class="col-sm-8">
                            <input type="text" id="wholesale_price" name="wholesale_price" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="agent_price" class="col-sm-2 control-label required">ราคา ตท.</label>
                        <div class="col-sm-8">
                            <input type="text" id="agent_price" name="agent_price" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sale_price" class="col-sm-2 control-label required">ราคาขาย</label>
                        <div class="col-sm-8">
                            <input type="text" id="sale_price" name="sale_price" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ค่าส่ง (KERRY)</label>
                        <div class="col-sm-8">
                            <input type="text" name="kerry" value="0" class="form-control input-sm" data-validation="number" data-validation-allowing="float">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start_ship_date" class="col-sm-2 control-label">วันที่ส่งได้</label>
                        <div class="col-sm-8">
                            <input type="text" id="start_ship_date" name="start_ship_date" class="form-control input-sm" autocomplete='off'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="col-sm-2 control-label">จำนวน</label>
                        <div class="col-sm-8">
                            <input type="text" id="quantity" name="quantity" value="0" class="form-control input-sm" data-validation="number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="weight" class="col-sm-2 control-label">น้ำหนัก</label>
                        <div class="col-sm-8">
                            <input type="text" id="weight" name="weight" value="0" class="form-control input-sm" data-validation="number">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea id="editor" name="description" class="form-control input-sm"></textarea>
                        </div>
                    </div>
                        
                </div>
                <div class="col-lg-6">
                    
                    <div id="product_childs"></div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="button" id="add_more_product_child" class="form-control" value="เพิ่มสินค้าย่อย"/>
                        </div>
                    </div>
                        
                </div>
            </form>
        </div>
    </div>
  </section><!--/wrapper -->
</section><!-- /MAIN CONTENT -->

<?php
/*
 * footer***********************************************************************
 */
require 'assets/template/back/footer.php';
/*
 * footer***********************************************************************
 */
?>
<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl; ?>/assets/js/jquery.form-validator.min.js"></script>
<script type="text/javascript">
    var abc = 0;      // Declaring and defining global increment variable.
    var product_child_id = 0;
    $(document).ready(function () {
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
        $( "#start_ship_date" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $("#save").click(function (e) {

            /*if($("input[name='image[]']").length == 0 || !$("input[name='image[]']")[0].files[0]){
                alert("กรุณาเลือกรูปภาพอย่างน้อย 1 รูปค่ะ");
                return false;
            }*/

            if($("input[name='file_video']")[0].files[0] && $("input[name='file_video']")[0].files[0].size > 100000000){
                    alert("ขนาดไฟล์วิดีโอห้ามใหญ่เกิน 100MB ค่ะ");
                    return false;
            }

            for (i = 0; i < $("input[name='image[]']").length; i++) {

                var filename = $("input[name='image[]']")[i].files[0].name;
                var extension = filename.replace(/^.*\./, '').toLowerCase();
                switch (extension) {
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        break;
                    default:
                        alert("นามสกุลไฟล์ไม่ถูกต้องค่ะ");
                        return false;
                }

                if($("input[name='image[]']")[i].files[0].size > 1000000){
                    alert("ขนาดไฟล์ห้ามใหญ่เกิน 1MB ค่ะ");
                    return false;
                }
                
            }

            //alert("Success");
            $("#product-form").submit();
            
        });
        //  To add new input file field dynamically, on click of "Add More Files" button below function will be executed.
        $('#add_more').click(function() {
            $(this).before($("<div/>", {
                id: 'filediv'
            }).fadeIn('slow').append($("<input/>", {
                name: 'image[]',
                type: 'file',
                id: 'image',
                accept: 'image/*'
            })).after("<br>"));
        });

        $('#add_more_product_child').click(function(){
            var url = '<?php echo $baseUrl; ?>/back/product/form_generate_product_child';
            product_child_id += 1;
            $.get(url, {product_child_id: product_child_id}, function (data) {
                $(data).fadeIn('slow').appendTo($("#product_childs"));
            });
        });

        $('body').on('click', '.close.product-child', function(){
            var id = $(this).attr('product-child-id');
            $('#product_child_'+id).remove();
        });

        // Following function will executes on change event of file input to select different file.
        $('body').on('change', '#image', function() {
            if (this.files && this.files[0]) {

                var extension = this.files[0].name.replace(/^.*\./, '').toLowerCase();
                switch (extension) {
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        break;
                    default:
                        alert("นามสกุลไฟล์ไม่ถูกต้องค่ะ");
                        return false;
                }

                if(this.files[0].size > 1000000){
                    alert("ขนาดไฟล์ห้ามใหญ่เกิน 1MB ค่ะ");
                    return false;
                }


                abc += 1; // Incrementing global variable by 1.
                var z = abc - 1;
                //var x = $(this).parent().find('#previewimg' + z).remove();
                $(this).before("<div id='abcd" + abc + "' class='abcd'><img id='previewimg" + abc + "' src=''/></div>");
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
                $(this).hide();
                $("#abcd" + abc).append($("<img/>", {
                    id: 'x-img',
                    src: '<?php echo $baseUrl; ?>/assets/img/x.png',
                    alt: 'delete'
                }).click(function() {
                    $(this).parent().parent().next().remove();
                    $(this).parent().parent().remove();
                }));
            }
        });

        // To Preview Image
        function imageIsLoaded(e) {
            $('#previewimg' + abc).attr('src', e.target.result);
        };

        $(document).on("change", "#file_video", function() {
            if (this.files && this.files[0]) {

                if(this.files[0].size > 100000000){
                    alert("ขนาดไฟล์ห้ามใหญ่เกิน 100MB ค่ะ");
                    return false;
                }

                /*var reader = new FileReader();
                reader.onload = videoIsLoaded;
                reader.readAsDataURL(this.files[0]);*/
            }
        });

        // To Preview Video
        /*function videoIsLoaded(e) {
            $('#video_here').attr('src', e.target.result).parent().load();
        };*/
    });
    $.validate();
</script>