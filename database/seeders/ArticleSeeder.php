<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Article::insert([
            [
                'name' => 'THIS IS A STANDARD POST WITH A PREVIEW IMAGE',
                'slug' => $this->convert_to_slug('Lorem ipsum 1'),
                'contents' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'teaser' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'status' => 'Published',
                'is_featured' => '1',
                'user_id' => '1',
                'image_url' => \URL::to('/').'/theme/images/news/news1.jpg',
                'thumbnail_url' => \URL::to('/').'/theme/images/news/news1.jpg',
                'meta_title' => 'title',
                'meta_keyword' => 'keyword',
                'meta_description' => 'description',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'name' => 'THIS IS A STANDARD POST WITH A PREVIEW IMAGE',
                'slug' => $this->convert_to_slug('Lorem ipsum 2'),
                'contents' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'teaser' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'status' => 'Published',
                'is_featured' => '1',
                'user_id' => '1',
                'image_url' => \URL::to('/').'/theme/images/news/news2.jpg',
                'thumbnail_url' => \URL::to('/').'/theme/images/news/news2.jpg',
                'meta_title' => 'title',
                'meta_keyword' => 'keyword',
                'meta_description' => 'description',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'name' => 'THIS IS A STANDARD POST WITH A PREVIEW IMAGE',
                'slug' => $this->convert_to_slug('Lorem ipsum 3'),
                'contents' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'teaser' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'status' => 'Published',
                'is_featured' => '1',
                'user_id' => '1',
                'image_url' => \URL::to('/').'/theme/images/news/news3.jpg',
                'thumbnail_url' => \URL::to('/').'/theme/images/news/news3.jpg',
                'meta_title' => 'title',
                'meta_keyword' => 'keyword',
                'meta_description' => 'description',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'name' => 'THIS IS A STANDARD POST WITH A PREVIEW IMAGE',
                'slug' => $this->convert_to_slug('Lorem ipsum 4'),
                'contents' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'teaser' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'status' => 'Published',
                'is_featured' => '1',
                'user_id' => '1',
                'image_url' => \URL::to('/').'/theme/images/news/news4.jpg',
                'thumbnail_url' => \URL::to('/').'/theme/images/news/news4.jpg',
                'meta_title' => 'title',
                'meta_keyword' => 'keyword',
                'meta_description' => 'description',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ]);
    }

    public function convert_to_slug($url){

        $url = Str::slug($url, '-');

        if (\App\Models\Page::where('slug', '=', $url)->exists()) {
            $url = $url."_2";
        }
        elseif (\App\Models\Article::where('slug', '=', $url)->exists()) {
            $url = $url."_2";
        }

        return $url;
    }
}
