<?php
    namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use App\Models\Location;
    
    class LocationsTableSeeder extends Seeder
    {
        public function run()
        {
            $locations = [
                [
                    'name' => 'Lake Bled',
                    'description' => 'Famous lake with an island and castle.',
                    'latitude' => 46.3630,
                    'longitude' => 14.0900,
                    'picture_url' => 'https://example.com/lake_bled.jpg',
                ],
                [
                    'name' => 'Lake Bohinj',
                    'description' => 'Largest permanent lake in Slovenia.',
                    'latitude' => 46.2826,
                    'longitude' => 13.8669,
                    'picture_url' => 'https://example.com/lake_bohinj.jpg',
                ],
                // Add more lakes as needed
            ];
    
            foreach ($locations as $location) {
                Location::create($location);
            }
        }
    }
    