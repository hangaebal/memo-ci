<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script src="/js/jquery.form.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<link href="/css/summernote.css" rel="stylesheet">
<script src="/js/summernote.js"></script>
<style>
    #previewDiv {min-height: 100px;}
    #previewDiv:after {content:"";display:block; clear: both;}
    .previewItem {float: left;}
    .previewItem img {width: 200px;}
    .previewItem .delImg {cursor: pointer;}

</style>

<div>

    <?php echo form_open('admin/post/edit', array('id' => 'postForm')) ?>
        <input type="hidden" name="id" value="<?php echo $post->id ?>"/>
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-1 control-label">메뉴</label>
                <div class="col-sm-4">
                    <select class="form-control" name="menuId">
                        <?php foreach ($menu_list as $menu): ?>
                            <option value="<?php echo $menu->id ?>" <?php echo ($menu->id == $post->menu_id)?'selected':'' ?>><?php echo $menu->title ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-1 control-label">제목</label>
                <div class="col-sm-7">
                    <input class="form-control" type="text" name="title" required value="<?php echo $post->title ?>">
                </div>
                <label class="col-sm-1 control-label">연도</label>
                <div class="col-sm-3">
                    <input class="form-control" type="text" name="year" required value="<?php echo $post->year ?>">
                </div>
            </div>
        </div>

        <hr>

        <?php if ($post->type === 'text'): ?>
            <div class="form-group typeText">
                <label class="control-label">글</label>
                <textarea class="form-control" name="contents" rows="10"><?php echo $post->contents ?></textarea>
            </div>
        <?php endif; ?>

        <?php if ($post->type === 'editor'): ?>
            <textarea name="contents" style="display: none"></textarea>
            <div id="summernote">
            </div>
            <script>
                $('#summernote').summernote({
                    minHeight: 500
                });

                $('#summernote').summernote('code', '<?php echo $post->contents ?>');
            </script>
        <?php endif; ?>

        <?php if ($post->type === 'image' || $post->type === 'video'): ?>
            <div class="typeImage typeVideo">
                <label class="control-label">미리보기</label>
                <div id="previewDiv">
                    <?php if ($post->type === 'image'): ?>
                        <?php foreach ($image_list as $image): ?>
                        <div class="previewItem">
                            <input type="hidden" name="imgId[]" value="<?php echo $image->id ?>">
                            <p><span class="glyphicon glyphicon-remove delImg" onclick="delImg(event, <?php echo $image->id ?>)"></span> <?php echo $image->title ?></p>
                            <!--TODO: 썸네일 적용-->
                            <img src="/upload/<?php echo $image->path ?>">
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($post->type === 'video'): ?>
                        <?php $image = $image_list[0]; ?>
                        <div class="previewItem">
                            <input type="hidden" name="imgId[]" value="<?php echo $image->id ?>">
                            <p><span class="glyphicon glyphicon-remove  delImg" onclick="delImg(event, <?php echo $image->id ?>)"></span></p>
                            <video src="/upload/<?php echo $image->path ?>" controls width="300"/>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php endif; ?>
    </form>

    <hr>
    <?php if ($post->type === 'image'): ?>
        <?php echo form_open_multipart('admin/post/image', array('id' => 'imageForm', 'class' => 'typeImage form-inline')) ?>
            <p><strong>이미지 등록</strong> <small>등록 후 드래그로 순서 변경 가능</small></p>
            <input type="hidden" name="type" value="image"/>
            <input id="imgTitle" class="form-control" type="text" name="imgTitle" placeholder="이미지 제목">
            <input id="imgFile" type="file" name="mFile" accept="image/*">
            <button class="btn btn-info btn-sm" type="submit">이미지 등록</button>
        </form>
    <?php endif; ?>

    <?php if ($post->type === 'video'): ?>
        <?php echo form_open_multipart('admin/post/image', array('id' => 'videoForm', 'class' => 'typeVideo')) ?>
            <p><strong>동영상 등록</strong></p>
            <input type="hidden" name="type" value="video"/>
            <input id="videoFile" type="file" name="mFile" accept="video/*">
            <button class="btn btn-info btn-sm" type="submit">동영상 등록</button>
        </form>
    <?php endif; ?>

    <div class="text-right">
        <button type="button" class="btn btn-danger" onclick="deletePost(<?php echo $post->id ?>)">삭제</button> |
        <button type="button" class="btn btn-primary" onclick="save()">저장</button>
    </div>

</div>

<script>
    $(function(){

        $('#previewDiv').sortable();

        $('#imageForm').ajaxForm({
            beforeSubmit: function (data, frm, opt) {
                if ($('#imgTitle').val() == '') {
                    alert('이미지 제목을 입력하세요.');
                    $('#imgTitle').focus();
                    return false;
                }
                if ($('#imgFile').val() == '') {
                    alert('파일을 선택하세요.');
                    $('#imgFile').focus();
                    return false;
                }
                return true;
            },
            success: function(data, statusText){
                if (data.status == 'success') {
                    var previewTag = '<div class="previewItem">'
                        +'<input type="hidden" name="imgId[]" value="'+data.id+'">'
                        +'<p><span class="glyphicon glyphicon-remove  delImg" onclick="delImg(event, '+data.id+')"></span> '+data.title+'</p>'
                        +'<img src="/upload/'+data.thumbPath+'">'
                        +'</div>';
                    $('#previewDiv').append(previewTag);
                } else {
                    console.log(data);
                    alert("오류가 발생했습니다.");
                }

                // 이미지 업로드 폼 초기화
                $('#imageForm')[0].reset();
            },
            error: function(e){
                console.log(e);
                alert("오류가 발생했습니다.");
            }
        });

        $('#videoForm').ajaxForm({
            beforeSubmit: function (data, frm, opt) {
                if ($('#videoFile').val() == '') {
                    alert('파일을 선택하세요.');
                    $('#videoFile').focus();
                    return false;
                }
                return true;
            },
            success: function(data, statusText){
                if (data.status == 'success') {
                    var previewTag = '<div class="previewItem">'
                        +'<input type="hidden" name="imgId[]" value="'+data.id+'">'
                        +'<p><span class="glyphicon glyphicon-remove delImg" onclick="delImg(event, '+data.id+')"></span></p>'
                        +'<video src="/upload/'+data.path+'" controls width="300">'
                        +'</div>';
                    $('#previewDiv').html(previewTag);
                } else {
                    console.log(data);
                    alert("오류가 발생했습니다.");
                }

                // 이미지 업로드 폼 초기화
                $('#videoForm')[0].reset();
            },
            error: function(e){
                console.log(e);
                alert("오류가 발생했습니다.");
            }
        });
    });

    function delImg(e, imageId) {
        if (confirm('정말 삭제할까요?')) {
            var tokenName = 'X-XSRF-TOKEN';
            var token = '<?php echo $this->security->get_csrf_hash();?>';

            $.ajax({
                url: '/admin/post/image/' + imageId
                ,method: 'delete'
                ,beforeSend: function(xhr) {
                    xhr.setRequestHeader(tokenName, token);
                }
            }).done(function(data) {
                if (data == 'success') {
                    $(e.target).parents('.previewItem').remove();
                } else {
                    console.log(data);
                    alert('오류가 발생했습니다.');
                }

            }).fail(function(e) {
                console.log(e);
                alert('오류가 발생했습니다.');
            });
        }
    }

    function save() {
        var isValid = true;

        var $input = $('input[name="title"]');
        if ($.trim($input.val()) == '') {
            $input.focus();
            alert('제목은 필수입니다.');
            isValid = false;
            return false;
        }

        //var type = $('#typeSelect').val();
        var type = '<?php echo $post->type ?>';
        if (type == 'text') {
            $input = $('textarea[name="contents"]');
            if ($.trim($input.val()) == '') {
                $input.focus();
                alert('글은 필수입니다.');
                isValid = false;
                return false;
            }
        } else if (type == 'editor') {
            if ($('#summernote').summernote('isEmpty') || $.trim($('#summernote').summernote('code')) == '') {
                $('#summernote').summernote('focus');
                alert('글은 필수입니다.');
                isValid = false;
                return false;
            }
            $('textarea[name="contents"]').val($('#summernote').summernote('code'));
        } else {
            if ($('input[name="imgId[]"]').length == 0) {
                alert('미디어 파일은 필수입니다.');
                isValid = false;
                return false;
            }
        }

        if (isValid) {
            $('#postForm').submit();
        }
    }

    function deletePost(id) {
        if (confirm('정말 삭제할까요?')) {

            var tokenName = 'X-XSRF-TOKEN';
            var token = '<?php echo $this->security->get_csrf_hash();?>';

            $.ajax({
                url: '/admin/post/' + id
                ,method: 'delete'
                ,beforeSend: function(xhr) {
                    xhr.setRequestHeader(tokenName, token);
                }
            }).done(function(data) {
                var regex = /^(https?):\/\//;
                if (regex.test(data)) {
                    alert('삭제되었습니다.');
                    location.href = data;
                } else {
                    console.log(data);
                    alert('오류가 발생했습니다.');
                }

            }).fail(function(e) {
                console.log(e);
                alert('오류가 발생했습니다.');
            });
        }
    }

</script>