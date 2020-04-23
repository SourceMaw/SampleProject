<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
return [
    'Laminas\Cache',
    'Laminas\Mvc\Plugin\FilePrg',
    'Laminas\Hydrator',
    'Laminas\InputFilter',
    'Laminas\Mvc\Plugin\FlashMessenger',
    'Laminas\Mvc\Plugin\Identity',
    'Laminas\Mvc\Plugin\Prg',
    'Laminas\Log',
    'Laminas\ServiceManager\Di',
    'Laminas\I18n',
    'Laminas\Filter',
    'Laminas\Mvc\Console',
    'Laminas\Session',
    'Laminas\Db',
    'Zend\Router',
    'Zend\Validator',
    'Application',
];
