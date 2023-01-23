<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Etudiant>
 */
class EtudiantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
       $userHas= $this->userHas();
        return [
            'classe'=>fake()->name(),
            'address'=>fake()->address(),
            'tel'=>fake()->phoneNumber(),
            'user_id'=>$userHas::factory()
           
        ];
       
    
    }
    public function userHas(){
        return $this->faker->randomElement([
            User::class,
        ]);
        }
}
