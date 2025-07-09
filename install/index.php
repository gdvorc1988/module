<?php
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Application;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// Создание HL-блока
function createTestGalyginHLBlock() {
    if (!Loader::includeModule('highloadblock')) return false;
    $hlblock = HL\HighloadBlockTable::add([
        'NAME' => 'TestGalygin',
        'TABLE_NAME' => 'test_galygin',
    ]);
    if ($hlblock->isSuccess()) {
        $hlblockId = $hlblock->getId();
        // Добавляем поля
        $entity = HL\HighloadBlockTable::getEntity($hlblockId);
        $userTypeEntity = new CUserTypeEntity();
        // Название
        $userTypeEntity->Add([
            'ENTITY_ID' => 'HLBLOCK_' . $hlblockId,
            'FIELD_NAME' => 'UF_NAME',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => '',
            'SORT' => 100,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'Y',
            'SHOW_FILTER' => 'Y',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'Y',
            'SETTINGS' => [],
            'EDIT_FORM_LABEL' => ['ru'=>'Название'],
            'LIST_COLUMN_LABEL' => ['ru'=>'Название'],
            'LIST_FILTER_LABEL' => ['ru'=>'Название'],
        ]);
        // Описание
        $userTypeEntity->Add([
            'ENTITY_ID' => 'HLBLOCK_' . $hlblockId,
            'FIELD_NAME' => 'UF_DESCRIPTION',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => '',
            'SORT' => 200,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => [],
            'EDIT_FORM_LABEL' => ['ru'=>'Описание'],
            'LIST_COLUMN_LABEL' => ['ru'=>'Описание'],
            'LIST_FILTER_LABEL' => ['ru'=>'Описание'],
        ]);
        // Привязка к сделке
        $userTypeEntity->Add([
            'ENTITY_ID' => 'HLBLOCK_' . $hlblockId,
            'FIELD_NAME' => 'UF_DEAL_ID',
            'USER_TYPE_ID' => 'integer',
            'XML_ID' => '',
            'SORT' => 300,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'Y',
            'SHOW_FILTER' => 'Y',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => [],
            'EDIT_FORM_LABEL' => ['ru'=>'Привязка к сделке'],
            'LIST_COLUMN_LABEL' => ['ru'=>'Привязка к сделке'],
            'LIST_FILTER_LABEL' => ['ru'=>'Привязка к сделке'],
        ]);
        return $hlblockId;
    }
    return false;
}

createTestGalyginHLBlock(); 