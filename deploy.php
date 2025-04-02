<?php
namespace Deployer;

require 'recipe/common.php';

// Config

set('repository', 'https://github.com/celfcreative/bunion-relief.git');
set('php_version', '8.4');
set('keep_releases', 5);
set('shared_files', ['.env']);
set('shared_dirs', ['web/app/uploads']);
set('writable_dirs', ['web/app/uploads', 'web/app/cache', 'web/app/mu-plugins', 'web/app/plugins']);
set('theme_name', 'bunionTheme');
set('theme_build_dir', 'public');

// Hosts
host('138.68.158.46')
    ->set('remote_user', 'forge')
    ->set('deploy_path', '/home/{{remote_user}}/p28.celfweb.com')
    ->set('timeout', 600);

/*
 * Cache and optimizations.
 */
desc('Clear WordPress cache');
task('wp:cache:flush', function () {
    cd('{{current_path}}');
    run('wp cache flush');
});

desc('Clear all compiled view files');
task('wp:acorn:view-clear', function () {
    cd('{{current_path}}');
    run('wp acorn view:clear');
});

desc('Compile all of the sites Blade templates');
task('wp:acorn:view-cache', function () {
    cd('{{current_path}}');
    run('wp acorn view:cache');
});

desc('Remove the cached bootstrap files');
task('wp:acorn:optimize:clear', function () {
    cd('{{current_path}}');
    run('wp acorn optimize:clear');
});

desc('Clear all cache and restart PHP');
task('cache:clean', [
    'wp:cache:flush',
    'wp:acorn:view-cache',
    'wp:acorn:view-clear',
    'wp:acorn:optimize:clear',
    'reload:php-fpm',
]);

desc('Checks Current Path');
task('check:cp', function () {
    writeln('{{current_path}}');
});

desc('Checks Deploy Path');
task('check:dp', function () {
    writeln('{{deploy_path}}');
});

desc('Checks Release Path');
task('check:rs', function () {
    writeln('{{release_path}}');
});

/*
 * Sage theme tasks
 */
desc('Install Composer dependencies for theme');
task('theme:composer', function () {
    cd('{{release_path}}/web/app/themes/{{theme_name}}');
    run('composer install --no-dev --prefer-dist --no-progress');
});

desc('Move local build assets to production theme');
task('theme:build', function () {
    $localTarFile = './web/app/themes/{{theme_name}}/{{theme_build_dir}}.tar.gz';
    $remoteThemePath = '{{release_path}}/web/app/themes/{{theme_name}}';

    // Create tarball of the local {{theme_build_dir}} folder
    runLocally("gtar -czf $localTarFile -C './web/app/themes/{{theme_name}}' {{theme_build_dir}}");

    // Upload the tarball to the remote server
    upload($localTarFile, "$remoteThemePath/{{theme_build_dir}}.tar.gz");

    // Extract the tarball on the remote server
    run("tar -xzf $remoteThemePath/{{theme_build_dir}}.tar.gz -C $remoteThemePath");

    // Remove the tarball from the remote server
    run("rm $remoteThemePath/{{theme_build_dir}}.tar.gz");

    // Remove the local tarball
    runLocally("rm $localTarFile");
});

/**
 * Uploads
 * 
 * Usually only for use during the initial upload
 * 
 */
desc('Move local uploads to production folder');
task('uploads:local', function () {
    $localTarFile = './web/app/uploads.tar.gz';
    $remoteSharedPath = '{{deploy_path}}/shared/web/app';

    writeln('Creating tarball of local uploads folder');
    runLocally("gtar --exclude='._*' -czf $localTarFile -C ./web/app uploads");

    writeln("Uploading tarball to $remoteSharedPath");
    upload($localTarFile, "$remoteSharedPath/uploads.tar.gz");
    writeln('Upload complete');

    writeln('Extract tarball on remote server');
    run("cd $remoteSharedPath && tar -xzf uploads.tar.gz");

    writeln('Removing tarball from remote server');
    run("rm $remoteSharedPath/uploads.tar.gz");

    writeln('Removing tarball locally');
    runLocally("rm $localTarFile");
});

desc('Symlink upload folder');
task('uploads:symlink', function () {
    writeln('Creating upload dir symlink');
    cd('{{release_path}}/web/app');
    run('rm -rf uploads');
    run('ln -s {{deploy_path}}/shared/web/app/uploads uploads');
    writeln('Symlink completed');
});

// Restart PHP
task('reload:php-fpm', function () {
    run('sudo systemctl reload php{{php_version}}-fpm');
});


// Run the Deploy
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'deploy:update_code',
    'theme:composer',
    'theme:build',
    'uploads:symlink',
    'deploy:publish',
]);

task('deploy:fresh', [
    'deploy:prepare',
    'deploy:vendors',
    'deploy:update_code',
    'theme:composer',
    'theme:build',
    'uploads:local',
    'uploads:symlink',
    'deploy:publish',
])->desc('For first time release from local');

// Hooks

after('deploy:failed', 'deploy:unlock');
