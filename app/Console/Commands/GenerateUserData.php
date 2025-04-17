<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;

class GenerateUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:user {count=1 : Number of users to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate random user data and insert into the database';

    /**
     * Character sets for password generation
     */
    protected array $characterSets = [
        'numbers' => '23456789', // Excludes 0,1 for better readability
        'lowercase' => 'abcdefghjkmnpqrstuvwxyz', // Excludes i,l,o
        'uppercase' => 'ABCDEFGHJKMNPQRSTUVWXYZ', // Excludes I,L,O
        'symbols' => '!#$%&()*+,-./:;<=>?@[]^_{|}~' // Excludes quotes and backticks
    ];

    protected $domains = [
        'gmail.com',
        'yahoo.com',
        'hotmail.com',
        'outlook.com',
        'protonmail.com',
        'icloud.com',
        'mail.com',
        'zoho.com'
    ];
    protected $specialChars = ['.', '-', '_'];
    protected $maxSpecialChars = 2;
    protected $minLocalLength = 5;
    protected $maxLocalLength = 20;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');
        $faker = Faker::create();

        for ($i = 0; $i < $count; $i++) {
            $user = [
                'name' => $faker->name(),
                'email' => $this->generateEmail(),
                'password' => $this->generateComplexPassword(),
            ];

            $this->newLine();
            $this->info("User Name: \t {$user['name']}");
            $this->info("User Email: \t {$user['email']}");
            $this->info("User Password: \t {$user['password']}");

        }

        $this->newLine();
        $this->line("Generated $count users.");
        $this->newLine();

        return COMMAND::SUCCESS;
    }

    /**
     * Generate password with complex rules
     *
     * @param int $length Password length
     * @param string $characterPool Available characters
     * @param bool $noSequential Prevent sequential chars
     * @param bool $noDuplicate Prevent duplicate chars
     * @return string Generated password
     */
    protected function generateComplexPassword(): string
    {
        $length = 20;
        $characterPool = $this->buildCharacterPool(false, false, true);
        $noSequential = true;
        $noDuplicate = true;
        $password = '';
        $previousChar = null;
        $attempts = 0;
        $maxAttempts = $length * 10; // Prevent infinite loops

        while (strlen($password) < $length && $attempts < $maxAttempts) {
            $attempts++;
            $char = $characterPool[random_int(0, strlen($characterPool) - 1)];

            // Skip duplicate characters if required
            if ($noDuplicate && str_contains($password, $char)) {
                continue;
            }

            // Skip sequential characters if required
            if ($noSequential && $previousChar !== null && abs(ord($char) - ord($previousChar)) === 1) {
                continue;
            }

            $password .= $char;
            $previousChar = $char;
        }

        // Fallback if we hit max attempts
        if (strlen($password) < $length) {
            $remaining = $length - strlen($password);
            $password .= substr(str_shuffle($characterPool), 0, $remaining);
        }

        return str_shuffle($password); // Final shuffle for better randomness
    }

    /**
     * Build character pool based on options
     *
     * @param bool $noNumbers Exclude numbers
     * @param bool $noSymbols Exclude symbols
     * @param bool $noAmbiguous Exclude ambiguous characters
     * @return string Available characters for password generation
     */
    protected function buildCharacterPool(bool $noNumbers, bool $noSymbols, bool $noAmbiguous): string
    {
        $pool = '';

        // Add lowercase letters (always included)
        $pool .= $this->characterSets['lowercase'];

        // Add uppercase letters (always included)
        $pool .= $this->characterSets['uppercase'];

        // Add numbers unless excluded
        if (!$noNumbers) {
            $pool .= $this->characterSets['numbers'];
        }

        // Add symbols unless excluded
        if (!$noSymbols) {
            $pool .= $this->characterSets['symbols'];
        }

        // Handle ambiguous characters
        if ($noAmbiguous) {
            $pool = str_replace(
                ['i', 'l', '1', '0', 'o', 'I', 'L', 'O'],
                '',
                $pool
            );
        }

        return $pool;
    }

    /**
     * Generate a random email address
     *
     * @param string|null $name Optional name to base the email on
     * @param string|null $domain Optional specific domain to use
     * @param bool $unique Whether to ensure uniqueness against User model
     * @return string Generated email address
     */
    public function generateEmail(
        ?string $name = null,
        ?string $domain = null
    ): string {
        $localPart = $this->generateLocalPart($name);
        $domain = $domain ?? $this->randomDomain();

        $email = "{$localPart}@{$domain}";

        return $email;
    }

    /**
     * Generate the local part of the email (before @)
     */
    protected function generateLocalPart(?string $name = null): string
    {
        if ($name) {
            return $this->generateFromName($name);
        }

        return $this->generateRandomLocalPart();
    }

    /**
     * Generate email local part from a name
     */
    protected function generateFromName(string $name): string
    {
        $name = preg_replace('/[^a-z0-9]/i', '', $name);
        $name = strtolower($name);

        // Common name patterns
        $patterns = [
            '{f}{l}',        // flast
            '{f}.{l}',       // f.last
            '{f}_{l}',       // f_last
            '{f}{l}{num}',   // flast123
            '{f}{l}{num}',   // flast123
            '{f}{m}{l}',     // fmiddlelast
            '{f}{initial}', // finitial
        ];

        $pattern = $patterns[array_rand($patterns)];
        $num = random_int(1, 999);

        $parts = [
            '{f}' => substr($name, 0, 1),
            '{l}' => substr($name, -min(8, strlen($name))),
            '{m}' => substr($name, 1, 1),
            '{initial}' => substr($name, 0, 1),
            '{num}' => $num,
        ];

        $local = strtr($pattern, $parts);
        $local = substr($local, 0, $this->maxLocalLength);

        return $this->addSpecialChars($local);
    }

    /**
     * Generate completely random local part
     */
    protected function generateRandomLocalPart(): string
    {
        $length = random_int($this->minLocalLength, $this->maxLocalLength);
        $local = Str::lower(Str::random($length));

        return $this->addSpecialChars($local);
    }

    /**
     * Add special characters to local part
     */
    protected function addSpecialChars(string $local): string
    {
        $numSpecial = random_int(0, $this->maxSpecialChars);

        for ($i = 0; $i < $numSpecial; $i++) {
            $pos = random_int(1, strlen($local) - 2);
            $char = $this->specialChars[array_rand($this->specialChars)];
            $local = substr_replace($local, $char, $pos, 0);
        }

        return $local;
    }

    /**
     * Get a random domain
     */
    protected function randomDomain(): string
    {
        return $this->domains[array_rand($this->domains)];
    }

}
