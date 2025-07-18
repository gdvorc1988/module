Модуль "Тестовое Галыгин" для Битрикс24

1. Описание
Модуль добавляет новую вкладку "Данные" в карточку сделки CRM Битрикс24. Вкладка содержит таблицу с данными из HL-блока, связанными с текущей сделкой. Поддерживаются поиск, фильтрация, сортировка, пагинация, добавление, редактирование и удаление записей без перезагрузки страницы.

2. Функционал
- Вкладка "Данные" в карточке сделки
- Таблица с отображением максимум 3 записей на страницу
- Поиск по названию (без перезагрузки)
- Фильтр: только текущая сделка / все записи (без перезагрузки)
- Сортировка по названию (без перезагрузки)
- Пагинация (без перезагрузки)
- Добавление записи через модальное окно (автоматическая привязка к сделке)
- Редактирование записи по клику на строку (модальное окно)
- Удаление записи (кнопка в таблице)
- Использование стандартных стилей Битрикс24
- Если сделка не сохранена — добавление недоступно, выводится предупреждение

3. Установка
1. Скопируйте папки `install`, `lib`, `components` в директорию модуля на сервере.
2. Установите модуль через административную панель Битрикс24 (Marketplace → Установить из архива или вручную).
3. При установке автоматически создаётся HL-блок с полями:
   - UF_NAME (Название)
   - UF_DESCRIPTION (Описание)
   - UF_DEAL_ID (Привязка к сделке)
4. После установки в карточке сделки появится вкладка "Данные".

4. Структура
```
/components/testgalygin.dealdata/         — компонент для вкладки
/lib/eventhandlers.php                     — обработчик для добавления вкладки
/install/index.php                         — скрипт установки (создание HL-блока)
/install/uninstall.php                     — скрипт удаления (опционально)
```

5. Использование
- Вкладка "Данные" появляется автоматически в карточке сделки.
- Для добавления записи сделка должна быть сохранена (должен быть ID).
- Все действия с таблицей происходят без перезагрузки страницы.