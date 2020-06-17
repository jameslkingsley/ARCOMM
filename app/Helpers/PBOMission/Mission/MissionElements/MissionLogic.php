<?php
  namespace App\Helpers\PBOMission\Mission\MissionElements;

  use App\Helpers\PBOMission\Mission\SQMClass;

  define('MISSION_LOGIC_EXPORT_KEYS',
  array('id','class','description','curator'));

  define('MISSION_LOGIC_TYPE_UNKNOWN', 0);
  define('MISSION_LOGIC_TYPE_MODULE', 1);
  define('MISSION_LOGIC_TYPE_VIRTUAL_UNIT', 2);
  define('MISSION_LOGIC_TYPE_HEADLESS', 3);

  define('MISSION_LOGIC_MODULE_TYPE_UNKNOWN', 0);
  define('MISSION_LOGIC_MODULE_TYPE_CURATOR', 1);
  define('MISSION_LOGIC_MODULE_TYPE_GENAI', 2);
  define('MISSION_LOGIC_MODULE_TYPE_GENATTACK', 3);

  class MissionLogic {
    public $id;
    public $class;
    public $type = MISSION_LOGIC_TYPE_UNKNOWN;
    // If playable virtual unit
    public $playable = false;
    public $variable;
    public $description;
    public $curator = false;
    // If module
    public $settings;
    public $moduleType;

    function __construct(SQMClass $logic) {
      $this->id = $logic->attribute('id');
      $this->class = $logic->attribute('type');
      $this->variable = $logic->attribute('name');
      $this->description = $logic->attribute('description');

      if ($this->class == 'HeadlessClient_F')
        return $this->type = MISSION_LOGIC_TYPE_HEADLESS;

      if ($logic->attribute('isPlayable')) {
        $this->playable = true;
        return $this->type = MISSION_LOGIC_TYPE_VIRTUAL_UNIT;
      }

      if ($attributes = $logic->class('CustomAttributes')) {
        $this->type = MISSION_LOGIC_TYPE_MODULE;
        $settings = array();
        foreach ($attributes->classes as $attribute) {
          $key = $attribute->attribute('property');
          if (!$key || !$attribute->hasClassPath('Value', 'data')) continue;
          $settings[$key] = $attribute->class('Value')->class('data')->attribute('value');
        }
        if ($settings) $this->settings = $settings;

        if ($this->class == 'ModuleCurator_F')
          return $this->moduleType = MISSION_LOGIC_MODULE_TYPE_CURATOR;

        if ($this->class == 'a3cs_mm_module_genSoldiers')
          return $this->moduleType = MISSION_LOGIC_MODULE_TYPE_GENAI;

        if ($this->class == 'a3cs_mm_module_genAttack')
          return $this->moduleType = MISSION_LOGIC_MODULE_TYPE_GENATTACK;

        return $this->moduleType = MISSION_LOGIC_MODULE_TYPE_UNKNOWN;
      }
    }

    // Called only for virtual units
    public function export(): array {
      $data = array();

      foreach (MISSION_LOGIC_EXPORT_KEYS as $key)
        if (isset($this->{$key})) $data[$key] = $this->{$key};

      return $data;
    }
  }
?>
