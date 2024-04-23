<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Helpers;
use App\Menu;
use View;
use App\KontenStatis;
use App\TahunAnggaran;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        
        
        //generate menu
        $events->listen(BuildingMenu::class, function (BuildingMenu $event)  {
            $role_ids=[];
            foreach (auth()->user()->roles as $role) {
              $role_ids[]=$role->id_role;
            }

            $menus=Menu::where('parent_id',0)->where('tipe_menu','admin')->whereHas('roles',function($q) use($role_ids){
              $q->whereIn('id_role',$role_ids);
            })->orderBy('urutan','asc')->get();
            foreach ($menus as $menu) {
                if (count($menu->submenus)==0) {
                    $event->menu->add($menu->nama_menu);
                }else{
                    $event->menu->add($menu->nama_menu);
                    $submenus=Menu::where('parent_id',$menu->id_menu)->where('tipe_menu','admin')->whereHas('roles',function($q) use($role_ids){
                      $q->whereIn('id_role',$role_ids);
                    })->orderBy('urutan','asc')->get();
                    foreach ($submenus as $submenu) {
                        if (count($submenu->submenus)==0) {
                            $event->menu->add(['text'=>$submenu->nama_menu,'url'=>$submenu->url,'icon'=>$submenu->icon]);
                        }else{

                            $anothermenu=[];
                            $subsubmenus=Menu::where('parent_id',$submenu->id_menu)->where('tipe_menu','admin')->whereHas('roles',function($q) use($role_ids){$q->whereIn('id_role',$role_ids);
                            })->orderBy('urutan','asc')->get();
                            foreach ($subsubmenus  as $subsubmenu) {
                                $subsubmenu2['text']=$subsubmenu->nama_menu;
                                $subsubmenu2['url']=$subsubmenu->url;
                                $anothermenu[]=$subsubmenu2;
                            }
                            $event->menu->add(['text'=>$submenu->nama_menu,'url'=>$submenu->url,'icon'=>$submenu->icon,'submenu'=>$anothermenu]);
                        }

                    }
                }
            

            }
        });

        $front_menus=Menu::where('tipe_menu','front')->orderBy('urutan')->where('parent_id',0)->get();
        $taaktif=TahunAnggaran::where('status',1)->first();
        View::share('front_menus',$front_menus);
        View::share('taaktif',$taaktif);
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '/Helper/Helpers.php';
        require_once app_path() . '/Helper/Uang.php';
        require_once app_path() . '/Helper/Tanggal.php';
    }
}
