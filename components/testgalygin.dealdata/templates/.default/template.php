<!-- Основной контейнер секции -->
<div class="ui-entity-section">
    <div class="ui-entity-section-content">
        <!-- Панель управления: поиск, фильтр, сортировка, добавление -->
        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
            <input type="text" id="tg-search" class="ui-ctl-element ui-ctl-textbox" placeholder="Поиск по названию..." style="width: 200px;">
            <button id="tg-filter" class="ui-btn ui-btn-light-border">Только текущая сделка</button>
            <button id="tg-sort" class="ui-btn ui-btn-light-border">Сортировать по названию</button>
            <span id="tg-add-wrap"></span>
        </div>
        <!-- Контейнер для таблицы -->
        <div id="tg-table-wrap"></div>
        <!-- Контейнер для пагинации -->
        <div id="tg-pagination" style="margin-top: 10px;"></div>
    </div>
</div>
<!-- Модальное окно-->
<div id="tg-modal" style="display:none;"></div>
<script src="/local/components/testgalygin.dealdata/templates/.default/script.js"></script> 