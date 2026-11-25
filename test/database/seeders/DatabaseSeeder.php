<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Event;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Main Sample Users (aligned with PRD names: Akhil, Priya, Rohan, Meera, etc.)
        $farhan = User::create([
            'name' => 'Farhan Artoria',
            'email' => 'farhan@together.app',
            'phone' => '+6281234567890',
            'password' => Hash::make('password123'),
            'age' => 22,
            'city' => 'Malang',
            'bio' => 'Building cool mobile apps and exploring trails.',
            'avatar_url' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150',
        ]);

        $akhil = User::create([
            'name' => 'Akhil Sharma',
            'email' => 'akhil@together.app',
            'phone' => '+919876543210',
            'password' => Hash::make('password123'),
            'age' => 24,
            'city' => 'Bengaluru',
            'bio' => 'Avid trekker and outdoor sports lover.',
            'avatar_url' => 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150',
        ]);

        $priya = User::create([
            'name' => 'Priya Patel',
            'email' => 'priya@together.app',
            'phone' => '+919876543211',
            'password' => Hash::make('password123'),
            'age' => 23,
            'city' => 'Bengaluru',
            'bio' => 'UI/UX enthusiast, coffee and photography.',
            'avatar_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150',
        ]);

        $rohan = User::create([
            'name' => 'Rohan Verma',
            'email' => 'rohan@together.app',
            'phone' => '+919876543212',
            'password' => Hash::make('password123'),
            'age' => 25,
            'city' => 'Bengaluru',
            'bio' => 'Badminton weekend warrior.',
            'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150',
        ]);

        $meera = User::create([
            'name' => 'Meera Nair',
            'email' => 'meera@together.app',
            'phone' => '+919876543213',
            'password' => Hash::make('password123'),
            'age' => 22,
            'city' => 'Bengaluru',
            'bio' => 'Foodie and weekend road-tripper.',
            'avatar_url' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150',
        ]);

        $sarah = User::create([
            'name' => 'Sarah Jenkins',
            'email' => 'sarah@together.app',
            'phone' => '+6281987654321',
            'password' => Hash::make('password123'),
            'age' => 24,
            'city' => 'Malang',
            'bio' => 'Sunset lover and book reader.',
            'avatar_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150',
        ]);

        // 2. Create Sample Events / Moments (From PRD: Sunset Hike, Badminton Hangout, Design Folks meetup)
        $sunsetHike = Event::create([
            'creator_id' => $akhil->id,
            'title' => 'Sunset Hike & Campfire',
            'description' => 'Join us for a relaxing sunset hike up the trails followed by a chill evening campfire, acoustic music, and stargazing.',
            'category' => 'hiking',
            'image_url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800',
            'location_name' => 'Nandi Hills Trailhead',
            'city' => 'Bengaluru',
            'start_time' => now()->addDays(2)->setHour(16)->setMinute(30),
            'max_participants' => 25,
            'status' => 'upcoming',
        ]);
        $sunsetHike->participants()->attach([$akhil->id, $farhan->id, $priya->id, $rohan->id, $meera->id]);

        $badminton = Event::create([
            'creator_id' => $rohan->id,
            'title' => 'Badminton Hangout & Doubles Match',
            'description' => 'Casual doubles match for intermediate players. Rackets available if needed. Come ready to sweat and have fun!',
            'category' => 'sports',
            'image_url' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=800',
            'location_name' => 'Decathlon Arena Court 3',
            'city' => 'Bengaluru',
            'start_time' => now()->addDays(3)->setHour(9)->setMinute(0),
            'max_participants' => 12,
            'status' => 'upcoming',
        ]);
        $badminton->participants()->attach([$rohan->id, $akhil->id, $farhan->id]);

        $designMeetup = Event::create([
            'creator_id' => $priya->id,
            'title' => 'Design Folks Coffee & Critique',
            'description' => 'Show off what you are currently designing, get constructive feedback, and meet fellow UI/UX and product designers over cold brew.',
            'category' => 'design',
            'image_url' => 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=800',
            'location_name' => 'Third Wave Coffee Roasters',
            'city' => 'Bengaluru',
            'start_time' => now()->addDays(4)->setHour(14)->setMinute(0),
            'max_participants' => 15,
            'status' => 'upcoming',
        ]);
        $designMeetup->participants()->attach([$priya->id, $farhan->id, $sarah->id]);

        $bromoTrip = Event::create([
            'creator_id' => $farhan->id,
            'title' => 'Mount Bromo Sunrise Chase',
            'description' => 'Weekend getaway to catch the golden sunrise over Mount Bromo with hot tea and shared jeep ride.',
            'category' => 'hiking',
            'image_url' => 'https://images.unsplash.com/photo-1589182373726-e4f658ab50f0?w=800',
            'location_name' => 'Penanjakan 1 Viewpoint',
            'city' => 'Malang',
            'start_time' => now()->addDays(6)->setHour(3)->setMinute(0),
            'max_participants' => 10,
            'status' => 'upcoming',
        ]);
        $bromoTrip->participants()->attach([$farhan->id, $sarah->id]);

        // 3. Create Sample Chats & Messages (Step 7)
        // Direct chat: Farhan & Akhil
        $conv1 = Conversation::create([
            'type' => 'direct',
            'last_message_at' => now()->subMinutes(15),
        ]);
        $conv1->participants()->attach([$farhan->id, $akhil->id]);

        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id' => $akhil->id,
            'message' => 'Hey! Are you bringing trekking poles for the sunset hike?',
            'is_read' => false,
            'created_at' => now()->subMinutes(15),
        ]);

        // Direct chat: Farhan & Priya
        $conv2 = Conversation::create([
            'type' => 'direct',
            'last_message_at' => now()->subHours(2),
        ]);
        $conv2->participants()->attach([$farhan->id, $priya->id]);

        Message::create([
            'conversation_id' => $conv2->id,
            'sender_id' => $priya->id,
            'message' => 'Love the Figma preview of the Together app!',
            'is_read' => true,
            'created_at' => now()->subHours(2),
        ]);

        // Group chat: Hiking Group
        $convGroup1 = Conversation::create([
            'type' => 'group',
            'title' => 'Hiking Group',
            'avatar_url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=150',
            'last_message_at' => now()->subMinutes(45),
        ]);
        $convGroup1->participants()->attach([$farhan->id, $akhil->id, $priya->id, $rohan->id, $meera->id]);

        Message::create([
            'conversation_id' => $convGroup1->id,
            'sender_id' => $rohan->id,
            'message' => 'Meeting at the base at 4:15 PM sharp guys!',
            'is_read' => false,
            'created_at' => now()->subMinutes(45),
        ]);

        // Group chat: Design Folks
        $convGroup2 = Conversation::create([
            'type' => 'group',
            'title' => 'Design Folks',
            'avatar_url' => 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=150',
            'last_message_at' => now()->subHours(5),
        ]);
        $convGroup2->participants()->attach([$farhan->id, $priya->id, $sarah->id]);

        Message::create([
            'conversation_id' => $convGroup2->id,
            'sender_id' => $sarah->id,
            'message' => 'Can’t wait for Saturday coffee!',
            'is_read' => true,
            'created_at' => now()->subHours(5),
        ]);
    }
}
