BX.ready(function() {
    // Получаем ID сделки из параметров компонента
    let dealId = BX.componentParameters && BX.componentParameters.DEAL_ID ? BX.componentParameters.DEAL_ID : null;
    let onlyDeal = true;
    let search = '';
    let sort = 'asc';
    let page = 1;

    // Рендер кнопки добавления или предупреждения
    function renderAddButton() {
        if (!dealId) {
            BX('tg-add-wrap').innerHTML = '<span style="color: #d0021b; font-weight: bold;">Перед добавлением, сохраните сделку</span>';
        } else {
            BX('tg-add-wrap').innerHTML = '<button id="tg-add" class="ui-btn ui-btn-primary">Добавить</button>';
            BX.bind(BX('tg-add'), 'click', function() {
                showAddModal();
            });
        }
    }

    // Загрузка и отображение таблицы
    function loadTable() {
        BX.ajax.runComponentAction('testgalygin:dealdata', 'list', {
            mode: 'class',
            data: {
                params: {
                    DEAL_ID: dealId,
                    ONLY_DEAL: onlyDeal,
                    SEARCH: search,
                    SORT: sort,
                    PAGE: page
                }
            }
        }).then(function(response) {
            if (response.data.success) {
                renderTable(response.data.items);
                renderPagination(response.data.total, response.data.page, response.data.pageSize);
                renderAddButton();
            }
        });
    }

    // Рендер таблицы с данными
    function renderTable(items) {
        let html = '<table class="ui-table ui-table-hover ui-table-striped"><thead><tr><th>Название</th><th>Описание</th><th></th></tr></thead><tbody>';
        if (items.length === 0) {
            html += '<tr><td colspan="3">Нет данных</td></tr>';
        } else {
            items.forEach(function(item) {
                html += `<tr class="tg-row" data-id="${item.ID}">
                    <td>${BX.util.htmlspecialchars(item.UF_NAME||'')}</td>
                    <td>${BX.util.htmlspecialchars(item.UF_DESCRIPTION||'')}</td>
                    <td><button class="ui-btn ui-btn-danger-light tg-delete" data-id="${item.ID}" title="Удалить">✕</button></td>
                </tr>`;
            });
        }
        html += '</tbody></table>';
        BX('tg-table-wrap').innerHTML = html;
        // Обработчик удаления
        BX.findChild(BX('tg-table-wrap'), {tag:'table'}, true).querySelectorAll('.tg-delete').forEach(function(btn) {
            BX.bind(btn, 'click', function(e) {
                e.stopPropagation();
                let id = btn.getAttribute('data-id');
                if (confirm('Удалить запись?')) {
                    BX.ajax.runComponentAction('testgalygin:dealdata', 'delete', {
                        mode: 'class',
                        data: {id: id}
                    }).then(function(response) {
                        if (response.data.success) {
                            loadTable();
                        } else {
                            alert('Ошибка: ' + response.data.error);
                        }
                    });
                }
            });
        });
        // Обработчик клика по строке для редактирования
        BX.findChild(BX('tg-table-wrap'), {tag:'table'}, true).querySelectorAll('.tg-row').forEach(function(row) {
            BX.bind(row, 'click', function(e) {
                if (e.target.classList.contains('tg-delete')) return;
                let id = row.getAttribute('data-id');
                showEditModal(id);
            });
        });
    }

    // Пагинации
    function renderPagination(total, page, pageSize) {
        let pages = Math.ceil(total / pageSize);
        let html = '';
        for (let i = 1; i <= pages; i++) {
            html += `<a href="#" class="ui-pagination-num${i===page?' ui-pagination-num-active':''}" data-page="${i}">${i}</a> `;
        }
        BX('tg-pagination').innerHTML = html;
        // Обработчик на номера страниц
        BX.findChildren(BX('tg-pagination'), {tag:'a'}, true).forEach(function(a) {
            BX.bind(a, 'click', function(e) {
                e.preventDefault();
                page = parseInt(a.getAttribute('data-page'));
                loadTable();
            });
        });
    }

    // Поиск по названию
    BX.bind(BX('tg-search'), 'input', BX.debounce(function(e) {
        search = e.target.value;
        page = 1;
        loadTable();
    }, 300));

    // Переключение фильтра текущая сделка
    BX.bind(BX('tg-filter'), 'click', function() {
        onlyDeal = !onlyDeal;
        BX('tg-filter').innerText = onlyDeal ? 'Только текущая сделка' : 'Все сделки';
        page = 1;
        loadTable();
    });

    // Переключение сортировки по названию
    BX.bind(BX('tg-sort'), 'click', function() {
        sort = sort === 'asc' ? 'desc' : 'asc';
        BX('tg-sort').innerText = sort === 'asc' ? 'Сортировать по названию' : 'Сортировать по названию (обратно)';
        loadTable();
    });

    // Модального окна для добавления записи
    BX.bind(BX('tg-add'), 'click', function() {
        showAddModal();
    });

    // ФМодальное окна добавления
    function showAddModal() {
        let html = `<div class="ui-popup">
            <div class="ui-popup-header">Добавить запись</div>
            <div class="ui-popup-content">
                <div class="ui-ctl ui-ctl-textbox ui-ctl-w100" style="margin-bottom:10px;">
                    <input type="text" id="tg-add-name" class="ui-ctl-element" placeholder="Название">
                </div>
                <div class="ui-ctl ui-ctl-textbox ui-ctl-w100" style="margin-bottom:10px;">
                    <input type="text" id="tg-add-desc" class="ui-ctl-element" placeholder="Описание">
                </div>
            </div>
            <div class="ui-popup-footer">
                <button id="tg-add-save" class="ui-btn ui-btn-success">Сохранить</button>
                <button id="tg-add-cancel" class="ui-btn ui-btn-light-border">Отмена</button>
            </div>
        </div>`;
        BX('tg-modal').innerHTML = html;
        BX('tg-modal').style.display = 'block';
        // Кнопка отмены
        BX.bind(BX('tg-add-cancel'), 'click', function() {
            BX('tg-modal').style.display = 'none';
        });
        // Кнопка сохранения
        BX.bind(BX('tg-add-save'), 'click', function() {
            let name = BX('tg-add-name').value.trim();
            let desc = BX('tg-add-desc').value.trim();
            if (!name) {
                BX('tg-add-name').classList.add('ui-ctl-danger');
                return;
            }
            // Добавление
            BX.ajax.runComponentAction('testgalygin:dealdata', 'add', {
                mode: 'class',
                data: {
                    fields: {
                        NAME: name,
                        DESCRIPTION: desc,
                        DEAL_ID: dealId
                    }
                }
            }).then(function(response) {
                if (response.data.success) {
                    BX('tg-modal').style.display = 'none';
                    loadTable();
                } else {
                    alert('Ошибка: ' + response.data.error);
                }
            });
        });
    }

    // Модальное окно для редактирования
    function showEditModal(id) {
        BX.ajax.runComponentAction('testgalygin:dealdata', 'get', {
            mode: 'class',
            data: {id: id}
        }).then(function(response) {
            if (response.data.success) {
                let item = response.data.item;
                let html = `<div class="ui-popup">
                    <div class="ui-popup-header">Редактировать запись</div>
                    <div class="ui-popup-content">
                        <div class="ui-ctl ui-ctl-textbox ui-ctl-w100" style="margin-bottom:10px;">
                            <input type="text" id="tg-edit-name" class="ui-ctl-element" placeholder="Название" value="${BX.util.htmlspecialchars(item.UF_NAME||'')}">
                        </div>
                        <div class="ui-ctl ui-ctl-textbox ui-ctl-w100" style="margin-bottom:10px;">
                            <input type="text" id="tg-edit-desc" class="ui-ctl-element" placeholder="Описание" value="${BX.util.htmlspecialchars(item.UF_DESCRIPTION||'')}">
                        </div>
                    </div>
                    <div class="ui-popup-footer">
                        <button id="tg-edit-save" class="ui-btn ui-btn-success">Сохранить</button>
                        <button id="tg-edit-cancel" class="ui-btn ui-btn-light-border">Отмена</button>
                    </div>
                </div>`;
                BX('tg-modal').innerHTML = html;
                BX('tg-modal').style.display = 'block';
                BX.bind(BX('tg-edit-cancel'), 'click', function() {
                    BX('tg-modal').style.display = 'none';
                });
                BX.bind(BX('tg-edit-save'), 'click', function() {
                    let name = BX('tg-edit-name').value.trim();
                    let desc = BX('tg-edit-desc').value.trim();
                    if (!name) {
                        BX('tg-edit-name').classList.add('ui-ctl-danger');
                        return;
                    }
                    BX.ajax.runComponentAction('testgalygin:dealdata', 'update', {
                        mode: 'class',
                        data: {
                            id: id,
                            fields: {
                                NAME: name,
                                DESCRIPTION: desc
                            }
                        }
                    }).then(function(response) {
                        if (response.data.success) {
                            BX('tg-modal').style.display = 'none';
                            loadTable();
                        } else {
                            alert('Ошибка: ' + response.data.error);
                        }
                    });
                });
            } else {
                alert('Ошибка: ' + response.data.error);
            }
        });
    }

    loadTable();
}); 