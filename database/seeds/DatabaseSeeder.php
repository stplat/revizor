<?php

use App\Models\ApplicantType;
use App\Models\ViolationAutoText;
use App\Models\ViolationComment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ApiTokensTableSeeder::class,
            ApplicantTypesTableSeeder::class,
            CheckTableSeeder::class,
            CheckTypesTableSeeder::class,
            DataTypesTableSeeder::class,
            RolesTableSeeder::class,
            ServersTableSeeder::class,
            UiksTableSeeder::class,
            UsersTableSeeder::class,
            VideosProcessingTableSeeder::class,
            VideosAuthTypesTableSeeder::class,
            VideosTableSeeder::class,
            ViolationProtocolsTableSeeder::class,
            ViolationApplicantNameTableSeeder::class,
            ViolationDecreeTableSeeder::class,
            VotesTableSeeder::class,
            VotesOfficialTableSeeder::class,
            IntermediateVotesOfficialTableSeeder::class,
            CamerasTableSeeder::class,
            BoxRecognitionsTableSeeder::class,
            ViolationsTableSeeder::class,
            VotesDeviationTableSeeder::class,
            CamerasBlacklistTableSeeder::class,
            UikLabelsTableSeeder::class,
            ViolationTypesTableSeeder::class,
            RealCountingVideosTableSeeder::class,
            CheckingVideosTableSeeder::class,
            VideosToSelectCamTableSeeder::class,
            VideosToFindBoxesTableSeeder::class,
            VideosToCountTableSeeder::class,
            VideosToRealCountTableSeeder::class,
            VideosToCheckTableSeeder::class,
            ViolationStatusesTableSeeder::class,
            ContantsTableSeeder::class,
            ImagesTableSeeder::class,
            CheckUsersTableSeeder::class,
            CheckedVideosTableSeeder::class,
            VotesSumTableSeeder::class,
            BoxesInfoTableSeeder::class,
            RealVotesTableSeeder::class,
            ViolationStatusChangesTableSeeder::class,
            BoxRecognitionsImagesTableSeeder::class,
            ViolationAutoTextTableSeeder::class,
            StorageServersTableSeeder::class,
            ViolationImagesTableSeeder::class,
            ViolationCommentsTableSeeder::class,
            UikTimingsTableSeeder::class,
        ]);
    }

    /**
     * @param $min
     * @param $max
     * @param int $round
     * @return float|int
     */
    public static function randomFloat($min, $max, $round = 0)
    {

        $randomfloat = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        if ($round > 0)
            $randomfloat = round($randomfloat, $round);

        return $randomfloat;
    }
}
