<?php

namespace Deployer;

require_once 'recipe/common.php';

// Configuration
set('repository', 'git@bitbucket.org:weblan/bookkeeper.git');
set('git_tty', true);
set('shared_files', []);
set('shared_dirs', ['web/uploads']);
set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');
set('git_tty', false);

// Hosts
host('lan143.ru')
    ->user('lan143')
    ->set('deploy_path', '/home/lan143/web/bookkeeper')
    ->identityFile('~/.ssh/id_rsa')
    ->forwardAgent(true)
    ->multiplexing(true);

task('deploy:copy_files', function () {
    run('cp {{deploy_path}}/shared/common/config/main-local.php {{release_path}}/common/config/main-local.php');
    run('cp {{deploy_path}}/shared/common/config/params-local.php {{release_path}}/common/config/params-local.php');
    run('cp {{deploy_path}}/shared/frontend/config/main-local.php {{release_path}}/frontend/config/main-local.php');
    run('cp {{deploy_path}}/shared/frontend/config/params-local.php {{release_path}}/frontend/config/params-local.php');
    run('cp {{deploy_path}}/shared/backend/config/main-local.php {{release_path}}/backend/config/main-local.php');
    run('cp {{deploy_path}}/shared/backend/config/params-local.php {{release_path}}/backend/config/params-local.php');
    run('cp {{deploy_path}}/shared/console/config/main-local.php {{release_path}}/console/config/main-local.php');
    run('cp {{deploy_path}}/shared/console/config/params-local.php {{release_path}}/console/config/params-local.php');
})->desc('Copy files');

task('deploy:init', function () {
    run('{{bin/php}} {{release_path}}/init --env=Production --overwrite=n');
})->desc('Initialization');

task('deploy:run_migrations', function () {
    run('{{bin/php}} {{release_path}}/yii migrate up --interactive=0');
})->desc('Run migrations');

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    run('sudo service php7.1-fpm restart');
});

/**
 * Main task
 */
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:copy_files',
    'deploy:init',
    'deploy:shared',
    'deploy:run_migrations',
    'deploy:symlink',
    'php-fpm:restart',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy your project');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
