<?php
use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;

EventManager::getInstance()->addEventHandler(
    'crm',
    'OnEntityDetailsTabsInitialized',
    function(array & $tabs, array $params) {
        if ($params['ENTITY_TYPE_ID'] == CCrmOwnerType::Deal) {
            $tabs[] = [
                'id' => 'TEST_GALYGIN_TAB',
                'name' => 'Данные',
                'loader' => [
                    'component' => 'testgalygin:dealdata',
                    'componentParams' => [
                        'DEAL_ID' => $params['ENTITY_ID'],
                    ],
                ],
            ];
        }
    }
); 