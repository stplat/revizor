<?php

use Illuminate\Database\Seeder;

use App\Models\StorageServer;

class StorageServersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StorageServer::insert([
            [
                'check_id' => '1',
                'auth_needed' => true,
                'auth_type_id' => 1,
                'storage_link' => 'carta.proverim.webcam/2020-09/B59K3265A0001H02/12242008-2403-01aa-aaaa-d8af81404fec-main_1599984817.92_1599985792.92.ts',
                'auth_login' => 'deJN9pjXbJnIh5xQ2SQoqdQOEv7q9k4Hv3KXlFLAZkYeWrNHoZEO60id+fnzLxbSzCtXtYPLmUSnxRgHvNaHdAXd1oQPag47L9AIiCS3600=',
                'auth_password' => 'ucncZqftIUK6Mcmhy2b0pGRZ6YAcp5HdcJ+G85/PWwa9ljojnHkpeqev4sBof7EY8tEBdkcjKEC9HudeG80WBDnE2ckU2E7GqlK3Jxj42gU=',
            ]
        ]);
    }
}
