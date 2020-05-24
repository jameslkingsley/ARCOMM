<?php
  namespace App\Helpers\PBOMission\Mission\MissionElements;

  use App\Helpers\PBOMission\Mission\SQMClass;

  define('MISSION_UNIT_EXPORT_KEYS',
  array('id','class','description','position','primaryWeapon','vehicle','curator'));

  class MissionUnit {
    // All units
    public bool $playable = false;
    public bool $curator = false;
    // Present only if playable
    public int $id;
    public string $class;
    public ?string $variable;
    public ?string $primaryWeapon;
    public ?array $position;
    public ?string $description;
    public ?string $vehicle;

    function __construct(SQMClass $unit) {
      global $unitDefaultWeapons;

      $attributes = $unit->class('Attributes');
      if (!$attributes) return;

      if (!$attributes->attribute('isPlayable') && !$attributes->attribute('isPlayer')) return;
      $this->playable = true;

      $this->id = $unit->attribute('id');
      $this->class = $unit->attribute('type');
      $this->variable = $attributes->attribute('name');
      $this->description = $attributes->attribute('description');

      if ($positionInfo = $unit->class('PositionInfo'))
        $this->position = $positionInfo->attribute('position[]');

      if ($inventory = $attributes->class('Inventory')) {
        // If Iventory class exists but without primaryWeapon class
        // inside then it means unit primary weapon is removed
        if ($primaryWeapon = $inventory->class('primaryWeapon'))
          $this->primaryWeapon = $primaryWeapon->attribute('name');
      } else {
        // If there's no Inventory class inside unit use default weapon
        if (isset($unitDefaultWeapons[$this->class]))
          $this->primaryWeapon = $unitDefaultWeapons[$this->class];
      }
    }

    public function export(): array {
      $data = array();

      foreach (MISSION_UNIT_EXPORT_KEYS as $key)
        if (isset($this->{$key})) $data[$key] = $this->{$key};

      return $data;
    }
  }
?>
