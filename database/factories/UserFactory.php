<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userAvatars = [
            "https://scontent.frix1-1.fna.fbcdn.net/v/t39.30808-6/257464302_3026380097601701_9189625399320401665_n.jpg?_nc_cat=105&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=wPFjihen6iMAX8h7_qv&_nc_ht=scontent.frix1-1.fna&oh=00_AT-JMWFKp3E0Dk6SnYLPr8P4-kgDzPNqFeekB5SqbQQM4g&oe=62CFA343",
            "https://scontent.frix1-1.fna.fbcdn.net/v/t39.30808-6/275482806_1352512051838163_1296078779361911622_n.jpg?_nc_cat=111&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=EXtFAI7qqqoAX_m2llm&tn=8TUz7uUxxfSFJya-&_nc_ht=scontent.frix1-1.fna&oh=00_AT8PabAQzIukMHZakn6gwAly-6RbWgET_lDPwlBU3ZXang&oe=62CEEBE1",
            "https://scontent.frix1-1.fna.fbcdn.net/v/t39.30808-6/292471101_3087837294861140_5932034967298173356_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=Jq-c8vb4ImUAX8zQ8Qz&_nc_ht=scontent.frix1-1.fna&oh=00_AT8aBGg_S5D38BqfsTgeTTZcqvJc2rqs1LISNR9wmfDkng&oe=62CF2C87",
            "https://scontent.frix1-1.fna.fbcdn.net/v/t39.30808-6/279194107_553355882969670_1905477569834700772_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=qzLTdShfSL4AX9yA0bP&_nc_ht=scontent.frix1-1.fna&oh=00_AT9-GXyRtdfhlM8ls8k47mzJ-vitWPcMcRZIAP8vdPlyoA&oe=62CFAB35",
            "https://scontent.frix1-1.fna.fbcdn.net/v/t39.30808-6/286667066_7512906095450858_5321504415902959177_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=jBo2PLQbNEUAX8B-fx8&_nc_ht=scontent.frix1-1.fna&oh=00_AT8vIHUpX314dL4Jo_bbzUd6QSumMbbYw8hDj1grPTCJWQ&oe=62CE5EC1",
            "https://scontent.frix1-1.fna.fbcdn.net/v/t39.30808-6/282998063_3215389332077366_9063208021821042078_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=7GHjTOvXbc0AX8-Uwju&_nc_ht=scontent.frix1-1.fna&oh=00_AT_OqsXaG451JGrzDISwoAeRh0jSz25W3_rZsfulvhO97A&oe=62CEB986",
            "https://scontent.frix1-1.fna.fbcdn.net/v/t1.6435-9/50576344_2208766482776450_8174405555608092672_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=0td8FJOYuVQAX-WkatR&_nc_oc=AQmPapuKyZBAWRurABjFIPMDlbmA5RibWBsjncYXofoWFYP9QtEtEkqSKVGI9DjKGls&tn=8TUz7uUxxfSFJya-&_nc_ht=scontent.frix1-1.fna&oh=00_AT_Nyq87Wi1cU_Xf999J6WdadK3HU8Ux_Lj6tRiGEQjH3Q&oe=62ED8C4D",
            "https://scontent.frix1-1.fna.fbcdn.net/v/t39.30808-6/278777942_2409666809175828_4025339463459558530_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=VHyLyIzS7BYAX-ifgox&_nc_ht=scontent.frix1-1.fna&oh=00_AT9Dp1UuhFfnBHQN4PSZGUATapf7EFyckqaKqT0DwX3KwQ&oe=62CEB153",
            "https://scontent.frix1-1.fna.fbcdn.net/v/t39.30808-6/274726196_3048031882103398_740891209688695288_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=sQsL5FZhBvAAX8s-L9X&_nc_ht=scontent.frix1-1.fna&oh=00_AT8fsdh9RGi9DPDfZtEPqJTX-tM74I_UOnzj3uZXFS-j9Q&oe=62CDCF9F",
            "https://scontent.frix1-1.fna.fbcdn.net/v/t39.30808-6/290303293_2130011700538249_6997294369462872934_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=OSEaSirxF2UAX_3DIiO&_nc_ht=scontent.frix1-1.fna&oh=00_AT8CKsjChmz9Kk5earyzvDZUX2FPXCmWKog2uTyFKwuDEA&oe=62CE0162",
        ];
        shuffle($userAvatars);
        return [
            'avatar' => $userAvatars[0],
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => $this->faker->numberBetween($min = 0, $max = 1),
            'provider_id' => $this->faker->unique()->numberBetween($min = 100000, $max = 1000000),
            'about_me' => $this->faker->text(350),
            'rating' => $this->faker->randomFloat(2,0, 10),
            'read_school_exp' => rand(0, 100),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
