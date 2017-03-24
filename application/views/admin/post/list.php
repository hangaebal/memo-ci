<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script src="/js/jquery.form.min.js"></script>
<style>
    .cursor {cursor: pointer; text-align: center;}
    .cursor .glyphicon {font-size: 20px; line-height: 33px;}
</style>
<?php echo form_open('admin/post/seq', array('id' => 'seqForm')) ?>
<!--<form id="seqForm" action="/admin/post/seq" method="post">-->
    <div>
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">메뉴 선택</label>
                <div class="col-sm-4">
                    <select id="menuId" class="form-control" name="menuId" onchange="changeMenu()">
                        <?php foreach ($menu_list as $menu): ?>
                            <option value="<?php echo $menu->id ?>" <?php echo ($this->input->get('menuId') == $menu->id)?'selected':'' ?>><?php echo $menu->title ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <table id="postTable" class="table table-bordered">
            <colgroup>
                <col width="10%">
                <col width="10%">
                <col width="50%">
                <col width="20%">
                <col width="10%">
            </colgroup>
            <thead>
            <tr>
                <th>드래그</th>
                <th>유형</th>
                <th>제목</th>
                <th>연도</th>
                <th>수정</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($post_list as $post): ?>
                <tr>
                    <td class="cursor">
                        <input class="form-control" type="hidden" name="id[]" value="<?php echo $post->id ?>">
                        <span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
                    </td>
                    <td><?php echo $post->type ?></td>
                    <td><?php echo $post->title ?></td>
                    <td><?php echo $post->year ?></td>
                    <td><a class="btn btn-info" href="/admin/post/edit/<?php echo $post->id ?>">수정</a></td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-right">
            <button type="submit" class="btn btn-success" style="float: left">순서 저장</button>
            <button type="button" class="btn btn-primary" onclick="createPost()">포스트 추가</button>
        </div>
    </div>
</form>

<script>
    $(function(){
        $('#postTable tbody').sortable();
    });

    function createPost() {
        location.href = '/admin/post/create?menuId=' + $('#menuId').val();
    }

    function changeMenu() {
        location.href = '/admin/post?menuId=' + $('#menuId').val();
    }

    $('#seqForm').ajaxForm({
        success: function(data, statusText){
            if (data == "success") {
                alert('순서가 저장되었습니다.');
            } else {
                alert("오류가 발생했습니다.");
            }
        },
        error: function(e){
            alert("오류가 발생했습니다.");
            console.log(e);
        }
    });
</script>