<?php

namespace App\Console\Commands;

use App\Models\Patch;
use File;
use Illuminate\Console\Command;
use ZipArchive;

class PatchWrap extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:wrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push modified files to patches folder and make it zip';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command..
     *
     * @return int
     */
    public function handle() {
        echo "(  _ \(  _ \(_  _)( \( ) / __)( ___)\n";
        echo " )___/ )   / _)(_  )  ( ( (__  )__) \n";
        echo "(__)  (_)\_)(____)(_)\_) \___)(____)\n";

        echo "\e[1;33mHi I am Prince. This your patch update program v2.0.0 thanks \n";
        echo "\e[1;32mstarting patching files...\n";

        if (maildoll_config('patch') == true) { // if patch is true

            // copy modified file to patches folder
            $new_file_path = 'patchs/'; // new_file_path

            $patches = Patch::get();

            $base_domain = base_path().'/'; // base_domain

            echo "\e[1;32mextracting folders... \n";

            foreach ($patches as $patch) {

                // create folder if not exists
                if (! File::exists($base_domain.$new_file_path.$patch->renamed_path)) {
                    File::makeDirectory($base_domain.$new_file_path.$patch->renamed_path, 0777, true); // create folder
                }

                $exact_file_name = basename($patch->file_from); // exact_file_name

                // copy file
                copy($base_domain.$patch->file_from, $base_domain.$patch->file_to);

                // create zip
                $zip = new ZipArchive();
                $zip_name = $patch->renamed_path;
                $zip_path = base_path('patchs/');
                $zip_file = $zip_path.$zip_name.'.zip';
                if ($zip->open($zip_file, ZipArchive::CREATE) === true) {
                    $zip->addFile($patch->file_to, $exact_file_name); // copy file dir, rename file name
                    $zip->close();
                } else {
                    return false;
                }

                // delete folder
                File::deleteDirectory($base_domain.$new_file_path.$patch->renamed_path);
                echo "\e[1;32m✓\e[0m "."\e[1;36m".$patch->file_from." \e[1;93m⟶\e[0m  "."\e[1;35m".$patch->file_to.PHP_EOL;
            }

            echo "\e[1;32mDONE.. \n";
        } else { // if patch is false
            echo 'Patch is disabled';
        }
    }
}
