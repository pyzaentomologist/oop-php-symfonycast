<?php

namespace App\Builder;

use App\ArmorType\ArmorType;
use App\ArmorType\IceBlockType;
use App\ArmorType\LeatherArmorType;
use App\ArmorType\ShieldType;
use App\AttackType\MultiAttackType;
use App\Character\Character;
use App\Factory\AttackTypeFactoryInterface;

class CharacterBuilder
{
    private int $maxHealth;
    private int $baseDamage;
    private array $attackTypes;
    private string $armorType;
    private int $level;

    public function __construct(private AttackTypeFactoryInterface $attackTypeFactory)
    {
    }

    public function setMaxHealth(int $maxHealth): self
    {
        $this->maxHealth = $maxHealth;

        return $this;
    }

    public function setBaseDamage(int $baseDamage): self
    {
        $this->baseDamage = $baseDamage;

        return $this;
    }

    public function setAttackType(string ...$attackTypes): self
    {
        $this->attackTypes = $attackTypes;

        return $this;
    }

    public function setArmorType(string $armorType): self
    {
        $this->armorType = $armorType;

        return $this;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function buildCharacter(): Character
    {
        $attackTypes = array_map(fn(string $attackType) => $this->attackTypeFactory->create($attackType), $this->attackTypes);
        if (count($attackTypes) === 1) {
            $attackType = $attackTypes[0];
        } else {
            $attackType = new MultiAttackType($attackTypes);
        }

        $character = new Character(
            $this->maxHealth,
            $this->baseDamage,
            $attackType,
            $this->createArmorType(),
        );
        for ($i = 1; $i < $this->level; $i++) {
            $character->levelUp();
        }

        $this->resetBuilder();

        return $character;
    }

    private function createArmorType(): ArmorType
    {
        return match ($this->armorType) {
            'ice_block' => new IceBlockType(),
            'shield' => new ShieldType(),
            'leather_armor' => new LeatherArmorType(),
            default => throw new \RuntimeException('Invalid armor type given')
        };
    }

    private function resetBuilder(): void
    {
        $this->maxHealth = 0;
        $this->baseDamage = 0;
        $this->attackTypes = [];
        $this->armorType = '';
        $this->level = 0;
    }

    public function setAttackTypeFactory(AttackTypeFactoryInterface $attackTypeFactory): void
    {
        $this->attackTypeFactory = $attackTypeFactory;
    }
}
