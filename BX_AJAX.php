<?
// Подключаем пролог ядра Bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

// Устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");

// Инициализируем необходимые библиотеки JavaScript (AJAX)
CJSCore::Init(array('ajax'));
$sidAjax = 'testAjax';

// Проверяем, была ли отправлена AJAX-форма с идентификатором 'testAjax'.
if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
    $GLOBALS['APPLICATION']->RestartBuffer();
    // Отправляем ответ в формате JSON
    echo CUtil::PhpToJSObject(array(
        'RESULT' => 'HELLO',
        'ERROR' => ''
    ));
    die();
}

?>
<div class="group">
    <div id="block"></div>
    <div id="process">wait ... </div>
</div>
<script>
    window.BXDEBUG = true;

    // Функция для загрузки данных по AJAX
    function DEMOLoad() {
        BX.hide(BX("block"));
        BX.show(BX("process"));
        BX.ajax.loadJSON(
            '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
            DEMOResponse
        );
    }

    // Функция, обрабатывающая ответ от AJAX-запроса
    function DEMOResponse(data) {
        // Выводим отладочную информацию о полученных данных
        BX.debug('AJAX-DEMOResponse ', data);
        // Обновляем содержимое блока 'block' новыми данными
        BX("block").innerHTML = data.RESULT;
        BX.show(BX("block"));
        BX.hide(BX("process"));

        // Генерируем событие для обновления содержимого страницы
        BX.onCustomEvent(
            BX(BX("block")),
            'DEMOUpdate'
        );
    }

    BX.ready(function() {
        /*
        BX.addCustomEvent(BX("block"), 'DEMOUpdate', function() {
            window.location.href = window.location.href;
        });
        */

        // Скрываем блоки 'block' и 'process' при загрузке страницы
        BX.hide(BX("block"));
        BX.hide(BX("process"));

        // Привязываем обработчик события click к элементам с классом 'css_ajax'
        BX.bindDelegate(
            document.body, 'click', {className: 'css_ajax' },
            function(e) {
                if(!e)
                    e = window.event;

                // Выполняем загрузку данных по AJAX при клике на элементе
                DEMOLoad();
                return BX.PreventDefault(e);
            }
        );

    });

</script>
<div class="css_ajax">click Me</div>
<?
// Подключаем эпилог ядра Bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>