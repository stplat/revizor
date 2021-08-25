<?php

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(__DIR__ . './../dumps/videos.sql'));
        //   $data = [];

        //   for ($i = 0; $i < 200; $i++) {
        //       array_push($data, [
        //           'check_id' => rand(1, 4),
        //           'cam_numeric_id' => rand(1, 50),
        //           'uik_id' => rand(1, 50),
        //           'start_local_datetime' => RandomDateHelper(),
        //           'end_local_datetime' => RandomDateHelper(),
        //       ]);
        //   }

        //   Video::insert($data);
        // Video::insert([
        //     [
        //         'check_id' => 1,
        //         'direct_link' => 'carta.proverim.webcam/2020-09/B59K3265A0001H02/12242008-2403-01aa-aaaa-d8af81404fec-main_1599984817.92_1599985792.92.ts',
        //         'cam_numeric_id' => 1,
        //         'uik_id' => 1,
        //         'start_epoch' => 1536498200,
        //         'end_epoch' => 1536498900,
        //         'length' => 700,
        //         'start_local_datetime' => '2021-05-11 00:27:31',
        //         'end_local_datetime' => '2021-05-12 15:27:31',
        //         'storage_server_id' => 1,
        //         'processed_by_api_boxes' => true,
        //         'processed_by_api_counting' => false,
        //     ],
        //     [
        //         'check_id' => 1,
        //         'direct_link' => 'carta.proverim.webcam/2020-09/B59K3265A0001H02/12242008-2403-01aa-aaaa-d8af81404fec-main_1599984817.92_1599985792.92.ts',
        //         'cam_numeric_id' => 1,
        //         'uik_id' => 1,
        //         'start_epoch' => 1536498900,
        //         'end_epoch' => 1536499650,
        //         'length' => 750,
        //         'start_local_datetime' => '2021-05-11 00:27:31',
        //         'end_local_datetime' => '2021-05-11 00:27:31',
        //         'storage_server_id' => 1,
        //         'processed_by_api_boxes' => false,
        //         'processed_by_api_counting' => false,
        //     ],
        //     [
        //         'check_id' => 1,
        //         'direct_link' => 'carta.proverim.webcam/2020-09/B59K3265A0001H02/12242008-2403-01aa-aaaa-d8af81404fec-main_1599984817.92_1599985792.92.ts',
        //         'cam_numeric_id' => 1,
        //         'uik_id' => 1,
        //         'start_epoch' => 1536499650,
        //         'end_epoch' => 1536500450,
        //         'length' => 800,
        //         'start_local_datetime' => '2021-05-11 00:27:31',
        //         'end_local_datetime' => '2021-05-11 00:27:31',
        //         'storage_server_id' => 1,
        //         'processed_by_api_boxes' => false,
        //         'processed_by_api_counting' => false,
        //     ],
        //     [
        //         'check_id' => 1,
        //         'direct_link' => 'carta.proverim.webcam/2020-09/B59K3265A0001H02/12242008-2403-01aa-aaaa-d8af81404fec-main_1599984817.92_1599985792.92.ts',
        //         'cam_numeric_id' => 1,
        //         'uik_id' => 1,
        //         'start_epoch' => 1536500450,
        //         'end_epoch' => 1536501250,
        //         'length' => 800,
        //         'start_local_datetime' => '2021-05-11 00:27:31',
        //         'end_local_datetime' => '2018-09-09 13:54:10',
        //         'storage_server_id' => 1,
        //         'processed_by_api_boxes' => false,
        //         'processed_by_api_counting' => false,
        //     ],
        //     [
        //         'check_id' => 1,
        //         'direct_link' => 'carta.proverim.webcam/2020-09/B59K3265A0001H02/12242008-2403-01aa-aaaa-d8af81404fec-main_1599984817.92_1599985792.92.ts',
        //         'cam_numeric_id' => 1,
        //         'uik_id' => 1,
        //         'start_epoch' => 1536501250,
        //         'end_epoch' => 1536502150,
        //         'length' => 900,
        //         'start_local_datetime' => '2018-09-09 13:54:10',
        //         'end_local_datetime' => '2018-09-09 14:09:10',
        //         'storage_server_id' => 1,
        //         'processed_by_api_boxes' => false,
        //         'processed_by_api_counting' => false,
        //     ],
        //     [
        //         'check_id' => 1,
        //         'direct_link' => 'carta.proverim.webcam/2020-09/B59K3265A0001H02/12242008-2403-01aa-aaaa-d8af81404fec-main_1599984817.92_1599985792.92.ts',
        //         'cam_numeric_id' => 3,
        //         'uik_id' => 2,
        //         'start_epoch' => 1536498900,
        //         'end_epoch' => 1536499650,
        //         'length' => 750,
        //         'start_local_datetime' => '2018-09-09 13:15:00',
        //         'end_local_datetime' => '2021-05-11 00:27:31',
        //         'storage_server_id' => 1,
        //         'processed_by_api_boxes' => false,
        //         'processed_by_api_counting' => false,
        //     ],
        //     [
        //         'check_id' => 1,
        //         'direct_link' => 'carta.proverim.webcam/2020-09/B59K3265A0001H02/12242008-2403-01aa-aaaa-d8af81404fec-main_1599984817.92_1599985792.92.ts',
        //         'cam_numeric_id' => 4,
        //         'uik_id' => 2,
        //         'start_epoch' => 1536499650,
        //         'end_epoch' => 1536500450,
        //         'length' => 800,
        //         'start_local_datetime' => '2021-05-11 00:27:31',
        //         'end_local_datetime' => '2021-05-11 00:27:31',
        //         'storage_server_id' => 1,
        //         'processed_by_api_boxes' => false,
        //         'processed_by_api_counting' => false,
        //     ],
        //     [
        //         'check_id' => 1,
        //         'direct_link' => 'carta.proverim.webcam/2020-09/B59K3265A0001H02/12242008-2403-01aa-aaaa-d8af81404fec-main_1599984817.92_1599985792.92.ts',
        //         'cam_numeric_id' => 4,
        //         'uik_id' => 2,
        //         'start_epoch' => 1536500450,
        //         'end_epoch' => 1536501250,
        //         'length' => 800,
        //         'start_local_datetime' => '2021-05-11 00:27:31',
        //         'end_local_datetime' => '2018-09-09 13:54:10',
        //         'storage_server_id' => 1,
        //         'processed_by_api_boxes' => false,
        //         'processed_by_api_counting' => false,
        //     ],
        // ]);
    }
}
