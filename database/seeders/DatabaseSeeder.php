<?php

namespace Database\Seeders;

use App\Models\Tag;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Plant;
use App\Models\PlantDiscovery;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{

    private $dummyLatLng = [
        ['latitude' => 36.22586294434122, 'longitude' => 37.09748219682034],
        ['latitude' => 36.220523656559024, 'longitude' => 37.147120979809394],
        ['latitude' => 36.2086758104226, 'longitude' => 37.09045003589689],
        ['latitude' => 36.18163598078703, 'longitude' => 37.09789585334525],
        ['latitude' => 36.17579281570278, 'longitude' => 37.10989189256761],
        ['latitude' => 36.1864771302831, 'longitude' => 37.143398071085215],
        ['latitude' => 36.20200017748733, 'longitude' => 37.16408089733066],
        ['latitude' => 36.21985621995999, 'longitude' => 37.186832006200646],
        ['latitude' => 36.18358985001805, 'longitude' => 37.25519096578331],
        ['latitude' => 36.258341997920695, 'longitude' => 37.08745323969682],
        ['latitude' => 36.29102359389738, 'longitude' => 37.157361193493124],
        ['latitude' => 36.28418826936273, 'longitude' => 37.24340175365984],
        ['latitude' => 36.26751423489225, 'longitude' => 37.30875948782263],
        ['latitude' => 35.78321128902025, 'longitude' => 37.50050852245003],
    ];
    public function run(): void
    {

        // Define permissions
        $permissions = [
            'view plants',
            'create plants',
            'edit plants',
            'delete plants',
            'view users',
            'manage tags'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $adminRole->givePermissionTo(Permission::all());
        $userRole->givePermissionTo(['view plants']);

        $tags = Tag::factory()->count(10)->create();
        $privateFiles = Storage::disk('local')->files('plants');

        // Plants
        $plants = Plant::factory()
            ->count(10)
            ->create([
                'status' => fake()->randomElement(Plant::statusTypes),
            ])
            ->each(function ($plant) use ($tags, $privateFiles) {
                $file = collect($privateFiles)->random();
                $path = storage_path('app/private/' . $file);
                $plant->addMedia($path)
                    ->preservingOriginal()
                    ->toMediaCollection('images');

                // Attach tags to plant
                $plant->tags()->attach(
                    $tags->random(rand(2, 5))->pluck('id')->toArray(),
                );
            });

        // Users
        User::factory()
            ->count(50)
            ->create()
            ->each(function ($user) use ($tags, $plants, $adminRole, $userRole, $privateFiles) {
                // Assign role
                $user->assignRole(rand(0, 1) ? $adminRole : $userRole);
                $user->update(fake()->randomElement($this->dummyLatLng),);

                // Attach tag preferences
                $user->tags()->attach(
                    $tags->random(rand(2, 5))->pluck('id')->toArray()
                );

                // Plant discoveries
                $discoveries = PlantDiscovery::factory()->count(2)->create([
                    'user_id'             => $user->id,
                    'plant_id'            => optional($plants->random())->id,
                    'ai_confidence_score' => rand(1, 100) / 10,

                    'admin_notes'         => fake()->text(),
                    'latitude'            => fake()->latitude(),
                    'longitude'           => fake()->longitude(),
                    'area_name'           => fake()->city(),
                    'is_protected_area'   => rand(0, 1),
                ])->each(function (PlantDiscovery $discovery) use ($privateFiles) {
                    $file = collect($privateFiles)->random();
                    $path = storage_path('app/private/' . $file);
                    $discovery->addMedia($path)
                        ->preservingOriginal()
                        ->toMediaCollection('images');
                });
            });
    }
}
