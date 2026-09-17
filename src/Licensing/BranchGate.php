<?php
declare(strict_types=1);

namespace Vendi\Licensing;

final class BranchGate
{
    public function canOperate(array $branch, array $subscription, ?\DateTimeImmutable $now = null): bool
    {
        $now ??= new \DateTimeImmutable('now');

        $subscriptionActive = ($subscription['status'] ?? null) === 'active';
        $inGrace = !empty($subscription['grace_until'])
            && new \DateTimeImmutable($subscription['grace_until']) >= $now;

        if (!$subscriptionActive && !$inGrace) {
            return false;
        }

        if (($branch['status'] ?? null) !== 'active') {
            return false;
        }

        if (($branch['addon_status'] ?? null) !== 'active' && ($branch['addon_status'] ?? null) !== 'included') {
            return false;
        }

        return !empty($branch['activated_at']);
    }

    public function activationHash(string $token): string
    {
        return hash('sha256', $token);
    }

    public function verifyActivationToken(string $token, string $expectedHash): bool
    {
        return hash_equals($expectedHash, $this->activationHash($token));
    }
}
