<?php

namespace App\Character;

use App\ArmorType\ArmorType;
use App\AttackType\AttackType;
use App\Dice;

class Character
{
    private int $level = 1;
    private int $xp = 0;
    private const MAX_STAMINA = 100;

    private int $currentStamina = self::MAX_STAMINA;
    private int $currentHealth;
    private string $nickname = '';

    public function __construct(
        private int $maxHealth,
        private int $baseDamage,
        private ArmorType $armorType,
        private AttackType $attackType
    ) {
        $this->currentHealth = $this->maxHealth;
    }

    public function attack(): int
    {
        $this->currentStamina -= (25 + Dice::roll(20));
        if ($this->currentStamina <= 0) {
            // can't attack this turn
            $this->currentStamina = self::MAX_STAMINA;

            return 0;
        }

        return $this->attackType->performAttack($this->baseDamage);
    }

    public function receiveAttack(int $damage): int
    {
        $armorReduction = $this->armorType->getArmorReduction($damage);
        $damageTaken = $damage - $armorReduction;
        $this->currentHealth -= $damageTaken;

        return $damageTaken;
    }

    public function getCurrentHealth(): int
    {
        return $this->currentHealth;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getLevel(): int
    {
        return $this->level;
    }
    public function addXp(int $xpEarned): int
    {
        $this->xp += $xpEarned;
        return $this->xp;
    }

    public function levelUp(): void
    {
        // +%15 bonus to stats
        $bonus = 1.15;
        $this->level++;
        $this->maxHealth = floor($this->maxHealth * $bonus);
        $this->baseDamage = floor($this->baseDamage * $bonus);
        // todo: level up attack and armor type
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getXp(): int
    {
        return $this->xp;
    }

    /**
     * Restore player's health before next fight
     */
    public function rest(): void
    {
        $this->currentHealth = $this->maxHealth;
        $this->currentStamina = self::MAX_STAMINA;
    }
}
