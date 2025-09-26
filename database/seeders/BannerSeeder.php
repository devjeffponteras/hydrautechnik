<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Banner::insert([
            [
                'album_id' => 1,
                'image_path' => \URL::to('/').'/theme/images/banners/image1.jpg',
                'title' => 'Welcome to Canvas',
                'description' => 'Create just what you need for your Perfect Website. Choose from a wide range of Elements &amp; simply put them on your own Canvas.',
                'alt' => null,
                'url' => \URL::to('/'),
                'order' => 1,
                'user_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'album_id' => 1,
                'image_path' => \URL::to('/').'/theme/images/banners/videos/webfocus.webm',
                'title' => 'Beautifully Flexible',
                'description' => 'Looks beautiful &amp; ultra-sharp on Retina Screen Displays. Powerful Layout with Responsive functionality that can be adapted to any screen size.',
                'alt' => null,
                'url' => null,
                'order' => 2,
                'user_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'album_id' => 1,
                'image_path' => \URL::to('/').'/theme/images/banners/image2.jpg',
                'title' => 'Premium Constructions',
                'description' => "You'll be surprised to see the Final Results of your Creation &amp; would crave for more.",
                'alt' => null,
                'url' => null,
                'order' => 3,
                'user_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ]);
    }
}
