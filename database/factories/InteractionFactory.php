<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interaction>
 */
class InteractionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $interactions = $this->generateInteractions();
        
        $statuses = ['planned', 'completed', 'cancelled', 'follow-up-required'];

        // Get all customers and users
        $customers = Customer::all();
        $users = User::all();

        return [
            'customer_id' => $customers->random()->id,
            'user_id' => $users->random()->id,
            'type' => $interactions['type'],
            'subject' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'interaction_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement($statuses),
            'outcome' => $this->faker->optional(0.7)->sentence,
            'metadata' => $interactions['metadata'],
        ];
    }

    private function generateInteractions()
    {
        $types = ['email', 'call', 'meeting'];
        $type = $this->faker->randomElement($types);

        // Generate a random number of interactions per customer
        $numInteractions = rand(2, 8);
        
        // Array to store metadata
        $list_metadata = [];

        for ($i = 0; $i < $numInteractions; $i++) {
            // Add type-specific metadata
            switch ($type) {
                case 'email':
                    $metadata = [
                        'email_subject' => $this->faker->sentence,
                        'email_body' => $this->faker->paragraph,
                        'email_status' => $this->faker->randomElement(['sent', 'opened', 'clicked']),
                    ];
                    break;
                case 'call':
                    $metadata = [
                        'duration' => $this->faker->numberBetween(1, 60) . ' minutes',
                        'call_type' => $this->faker->randomElement(['incoming', 'outgoing']),
                        'call_outcome' => $this->faker->randomElement(['successful', 'voicemail', 'no answer']),
                    ];
                    break;
                case 'meeting':
                    $metadata = [
                        'location' => $this->faker->randomElement(['office', 'client site', 'virtual']),
                        'duration' => $this->faker->numberBetween(30, 120) . ' minutes',
                        'attendees' => $this->faker->numberBetween(2, 5),
                    ];
                    break;
            }

            array_push($list_metadata, $metadata);
        }

        return [
            'type' => $type,
            'metadata' => json_encode($list_metadata)
        ];
    }
}
