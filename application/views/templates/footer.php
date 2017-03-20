</section>

<div id="modalBg" class="modal"></div>
<img id="modalImg" class="modal">

<script type="text/javascript">
    // 메인 메뉴 클릭시 서브 메뉴 오픈
    $('.mainMenu').click(function(e){
        // body click 으로 전파되는것을 막는다.
        e.stopPropagation();

        $('.mainMenu').removeClass('selected');
        // 선택된 메뉴 보더 진하게
        // 하위 메뉴 노출
        $(this).addClass('selected');
    });
    // 열려있는 서브메뉴 닫기
    $('body').click(function(e){
        $('.mainMenu').removeClass('selected');
    });
</script>
</body>
</html>
