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
set('php', '/usr/bin/php');
set('magerun', '/usr/local/bin/n98-magerun2');
set('composer', '/usr/bin/composer');
set('keep_releases', 1);

set('writable_dirs', [
    '{{magento_dir}}var/page_cache',
    '{{magento_dir}}var/cache',
    '{{magento_dir}}var',
    '{{magento_dir}}pub/static',
    '{{magento_dir}}pub/media',
    '{{magento_dir}}generated'
]);

task('magento:config', function () {
    if (test("[ -f {{release_path}}{{magento_dir}}app/etc/env.php ]")) {
        run("cd {{release_path}}{{magento_dir}} && {{composer}} install");
        run("cd {{release_path}}{{magento_dir}} && {{php}} {{magerun}} cache:enable {{magerun_params}} {{verbose}}");
        run("cd {{release_path}}{{magento_dir}} && {{php}} {{magerun}} config:store:set dev/template/allow_symlink 1 {{magerun_params}} {{verbose}}");
        if (get('is_production')) {
            run("cd {{release_path}}{{magento_dir}} && {{php}} {{magerun}} config:store:set design/search_engine_robots/default_robots INDEX,FOLLOW {{magerun_params}} {{verbose}}");
        } else {
            run("cd {{release_path}}{{magento_dir}} && {{php}} {{magerun}} config:store:set design/search_engine_robots/default_robots NOINDEX,NOFOLLOW {{magerun_params}} {{verbose}}");
        }
    }
});

task('magento:upgrade:db', function () {

    $supports = test('(( $(echo "{{magento_version}} 2.1" | awk \'{print ({{magento_version}} > 2.1)}\') ))');

    if (!$supports) {
        invoke('magento:maintenance:enable');
        run("cd {{release_path}}{{magento_dir}} && {{php}} {{magerun}} module:disable Magento_Version {{magerun_params}} {{verbose}}");
        run("cd {{release_path}}{{magento_dir}} && {{php}} {{magerun}} setup:upgrade {{magerun_params}} {{verbose}}");
        run("cd {{release_path}}{{magento_dir}} && {{php}} {{magerun}} sys:setup:downgrade-versions {{magerun_params}} {{verbose}}");
        invoke('magento:maintenance:disable');
    } else {
        // Check if need update DB
        $isDbUpdated = test('[ "$({{php}} {{release_path}}{{magento_bin}} setup:db:status --no-ansi -n)" == "All modules are up to date." ]');
        if (!$isDbUpdated) {
            write("All modules are up to date.");
            invoke('magento:maintenance:enable');
            run("cd {{release_path}}{{magento_dir}} && {{php}} {{magerun}} module:disable Magento_Version {{magerun_params}} {{verbose}}");
            run("cd {{release_path}}{{magento_dir}} && {{php}} {{magerun}} setup:upgrade {{magerun_params}} {{verbose}}");
//            run("cd {{release_path}}{{magento_dir}} && {{php}} {{magerun}} sys:setup:downgrade-versions  {{magerun_params}} {{verbose}}");
            invoke('magento:maintenance:disable');
        }else{
            write("All modules are up to date.");
        }
    }

});

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