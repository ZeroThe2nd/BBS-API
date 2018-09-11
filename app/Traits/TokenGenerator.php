<?php namespace App\Traits;

use App\User;

/**
 * Trait TokenGenerator
 *
 * @package App\Traits
 */
trait TokenGenerator
{

    /**
     * Generates a unique API key that does not exist in the database yet.
     * True on success, null on failure
     *
     * @return null|string
     */
    public function generateTokenForApi()
    {
        $exists = false;
        $token  = null;
        while (!$exists) {
            $token = $this->generateToken(64);
            if (is_null($token)) {
                // Token generator failed
                return null;
            }
            $exists = User::query()->where([
                'api_token' => $token,
            ])->exists();
            if (!$exists) {
                return $token;
            }
        }

        // Couldn't generate an unused api_token
        return null;
    }

    /**
     * Generates a cryptographically safe random string with a given length
     * Returns `null` on error
     *
     * @param int $length
     *
     * @return string|null
     */
    public function generateToken($length = 64)
    {
        $token        = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max          = strlen($codeAlphabet);

        try {
            for ($i = 0; $i < $length; $i++) {
                $token .= $codeAlphabet[random_int(0, $max - 1)];
            }
        } catch (\Exception $e) {
            echo '<pre>' . print_r($e->getMessage(), true) . '</pre>';

            return null;
        }

        return $token;
    }
}
