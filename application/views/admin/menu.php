<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .cursor {cursor: pointer; text-align: center;}
    .cursor .glyphicon {font-size: 20px; line-height: 33px;}
</style>
<div>
    <?php echo form_open('admin/menu', array('id' => 'menuForm')); ?>
    <!--<form id="menuForm" action="/admin/menu" method="post">-->
        <table id="menuTable" class="table table-bordered">
            <colgroup>
                <col width="10%">
                <col width="">
                <col width="15%">
            </colgroup>
            <thead>
            <tr>
                <th>드래그</th>
                <th>제목</th>
                <th>삭제</th>
            </tr>
            </thead>
            <tbody id="menuTbody">
            <?php foreach ($menu_list as $menu): ?>
                <tr>
                    <td class="cursor">
                        <input class="form-control" type="hidden" name="id[]" value="<?php echo $menu->id ?>">
                        <span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
                    </td>
                    <td><input class="form-control" type="text" name="title[]" value="<?php echo $menu->title ?>" required></td>
                    <td><button type="button" class="btn btn-danger" onclick="deleteRow(event, <?php echo $menu->id ?>)">삭제</button></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-right">
            <button type="button" class="btn btn-info" onclick="addRow()">행 추가</button>
            <button type="button" class="btn btn-primary" onclick="save()">저장</button>
        </div>
    </form>

</div>

<script>
    $(function(){
        $('#menuTbody').sortable();
    });

    function deleteRow(e, id) {
        if (confirm('정말 삭제할까요?')) {
            if (id == null) {
                $(e.target).parents('tr').remove();
            } else {
                var tokenName = 'X-XSRF-TOKEN';
                var token = '<?php echo $this->security->get_csrf_hash();?>';

                $.ajax({
                    url: '/admin/menu/' + id
                    ,method: 'delete'
                    ,beforeSend: function(xhr) {
                        xhr.setRequestHeader(tokenName, token);
                    }
                }).done(function(data) {
                    if (data == "success") {
                        $(e.target).parents('tr').remove();
                    } else {
                        alert("오류가 발생했습니다.");
                    }
                }).fail(function() {
                    alert('오류가 발생했습니다.');
                });
            }
        }
    }

    function addRow() {
        var rowTag = '<tr>'
            + '<td class="cursor"><span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span></td>'
            + '<td><input class="form-control" type="text" name="title[]" required></td>'
            + '<td><button type="button" class="btn btn-danger" onclick="deleteRow(event)">삭제</button></td>'
            + '</tr>';
        $('#menuTable').append(rowTag);
    }

    function save() {
        console.log('save');
        var isValid = true;
        $('#menuTbody tr').each(function(){
            var $input = $(this).find('input[name="title[]"]');
            if ($.trim($input.val()) == "") {
                $input.focus();
                alert('필수 항목이 비어있습니다.');
                isValid = false;
                return false;
            }
        });

        if (isValid) {
            $('#menuForm').submit();
        }

    }
</script>