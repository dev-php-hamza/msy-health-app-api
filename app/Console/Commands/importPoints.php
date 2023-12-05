<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Traits\Import;
use App\Models\Location;
use App\Models\HealthCenter;

class importPoints extends Command
{
    use Import;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $dir = "/assets/import_files";
        $filesInFolder = File::allFiles(public_path().$dir);
        $countryId = 226;

        foreach ($filesInFolder as $key => $path) {
            $file      = pathinfo($path);

            $fileName  = $file['basename'];
            $file_ext  = $file['extension'];
            if ($fileName === 'points.csv' ) {
                $fileData  = $this->getCsvData($fileName, 'points');
                if ($fileData['status']) {
                    foreach ($fileData['points'] as $key => $point) {
                        try {
                            $location = Location::updateOrCreate([
                                'name' => utf8_encode(trim($point[7])),
                                'country_id' => $countryId 
                            ],[
                                'name' => utf8_encode(trim($point[7]))
                            ]);

                            // updateOrCreate healthCenter/point
                            $pointdb = HealthCenter::updateOrCreate([
                                'name' => utf8_encode(trim($point[0])),
                                'location_id' => $location->id
                            ],[
                                'latitude'    => $point[4],
                                'longitude'   => $point[5],
                                'email'       => $point[3],
                                'phone'       => trim($point[1]),
                                'address'     => utf8_encode(trim($point[2])),
                                'type'        => $point[6],
                                'location_id' => $location->id,
                            ]);

                        } catch (\Exception $e) {
                            dd($e->getMessage());
                        }
                    }
                    $fileToBeDeleted = public_path().$dir.'/'.$fileName;
                    @unlink($fileToBeDeleted);
                    dd('file complete');
                }
                dd('file format issue');
            }
        }
    }
}
