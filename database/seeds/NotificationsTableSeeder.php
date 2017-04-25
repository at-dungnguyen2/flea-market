<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Post;
use App\Models\Notification;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $users = User::all()->pluck('id');
        $posts = Post::all()->pluck('id');
        $types = array('1', '2');
        for ($i=0; $i < 100; $i++) {
          Notification::create([
            'user_id' => $faker->randomElement($users->toArray()),
            // 'post_id' => $faker->randomElement($posts->toArray()),
            'type' => $types[0],
            'message' => $faker->text,
            'seen' => $faker->boolean(50),
            'approver' => 'default.png',
            'url' => url('/post').'/'. Post::find($faker->randomElement($users->toArray()))->slug,
            'created_at' => $faker->dateTimeThisYear($max = 'now')
          ]);
        }
    }
}
