<?php

namespace Deployer;
require_once __DIR__ . '/vendor/rafaelstz/deployer-magento2/deploy.php';

// Project
set('application', 'magento.test');
set('repository', 'git@github.com:vishaltatva/markshust_magento_2_4_2.git');
set('default_stage', 'staging');
//set('languages', 'en_US pt_BR');
//set('verbose', '-v');

// Env Configurations
set('php', '/usr/local/bin/php');
set('magerun', '/var/www/html/bin/n98-magerun2');
set('composer', '/usr/local/bin/composer');

// Project Configurations
host('192.168.10.11')
    ->hostname('192.168.10.11')
    ->user('tatva')
    ->port(22)
    ->set('deploy_path', '/var/www/html/markshust_magento_2_4_2')
    ->set('branch', 'master')
    ->set('is_production', 1)
    ->stage('staging')
    ->roles('master')
    // ->configFile('~/.ssh/config')
    ->identityFile('~/.ssh/id_rsa_markshust_magento_2_4_2')
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no');