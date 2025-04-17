<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateStrongPasswords extends Command
{
    /**
     * The name and signature of the console command.
     * 
     * Features:
     * - Custom count of passwords to generate
     * - Adjustable password length
     * - Options to include/exclude numbers and symbols
     * - Complex mode for enhanced security
     * 
     * @var string
     */
    protected $signature = 'generate:passwords
                            {count=20 : Number of passwords to generate (1-50)}
                            {--length=20 : Length of each password (8-64)}
                            {--complex : Enable complex mode with strict character rules}
                            {--no-numbers : Exclude numbers from passwords}
                            {--no-symbols : Exclude special characters}
                            {--no-ambiguous : Exclude ambiguous characters (i,l,1,0,o,O)}
                            {--no-sequential : Prevent sequential characters (abc, 123)}
                            {--no-duplicate : Prevent duplicate characters}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate cryptographically strong random passwords with customizable options';

    /**
     * Character sets for password generation
     */
    protected array $characterSets = [
        'numbers' => '23456789', // Excludes 0,1 for better readability
        'lowercase' => 'abcdefghjkmnpqrstuvwxyz', // Excludes i,l,o
        'uppercase' => 'ABCDEFGHJKMNPQRSTUVWXYZ', // Excludes I,L,O
        'symbols' => '!#$%&()*+,-./:;<=>?@[]^_{|}~' // Excludes quotes and backticks
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get and validate input parameters
        $count = (int)$this->argument('count');
        $length = (int)$this->option('length');
        $this->validateInputs($count, $length);

        // Generate passwords based on options
        $passwords = $this->generatePasswords(
            count: $count,
            length: $length,
            complex: $this->option('complex') || true,
            noNumbers: $this->option('no-numbers') || false,
            noSymbols: $this->option('no-symbols') || false,
            noAmbiguous: $this->option('no-ambiguous') || false,
            noSequential: $this->option('no-sequential') || true,
            noDuplicate: $this->option('no-duplicate') || true
        );

        // Display results in formatted table
        $this->displayPasswords($passwords);

        return Command::SUCCESS;
    }

    /**
     * Validate command input parameters
     *
     * @param int $count Number of passwords to generate
     * @param int $length Length of each password
     * @throws \Exception When validation fails
     */
    protected function validateInputs(int $count, int $length): void
    {
        if ($count < 1 || $count > 50) {
            $this->error('Error: Count must be between 1 and 50');
            exit(1);
        }

        if ($length < 8 || $length > 64) {
            $this->error('Error: Length must be between 8 and 64 characters');
            exit(1);
        }
    }

    /**
     * Generate multiple passwords based on criteria
     *
     * @param int $count Number of passwords to generate
     * @param int $length Length of each password
     * @param bool $complex Enable complex generation rules
     * @param bool $noNumbers Exclude numbers
     * @param bool $noSymbols Exclude symbols
     * @param bool $noAmbiguous Exclude ambiguous characters
     * @param bool $noSequential Prevent sequential characters
     * @param bool $noDuplicate Prevent duplicate characters
     * @return array Generated passwords
     */
    protected function generatePasswords(
        int $count,
        int $length,
        bool $complex,
        bool $noNumbers,
        bool $noSymbols,
        bool $noAmbiguous,
        bool $noSequential,
        bool $noDuplicate
    ): array {
        $passwords = [];
        $characterPool = $this->buildCharacterPool($noNumbers, $noSymbols, $noAmbiguous);

        for ($i = 0; $i < $count; $i++) {
            $passwords[] = $this->generatePassword(
                length: $length,
                characterPool: $characterPool,
                complex: $complex,
                noSequential: $noSequential,
                noDuplicate: $noDuplicate
            );
        }

        return $passwords;
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
     * Generate a single password with given criteria
     *
     * @param int $length Password length
     * @param string $characterPool Available characters
     * @param bool $complex Use strict generation rules
     * @param bool $noSequential Prevent sequential chars
     * @param bool $noDuplicate Prevent duplicate chars
     * @return string Generated password
     */
    protected function generatePassword(
        int $length,
        string $characterPool,
        bool $complex,
        bool $noSequential,
        bool $noDuplicate
    ): string {
        if ($complex) {
            return $this->generateComplexPassword($length, $characterPool, $noSequential, $noDuplicate);
        }

        // Simple generation using Laravel's Str::random()
        $password = Str::random($length);
        
        // Ensure at least one uppercase and one lowercase
        if (!preg_match('/[A-Z]/', $password)) {
            $password[rand(0, $length - 1)] = $this->characterSets['uppercase'][rand(0, strlen($this->characterSets['uppercase']) - 1)];
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $password[rand(0, $length - 1)] = $this->characterSets['lowercase'][rand(0, strlen($this->characterSets['lowercase']) - 1)];
        }

        return $password;
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
    protected function generateComplexPassword(
        int $length,
        string $characterPool,
        bool $noSequential,
        bool $noDuplicate
    ): string {
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
     * Display generated passwords in formatted table
     *
     * @param array $passwords Array of passwords to display
     */
    protected function displayPasswords(array $passwords): void
    {
        $this->info('=== Strong Password Generation Results ===');
        $this->newLine();
        $this->line("Generated " . count($passwords) . " passwords with length " . strlen($passwords[0]));
        $this->newLine();

        $headers = ['#', 'Password', 'Length', 'Strength', 'Entropy (bits)'];
        $rows = [];

        foreach ($passwords as $index => $password) {
            $rows[] = [
                $index + 1,
                $password,
                strlen($password),
                $this->getPasswordStrength($password),
                $this->calculateEntropy($password)
            ];
        }

        $this->table($headers, $rows);
        $this->newLine();
        $this->line('Note: Always store passwords securely using a password manager');
    }

    /**
     * Calculate password strength rating
     *
     * @param string $password Password to evaluate
     * @return string Strength rating
     */
    protected function getPasswordStrength(string $password): string
    {
        $score = 0;
        
        // Length contributes up to 6 points
        $score += min(6, floor(strlen($password) / 2));
        
        // Character variety
        if (preg_match('/[A-Z]/', $password)) $score += 2;
        if (preg_match('/[a-z]/', $password)) $score += 2;
        if (preg_match('/[0-9]/', $password)) $score += 2;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $score += 3;

        // Deductions for patterns
        if (preg_match('/(.)\1{2,}/', $password)) $score -= 2; // Repeated chars
        if (preg_match('/123|abc|ABC/', $password)) $score -= 2; // Simple sequences

        // Determine strength level
        if ($score >= 12) return 'Very Strong';
        if ($score >= 8) return 'Strong';
        if ($score >= 5) return 'Medium';
        return 'Weak';
    }

    /**
     * Calculate password entropy in bits
     *
     * @param string $password Password to evaluate
     * @return float Entropy in bits
     */
    protected function calculateEntropy(string $password): float
    {
        $charPoolSize = 0;
        $hasLower = preg_match('/[a-z]/', $password);
        $hasUpper = preg_match('/[A-Z]/', $password);
        $hasDigit = preg_match('/[0-9]/', $password);
        $hasSpecial = preg_match('/[^A-Za-z0-9]/', $password);

        if ($hasLower) $charPoolSize += 26;
        if ($hasUpper) $charPoolSize += 26;
        if ($hasDigit) $charPoolSize += 10;
        if ($hasSpecial) $charPoolSize += 32; // Common special chars

        // Calculate entropy: log2(pool_size^length)
        return round(strlen($password) * log($charPoolSize, 2), 1);
    }
}