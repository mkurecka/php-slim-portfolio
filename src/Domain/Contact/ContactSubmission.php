<?php

namespace App\Domain\Contact;

class ContactSubmission
{
    private string $id;
    private string $name;
    private string $email;
    private string $subject;
    private string $message;
    private string $date;
    private string $ip;

    public function __construct(
        string $name,
        string $email,
        string $subject,
        string $message,
        ?string $ip = null,
        ?string $date = null,
        ?string $id = null
    ) {
        $this->id = $id ?? uniqid();
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
        $this->date = $date ?? date('Y-m-d H:i:s');
        $this->ip = $ip ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'date' => $this->date,
            'ip' => $this->ip
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['subject'],
            $data['message'],
            $data['ip'] ?? null,
            $data['date'] ?? null,
            $data['id'] ?? null
        );
    }
}