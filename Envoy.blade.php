@setup
    if($env == 'staging') {
        $server = "dukan@dukan.al-raqam.com";
        $path = "web/dukan.al-raqam.com/public_html";
        $healthUrl = "https://dukan.al-raqam.com";
    }
    else {
        $server = "dukan@dukan.al-raqam.com";
        $path = "web/dukan.al-raqam.com/public_html";
        $healthUrl = "https://dukan.al-raqam.com";
    }

    $repo = "ssh://git@gitlab.alilm.uz:3022/dukan/dukan-backend.git";
    $slack = $_ENV['DEPLOY_SLACK_WEBHOOK'] ?? null;

@endsetup

@servers(['web' => $server])

@task('init')
    echo "Path"
    cd {{ $path }}
    ls -al
@endtask

@story('deploy')
    app_pull
    run_composer
    optimize
    migrate
    completion
    health_check
@endstory

@task('app_pull')
    echo "App pull"
    cd {{ $path }}
    git stash
    git pull
@endtask

@task('run_composer')
    echo "Starting deployment"
    cd {{ $path }}
    composer update
@endtask

@task('optimize')
    cd {{ $path }}
        echo "Optimize and cache clear"
        php artisan cache:clear
        php artisan config:clear
        php artisan route:clear
        php artisan optimize
@endtask

@task('migrate')
    cd {{ $path }}
    @if ( $env === 'staging' )
        echo "Migration start"
        php artisan migrate:fresh --seed --force
    @endif
@endtask

@task('completion')
    echo "Deployment complete, you connected as: {{ $server }}";
@endtask

@task('health_check')
    @if ( ! empty($healthUrl) )
        if [ "$(curl --write-out "%{http_code}\n" --silent --output /dev/null {{ $healthUrl }})" == "200" ]; then
            printf "\033[0;32mHealth check to {{ $healthUrl }} OK\033[0m\n"
        else
            printf "\033[1;31mHealth check to {{ $healthUrl }} FAILED\033[0m\n"
        fi
    @else
        echo "No health check set"
    @endif
@endtask
