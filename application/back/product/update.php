<?php
/*
 * php code///////////**********************************************************
 */
$db = new database();
$option_pd = array(
    "table" => "products",
    "condition" => "id='{$_GET['id']}' "
);
$query_pd = $db->select($option_pd);
$rs_pd = $db->get($query_pd);

$option_img = array(
    "table" => "images",
    "condition" => "ref_id='{$_GET['id']}' AND filetype = 'product' "
);
$query_img = $db->select($option_img);

$title = 'แก้ไขสินค้า : ' .$rs_pd['name'];
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
</style>
<section id="main-content">
  <section class="wrapper">
    <div class="row mt">
        <div class="col-lg-12">
            <h1 class="page-header">แก้ไขข้อมูล <?php echo $rs_pd['name']; ?></h1>
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
        <div class="col-lg-12">
            <div class="form-horizontal" style="margin-top: 10px;">
                <form id="product-form" action="<?php echo $baseUrl; ?>/back/product/form_update" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $rs_pd['id'];?>">
                    <div class="form-group">
                        <label for="product_image" class="col-sm-2 control-label required">รูปภาพประจำสินค้า</label>
                        <div class="col-sm-4">
                            <?php while ($rs_img = $db->get($query_img)) { ?>
                                <div id="div-image-<?php echo $rs_img['id']; ?>" class="abcd">
                                    <a title="" download="<?php echo $rs_img['filename']; ?>" href="<?php echo $baseUrl ?>/assets/upload/product/<?php echo $rs_img['filename']; ?>"><img src="<?php echo $baseUrl ?>/assets/upload/product/<?php echo $rs_img['filename'];?>"></a>
                                    <a title="" href="#" data-toggle="modal" data-target="#deleteModal<?php echo $rs_img['id'];?>"><img id="x-img" src="<?php echo $baseUrl; ?>/assets/img/x.png" alt="delete"></a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo $rs_img['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">แจ้งเตือนการลบข้อมูล</h4>
                                                </div>
                                                <div class="modal-body">
                                                    คุณยืนยันต้องการจะลบข้อมูลนี้ ใช่หรือไม่?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">ไม่ใช่</button>
                                                    <button type="button" class="btn btn-primary btn_delete_image" href="<?php echo $baseUrl; ?>/back/product/delete_image" data-dismiss="modal" value="<?php echo $rs_img['id']; ?>">ใช่ ยืนยันการลบ</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>                  
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image" class="col-sm-2 control-label">รูปภาพใหม่</label>
                        <div class="col-sm-4">
                        <input type="button" id="add_more" class="upload" value="Add More Files"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="video" class="col-sm-2 control-label">วิดีโอ</label>
                        <div class="col-sm-4">
                            <?php  if (!empty($rs_pd['video_filename'])) { ?>
                                <video width="400" controls>
                                    <source src="<?php echo $baseUrl ?>/assets/upload/product/<?php echo $rs_pd['video_filename']; ?>" id="video_here">
                                        Your browser does not support HTML5 video.
                                </video>
                            <?php } ?>
                            <input type="file" id="file_video" name="file_video" accept="video/*"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label required">ชื่อสินค้า</label>
                        <div class="col-sm-4">
                            <input type="text" id="name" name="name" class="form-control input-sm" data-validation="required" value="<?php echo $rs_pd['name']; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label required">ราคา</label>
                        <div class="col-sm-4">
                            <input type="text" id="price" name="price" class="form-control input-sm" data-validation="number" data-validation-allowing="float" value="<?php echo $rs_pd['price']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="agent_price" class="col-sm-2 control-label required">ราคา ตท.</label>
                        <div class="col-sm-4">
                            <input type="text" id="agent_price" name="agent_price" class="form-control input-sm" data-validation="number" data-validation-allowing="float" value="<?php echo $rs_pd['agent_price']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sale_price" class="col-sm-2 control-label required">ราคาขาย</label>
                        <div class="col-sm-4">
                            <input type="text" id="sale_price" name="sale_price" class="form-control input-sm" data-validation="number" data-validation-allowing="float" value="<?php echo $rs_pd['sale_price']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">ค่าส่ง (ธรรมดา/ลงทะเบียน/EMS/KERRY)</label>
                        <div class="col-sm-1">
                            <input type="text" name="parcel" class="form-control input-sm" data-validation="number" value="<?php echo $rs_pd['parcel']; ?>" data-validation-allowing="float">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="registered" class="form-control input-sm" data-validation="number" value="<?php echo $rs_pd['registered']; ?>" data-validation-allowing="float">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ems" class="form-control input-sm" data-validation="number" value="<?php echo $rs_pd['ems']; ?>" data-validation-allowing="float">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="kerry" class="form-control input-sm" data-validation="number" value="<?php echo $rs_pd['kerry']; ?>" data-validation-allowing="float">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start_ship_date" class="col-sm-2 control-label">วันที่ส่งได้</label>
                        <div class="col-sm-4">
                            <input type="text" id="start_ship_date" name="start_ship_date" class="form-control input-sm" value="<?php echo date('d-m-Y', strtotime($rs_pd['start_ship_date'])); ?>" autocomplete='off'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="col-sm-2 control-label">จำนวน</label>
                        <div class="col-sm-4">
                            <input type="text" id="quantity" name="quantity" value="<?php echo $rs_pd['quantity']; ?>" class="form-control input-sm" data-validation="number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="weight" class="col-sm-2 control-label">น้ำหนัก</label>
                        <div class="col-sm-4">
                            <input type="text" id="weight" name="weight" value="<?php echo $rs_pd['weight']; ?>" class="form-control input-sm" data-validation="number">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea id="editor" name="description" class="form-control input-sm"><?php echo $rs_pd['description']; ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
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
    $(document).ready(function () {
        CKEDITOR.replace('editor');
        $( "#start_ship_date" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $("#save").click(function (e) {
            
            /*if($("input[name='image[]']").length == 0 || !$("input[name='image[]']")[0].files[0]){
                alert("กรุณาเลือกรูปภาพอย่างน้อย 1 รูปค่ะ");
                return false;
            }*/

            if($("input[name='file_video']")[0].files[0] && $("input[name='file_video']")[0].files[0].size > 10000000){
                alert("ขนาดไฟล์วิดีโอห้ามใหญ่เกิน 10MB ค่ะ");
                return false;
            }
        
            for (i = 0; i < $("input[name='image[]']").length; i++) {

                if($("input[name='image[]']")[i].files[0]){
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

        /* Delete Image */
        $(document).on('click','.btn_delete_image',function(){
            var url = $(this).attr("href");
            var val_image_id = $(this).attr("value");

            $.post(url, { image_id: val_image_id }, function(){
                $('#div-image-'+val_image_id).remove();
            });

        });
        /***********************/

        $(document).on("change", "#file_video", function() {
            if (this.files && this.files[0]) {

                if(this.files[0].size > 10000000){
                    alert("ขนาดไฟล์ห้ามใหญ่เกิน 10MB ค่ะ");
                    return false;
                }
                
            }
        });

    });
    $.validate();
</script>

