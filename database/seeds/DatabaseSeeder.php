<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        /**
         * Create Users
         */

        // Create user Sansa Stark
        $sansa = User::create([
            'first_name' => 'Sansa',
            'last_name' => 'Stark',
            'email' => 'sansa@example.org',
            'password' => bcrypt('password'),
        ]);

        // Create user Arya Stark
       $arya = User::create([
            'first_name' => 'Arya',
            'last_name' => 'Stark',
            'email' => 'arya@example.org',
            'password' => bcrypt('password'),
        ]);

        // Create user Brandon Stark
        $brandon = User::create([
            'first_name' => 'Brandon',
            'last_name' => 'Stark',
            'email' => 'brandon@example.org',
            'password' => bcrypt('password'),
        ]);


        /**
         * Create first comment and its nested response comments
         */

        $comment0 = Comment::create([
            'user_id' => $brandon->getKey(),
            'root_id' => null,
            'parent_id' => null,
            'nesting_level' => 0,
            'comment_text' => 'I am the three-eyed Raven!'
        ]);

        $comment0->{Comment::PROPERTY_ROOT_ID} = $comment0->getKey();
        $comment0->save();

        $comment1 = Comment::create([
            'user_id' => $sansa->getKey(),
            'root_id' => $comment0->getKey(),
            'parent_id' => $comment0->getKey(),
            'nesting_level' => 1,
            'comment_text' => 'Get over yourself, Bran.'
        ]);

        $comment2 = Comment::create([
            'user_id' => $arya->getKey(),
            'root_id' => $comment0->getKey(),
            'parent_id' => $comment0->getKey(),
            'nesting_level' => 1,
            'comment_text' => 'Stop bragging, brother.'
        ]);

        $comment3 = Comment::create([
            'user_id' => $brandon->getKey(),
            'root_id' => $comment0->getKey(),
            'parent_id' => $comment1->getKey(),
            'nesting_level' => 2,
            'comment_text' => 'Leave me alone, Sansa!'
        ]);

        $comment4 = Comment::create([
            'user_id' => $brandon->getKey(),
            'root_id' => $comment0->getKey(),
            'parent_id' => $comment2->getKey(),
            'nesting_level' => 2,
            'comment_text' => 'Shut up, Arya!'
        ]);

        $comment5 = Comment::create([
            'user_id' => $arya->getKey(),
            'root_id' => $comment0->getKey(),
            'parent_id' => $comment4->getKey(),
            'nesting_level' => 3,
            'comment_text' => 'Struck a nerve, did I, brother?'
        ]);
    }
}
