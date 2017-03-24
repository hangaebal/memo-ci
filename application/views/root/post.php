<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<article id="postArticle">
    <?php if ($post->type === 'text'): ?>
        <div><?php echo nl2br($post->contents) ?></div>
    <?php elseif ($post->type === 'editor'): ?>
        <div><?php echo $post->contents ?></div>
    <?php elseif ($post->type === 'video'): ?>
        <video src="/upload/<?php echo $image_list[0]->path?>" width="100%" controls></video>
    <?php elseif ($post->type === 'image'): ?>
        <?php foreach ($image_list as $image): ?>
            <div class="imageItem">
                <?php $thumb_path = substr($image->path, 0, strrpos($image->path, '.')).'_thumb'.substr($image->path, strrpos($image->path, '.'))?>
                <img src="/upload/<?php echo $thumb_path?>" data-path="/upload/<?php echo $image->path?>" class="modalOpen">
                <p><?php echo $image->title?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</article>

<script>
    $(function(){
        $('.modalOpen').click(function(e){
            e.preventDefault();

            $('#modalImg').attr('src', $(e.target).attr('data-path'));
            $('#modalImg').one('load', function() {
                modalToCenter();

                $('#modalBg').show();
                $('#modalImg').show();
            });
        });

        $('.modal').click(function(){
            $('#modalImg').hide();
            $('#modalBg').hide();
            $('#modalImg').removeAttr('src');
        });

        $(window).on('resize', function() {
            modalToCenter();
        });
    });

    function modalToCenter() {
        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        $('#modalImg').css('top', (height-$('#modalImg').outerHeight())/2 + 'px');
        $('#modalImg').css('left', (width-$('#modalImg').outerWidth())/2 + 'px');
    }

</script>