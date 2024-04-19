<?php

namespace App\Console\Commands;

use App\Models\SettingAction;
use App\Models\SettingCont;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class PermissionRouteUpdater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get routes, controllers and actions to update permission list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->alert('START SYNC CONTROLLERS && ACTIONS');
        $routes = Route::getRoutes();
        $created_controllers = 0;
        $created_actions = 0;
        foreach ($routes as $key => $route) {
            if(isset($route->getAction()['controller'])){
                $array = explode('@',$route->getAction()['controller']);
                $action_name = $array[1];
                $controller_array = explode('\\',$array[0]);
                $controller_name = $controller_array[count($controller_array) - 1];
                $controller = SettingCont::where('name',$controller_name)->first();
                if(!$controller){
                    $controller = SettingCont::create(['name' => $controller_name]);
                    $created_controllers++;
                }
                $action = SettingAction::where('name',$action_name)->where('conts_id',$controller->id)->first();
                if(!$action){
                    SettingAction::create(['name' => $action_name,'conts_id' => $controller->id]);
                    $created_actions++;
                }
            }
        }
        $this->info(' Controller and actions list updated successfully!');
        $this->table(['controllers','actions'],[[$created_controllers,$created_actions]]);
        return 0;
    }
}
