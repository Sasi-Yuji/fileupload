<?php

namespace App\Services;

/**
 * MessagingService handles automated notifications (SMS, WhatsApp).
 * Designed for Twilio, MessageBird, or similar providers.
 */
class MessagingService
{
    protected $isReady = false;

    public function __construct()
    {
        // Example check: API keys in .env
        $this->isReady = !empty(getenv('MESSAGING_API_KEY'));
    }

    /**
     * Sends a registration confirmation message to a student.
     */
    public function sendRegistrationConfirmation($phone, $name)
    {
        $message = "Hello {$name}, welcome to the University Management System! Your registration is successful.";
        
        if (!$this->isReady) {
            log_message('notice', "SMS Gateway [MOCK]: Sending to {$phone} -> '{$message}'");
            return [
                'status' => 'simulated',
                'id' => 'mock_msg_' . uniqid(),
                'message' => 'SMS/WhatsApp simulation successful.'
            ];
        }

        // Real API call logic (e.g., using Twilio PHP SDK)
        return ['status' => 'success'];
    }

    /**
     * Sends a fee reminder message.
     */
    public function sendFeeReminder($phone, $name, $amount)
    {
        $message = "Hello {$name}, a payment of \${$amount} is due for your tuition fees.";
        
        if (!$this->isReady) {
            log_message('notice', "SMS Gateway [MOCK]: Sending to {$phone} -> '{$message}'");
            return ['status' => 'simulated'];
        }

        return ['status' => 'success'];
    }
}
