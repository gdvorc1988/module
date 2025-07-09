<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// Подключаем необходимые классы Bitrix
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Engine\ContractControllerable;
use Bitrix\Main\Engine\ActionFilter;

// Основной класс компонента
class TestGalyginDealDataComponent extends CBitrixComponent implements ContractControllerable
{
    // Описываем ajax-действия компонента
    public function configureActions()
    {
        return [
            //добавление записи
            'add' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST]),
                    new ActionFilter\Csrf(),
                ],
            ],
            //получение списка записей
            'list' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST, ActionFilter\HttpMethod::METHOD_GET]),
                ],
            ],
            //удаление записи
            'delete' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST]),
                    new ActionFilter\Csrf(),
                ],
            ],
            //обновление записи
            'update' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST]),
                    new ActionFilter\Csrf(),
                ],
            ],
            //получение одной записи
            'get' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST, ActionFilter\HttpMethod::METHOD_GET]),
                ],
            ],
        ];
    }

    // Основной метод компонента (вызывает шаблон)
    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }

    //добавление записи в HL-блок
    public function addAction($fields)
    {
        // Проверяем подключение модуля highloadblock
        if (!Loader::includeModule('highloadblock')) return ['success'=>false, 'error'=>'HL модуль не подключен'];
        // Получаем HL-блок по имени
        $hlblock = HL\HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'TestGalygin']
        ])->fetch();
        if (!$hlblock) return ['success'=>false, 'error'=>'HL-блок не найден'];
        // Получаем сущность и класс для работы с HL-блоком
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();
        // Добавляем запись
        $result = $entityDataClass::add([
            'UF_NAME' => $fields['NAME'],
            'UF_DESCRIPTION' => $fields['DESCRIPTION'],
            'UF_DEAL_ID' => $fields['DEAL_ID'],
        ]);
        if ($result->isSuccess()) {
            return ['success'=>true];
        } else {
            return ['success'=>false, 'error'=>implode(', ', $result->getErrorMessages())];
        }
    }

    //получение списка записей с фильтрацией, поиском, сортировкой и пагинацией
    public function listAction($params)
    {
        //подключение модуля highloadblock
        if (!Loader::includeModule('highloadblock')) return ['success'=>false, 'error'=>'HL модуль не подключен'];
        // Получаем HL-блок
        $hlblock = HL\HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'TestGalygin']
        ])->fetch();
        if (!$hlblock) return ['success'=>false, 'error'=>'HL-блок не найден'];
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();
        // Фильтр
        $filter = [];
        if ($params['ONLY_DEAL'] && $params['DEAL_ID']) {
            $filter['=UF_DEAL_ID'] = $params['DEAL_ID'];
        }
        if ($params['SEARCH']) {
            $filter['%UF_NAME'] = $params['SEARCH'];
        }
        // Сортировку
        $order = ['UF_NAME' => ($params['SORT'] === 'desc' ? 'DESC' : 'ASC')];
        // Параметры пагинации
        $pageSize = 3;
        $page = max(1, intval($params['PAGE']));
        // Получаем записи
        $result = $entityDataClass::getList([
            'filter' => $filter,
            'order' => $order,
            'count_total' => true,
            'limit' => $pageSize,
            'offset' => ($page-1)*$pageSize,
        ]);
        $items = [];
        while ($row = $result->fetch()) {
            $items[] = $row;
        }
        // результат
        return [
            'success' => true,
            'items' => $items,
            'total' => $result->getCount(),
            'page' => $page,
            'pageSize' => $pageSize,
        ];
    }

    //удаление записи по ID
    public function deleteAction($id)
    {
        if (!Loader::includeModule('highloadblock')) return ['success'=>false, 'error'=>'HL модуль не подключен'];
        $hlblock = HL\HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'TestGalygin']
        ])->fetch();
        if (!$hlblock) return ['success'=>false, 'error'=>'HL-блок не найден'];
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();
        $result = $entityDataClass::delete($id);
        if ($result->isSuccess()) {
            return ['success'=>true];
        } else {
            return ['success'=>false, 'error'=>implode(', ', $result->getErrorMessages())];
        }
    }
    //обновление записи по ID
    public function updateAction($id, $fields)
    {
        if (!Loader::includeModule('highloadblock')) return ['success'=>false, 'error'=>'HL модуль не подключен'];
        $hlblock = HL\HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'TestGalygin']
        ])->fetch();
        if (!$hlblock) return ['success'=>false, 'error'=>'HL-блок не найден'];
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();
        $result = $entityDataClass::update($id, [
            'UF_NAME' => $fields['NAME'],
            'UF_DESCRIPTION' => $fields['DESCRIPTION'],
        ]);
        if ($result->isSuccess()) {
            return ['success'=>true];
        } else {
            return ['success'=>false, 'error'=>implode(', ', $result->getErrorMessages())];
        }
    }
    //получение одной записи по ID
    public function getAction($id)
    {
        if (!Loader::includeModule('highloadblock')) return ['success'=>false, 'error'=>'HL модуль не подключен'];
        $hlblock = HL\HighloadBlockTable::getList([
            'filter' => ['=NAME' => 'TestGalygin']
        ])->fetch();
        if (!$hlblock) return ['success'=>false, 'error'=>'HL-блок не найден'];
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();
        $row = $entityDataClass::getByPrimary($id)->fetch();
        if ($row) {
            return ['success'=>true, 'item'=>$row];
        } else {
            return ['success'=>false, 'error'=>'Запись не найдена'];
        }
    }
} 